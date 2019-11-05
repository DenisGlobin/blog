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
//        return Article::latest()
//            ->where('title', 'ilike', '%'.$query.'%')
//            ->orWhere('full_text', 'ilike', '%'.$query.'%')
//            ->paginate(5);

        return Article::selectRaw("*, word_similarity(title, ?) AS sml_t, word_similarity(full_text, ?) AS sml_f", [$query, $query])
            ->whereRaw("word_similarity(title, ?) > 0.05", [$query])
            ->orWhereRaw("word_similarity(full_text, ?) > 0.05", [$query])
            ->orderByRaw('sml_t, sml_f DESC')
            ->paginate(5);
    }
}