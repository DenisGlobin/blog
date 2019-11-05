<?php

namespace App\Library;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

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
        // Иначе показывает на месяц меньше, чем нужно
        $maxDate = Carbon::parse($maxDate)->addMonth();
        $monthPeriod = CarbonPeriod::create($minDate, '1 month', $maxDate);

        return $monthPeriod;
    }

    /**
     * Get statistic of articles and comments from months
     *
     * @param array $startDate
     * @param array $endDate
     * @return Collection
     */
    protected function getArticlesAndCommentStatistic(string $startDate, string $endDate, $userID = null)
    {
        // Trasform selected dates to Carbon dates
        $dateFrom = Carbon::createFromIsoFormat('!YYYY-MM', $startDate, 'UTC');
        $dateUntil = Carbon::createFromIsoFormat('!YYYY-MM', $endDate, 'UTC');
        $period = CarbonPeriod::create($dateFrom,'1 month', $dateUntil->addMonth());
        // Set WHERE RAW with userID
        $userSelect = (!is_null($userID) ? ("user_id = " . $userID) : "user_id IS NOT NULL");
        // Select count of all published articles from the related dates
        $articleStats = DB::table('articles')
            ->select(DB::raw("date_part('month', created_at) as month,
                                    date_part('year', created_at) as year,
                                    count(*) as artcl_total"))
            ->where('is_active', 'true')
            ->whereRaw($userSelect)
            ->whereBetween('created_at', [$dateFrom, $dateUntil])
            ->groupBy('year', 'month')
            ->orderByRaw('year, month ASC')
            ->get();
        // Select count of all comments from the related dates
        $commentsStats = DB::table('comments')
            ->select(DB::raw("date_part('month', created_at) as month,
                                    date_part('year', created_at) as year, 
                                    count(*) as cmnt_total"))
            ->whereRaw($userSelect)
            ->whereBetween('created_at', [$dateFrom, $dateUntil])
            ->groupBy('year', 'month')
            ->orderByRaw('year, month ASC')
            ->get();

        $statistics = collect();
        // Loop through the selected period for months
        foreach ($period as $date) {
            $month = $date->isoFormat('MM');
            $year = $date->isoFormat('YYYY');
            // Search statistic ID of record for articles from current month.
            // If no such record is found, then returns false.
            $articlesMonthStatIndex = $articleStats->search(function ($item, $key) use($month, $year) {
                return $item->year == $year && $item->month == $month;
            });
            // If ID is not false
            if (is_int($articlesMonthStatIndex)) {
                $article = $articleStats->get($articlesMonthStatIndex);
                $artclTotal = $article->artcl_total;
            } else {
                $artclTotal = 0;
            }
            // Search statistic ID of record for comments from current month.
            // If no such record is found, then returns false.
            $commentIndex = $commentsStats->search(function ($item, $key) use($month, $year) {
                return $item->year == $year && $item->month == $month;
            });
            // If ID is not false
            if (is_int($commentIndex)) {
                $comment = $commentsStats->get($commentIndex);
                $cmntTotal = $comment->cmnt_total;
            } else {
                $cmntTotal = 0;
            }
            // Save result statistic
            $statistics->push([
                'date' => $date->isoFormat('MMMM YYYY'),
                'artcl_total' => $artclTotal,
                'cmnt_total' => $cmntTotal,
            ]);
        }
        return $statistics;
    }

}