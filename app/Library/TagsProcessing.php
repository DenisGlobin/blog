<?php

namespace App\Library;

use App\Article;
use App\Tag;

trait TagsProcessing
{
    /**
     * Save new tags to DB
     *
     * @param Article $article
     * @param array $tags
     */
    protected function saveTags(Article $article, array $tags)
    {
        // Delete duplicates
        $tags = array_unique($tags);
        foreach ($tags as $tag) {
            // If the tag exists in the DB
            $exsistTag = Tag::where('name', $tag)->first();
            if ($exsistTag != null) {
                $article->tags()->attach($exsistTag->id);
            } else {
                // Add the tag to DB
                $newTag = new Tag();
                $newTag->name = (string) $tag;
                $newTag->save();
                $article->tags()->attach($newTag->id);
            }
        }
    }

    /**
     * Get articles with required tag
     *
     * @param string $tagName
     * @return mixed
     */
    protected function getRelevantArticles(string $tagName)
    {
        $articles = Article::whereHas('tags', function ($query) use ($tagName) {
            $query->where('name', $tagName);
        })->paginate(5);
        return $articles;
    }

    /**
     * Get tags chart statistic
     *
     * @return array
     */
    protected function getTagsChart()
    {
        $tagsCounter = array();
        $tags = Tag::withCount('articles')->get();
        foreach ($tags as $tag) {
            $tagsCounter[$tag->name] = $tag->articles_count;
        }
        return $tagsCounter;
    }
}