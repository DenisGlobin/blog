<?php

namespace App\Library;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait ArticlesArchive
{
    /**
     * Get count of articles from months sorted by date
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getArticlesForYear()
    {
        return DB::table('articles')
            ->select(DB::raw("date_part('month', created_at) as month,
                                    date_part('year', created_at) as year, count(*)"))
            ->where('is_active', 'true')
            ->where('created_at', '>', Carbon::now()->subYear())
            ->groupBy('year', 'month')
            ->orderByRaw('year, month ASC')
            ->get();
    }

    /**
     * Get array of archive sorted by date
     *
     * @param string $handler
     * @param null $userID
     * @return array
     */
    protected function getArticleArchive()
    {
        $archives = array();
        $months = $this->getArticlesForYear();

        $index = 0;
        foreach ($months as $month) {
            $date = Carbon::createFromIsoFormat('!YYYY-M', $month->year . '-' . $month->month, 'UTC');
            $archives[$index]['date'] = $date->isoFormat('MMMM YYYY');
            $archives[$index]['month'] = $month->month;
            $archives[$index]['year'] = $month->year;
            $archives[$index]['count'] = $month->count;
            $index++;
        }
        return $archives;
    }
}