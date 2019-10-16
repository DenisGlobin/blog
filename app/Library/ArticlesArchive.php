<?php

namespace App\Library;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait ArticlesArchive
{
    /**
     * Get all articles from months sorted by date
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getArticlesByMonths()
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
     * Get user's articles from months sorted by date
     *
     * @param int $userID
     * @return \Illuminate\Support\Collection
     */
    protected function getUserArticleByMonth(int $userID)
    {
        return DB::table('articles')
            ->select(DB::raw("date_part('month', created_at) as month,
                                  date_part('year', created_at) as year, count(*)"))
            ->where('user_id', $userID)
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
    protected function getArticleArchive($handler = "allArticles", $userID = null)
    {
        $archives = array();
        $months = array();
        switch ($handler){
            case "allArticles":
                $months = $this->getArticlesByMonths();
                break;
            case "userArticles":
                if (isset($userID)) {
                    $months = $this->getUserArticleByMonth($userID);
                    break;
                } else {
                    return null;
                }
        }
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