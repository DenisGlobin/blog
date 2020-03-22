<?php

namespace App\Library;


use App\Article;

trait SearchQuery
{
    /**
     * Get articles containing a search query
     *
     * @param string $query
     * @return mixed
     */
    protected function searchQueryProcessing(string $query)
    {
        return Article::search($query)
            ->where('is_active','true')
            ->paginate(5);
    }
}