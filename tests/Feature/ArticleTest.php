<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Article;

class ArticleTest extends TestCase
{
    public function testNewArticleFactory()
    {
        // Create a single App\Order instance...
        $article = factory(Article::class)->create();
        //Was the user created?
        $this->assertDatabaseHas('articles', [
            'id' => $article->id
        ]);
    }
}
