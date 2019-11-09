<?php

namespace App\Library;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait ArticlesArchive
{
    /**
     * Get array of articles archive for last year sorted by date
     *
     * @return array
     */
    protected function getArticleArchive()
    {
        $months = DB::table('articles')
            ->select(DB::raw("date_part('month', created_at) as month,
                                    date_part('year', created_at) as year, 
                                    count(*)"))
            ->where('is_active', 'true')
            ->where('created_at', '>', Carbon::now()->subYear())
            ->groupBy('year', 'month')
            ->orderByRaw('year, month ASC')
            ->get();

        $archives = collect();
        foreach ($months as $month) {
            $date = Carbon::createFromIsoFormat('!YYYY-M', $month->year . '-' . $month->month, 'UTC');
            $archives->push([
                'date' => $date->isoFormat('MMMM YYYY'),
                'month' => $month->month,
                'year' => $month->year,
                'count' => $month->count
            ]);
        }
        return $archives;
    }
}