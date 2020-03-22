<?php

namespace App\Library;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

trait ArticlesAndCommentsStatistic
{
    protected function getAllDatesPeriod()
    {
        $articlesDates = DB::table('articles')
            ->select(DB::raw("min(created_at) as min_date,
                                    max(created_at) as max_date"))
            ->where('is_active', 'true')
            ->get();

        $commentsDates = DB::table('comments')
            ->select(DB::raw("min(created_at) as min_date,
                                    max(created_at) as max_date"))
            ->get();

        foreach ($articlesDates as $date) {
            $minDate = $date->min_date;
            $maxDate = $date->max_date;
        }
        foreach ($commentsDates as $date) {
            if ($date->max_date > $maxDate) {
                $maxDate = $date->max_date;
            }
        }

        $maxDate = Carbon::parse($maxDate)->addMonth();
        $monthPeriod = CarbonPeriod::create($minDate, '1 month', $maxDate);

        return $monthPeriod;
    }

    /**
     * Get statistic of articles and comments from months
     *
     * @param string $startDate
     * @param string $endDate
     * @param null $userID
     * @return array
     */
    protected function getArticlesAndCommentStatistic(string $startDate, string $endDate, $userID = null)
    {
        // Transform selected dates to Carbon dates
        $dateFrom = Carbon::createFromIsoFormat('!YYYY-MM', $startDate, 'UTC');
        $dateUntil = Carbon::createFromIsoFormat('!YYYY-MM', $endDate, 'UTC');
        $period = CarbonPeriod::create($dateFrom,'1 month', $dateUntil->addMonth());
        // Set dates period raw
        $raw = "";
        foreach ($period as $date) {
            $month = $date->isoFormat('MM');
            $year = $date->isoFormat('YYYY');
            $raw = $raw . " (" . $year . ", " . $month . ", null, null),";
        }
        // Delete last comma in the string
        $raw = substr($raw, 0, -1);
        // Set where raw with userID
        $userSelect = (!is_null($userID) ? ("user_id = " . $userID) : "user_id IS NOT NULL");

        $statistics = DB::select("SELECT * FROM (VALUES $raw) AS period (year, month, artcl_total, cmnt_total)
                                            LEFT JOIN (SELECT 
                                                date_part('month', created_at) AS month,
                                                date_part('year', created_at) AS year,
                                                COUNT(*) AS artcl_total
                                                FROM articles
                                                WHERE $userSelect
                                                GROUP BY year, month) AS artcl
                                            USING (month, year)
                                            LEFT JOIN (SELECT 
                                                date_part('month', created_at) AS month,
                                                date_part('year', created_at) AS year,
                                                COUNT(*) AS cmnt_total
                                                FROM comments
                                                WHERE $userSelect
                                                GROUP BY year, month) AS cmnt
                                            USING (month, year)
                                            ORDER BY period.year, period.month ASC
                                            ");
        // Change date's presentation
        $finalStat = collect();
        foreach ($statistics as $statistic) {
            $date = Carbon::createFromIsoFormat('!YYYY-M', $statistic->year . '-' . $statistic->month, 'UTC');
            // Save result statistic
            $finalStat->push([
                'date' => $date->isoFormat('MMMM YYYY'),
                'artcl_total' => (int) $statistic->artcl_total,
                'cmnt_total' => (int) $statistic->cmnt_total,
            ]);
        }
        return $finalStat;
    }

}