<?php

namespace Tests\Feature;

use App\Comment;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Article;

class ArticleTest extends TestCase
{
    protected $article;

    public function setUp(): void
    {
        parent::setUp();
        $randomDay = Carbon::now()->subDays(rand(1, 365));
        // Create a single App\Article instance...
        $this->article = factory(Article::class)->create([
            'created_at' => $randomDay,
            'updated_at' => $randomDay,
        ]);
    }

    public function testNewArticleFactory()
    {
        //Was the user created?
        $this->assertDatabaseHas('articles', [
            'id' => $this->article->id
        ]);
    }

    public function testNewCommentFactory()
    {
        $comment = factory(Comment::class)->create([
            'article_id' => $this->article->id,
        ]);
        //Was the comment created?
        $this->assertDatabaseHas('comments', [
            'id' => $comment->id
        ]);
    }
}
