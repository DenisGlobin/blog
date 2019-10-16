<?php

namespace App\Library;


use App\Article;
//use Illuminate\Support\Facades\DB;

trait SearchQuery
{
    protected function searchQueryProcessing(string $query)
    {
        //return DB::table('articles')
          //  ->select(DB::raw("*"))
            //->where('is_active', true)
            //->whereNull('deleted_at')
            //->whereRaw('title or full_text like %'. $query .'%')
//        ->whereRaw('title or full_text like %?%', [$query])
//            ->orderBy('created_at', 'ASC')
//            ->get();
        return Article::latest()
            ->where('title', 'ilike', '%'.$query.'%')
            ->orWhere('full_text', 'ilike', '%'.$query.'%')
            ->paginate(5);
    }
}