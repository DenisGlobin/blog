<?php

namespace App\Http\Controllers;

use App\Article;
use App\Comment;
use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;
use App\Http\Requests\ArticleRequest;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware(['auth', 'verified']);
    }

    /**
     * Show articles list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = [
            'articles' => Article::latest()->where('is_active', true)->paginate(5),
        ];
        return view('index', $data);
    }

    /**
     * Show selected article
     *
     * @param int $arcticleID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showArticle(int $arcticleID)
    {
        $data = [
            'article' => Article::findOrFail($arcticleID),
            'comments' => Comment::where('article_id', $arcticleID)
                        ->orderBy('created_at', 'desc')->get(),
        ];
        return view('article', $data);
    }

    public function showAddArticleForm()
    {
        $this->middleware(['auth', 'verified']);
        return view('user.add_article');
    }

    public function addNewArticle(ArticleRequest $request)
    {
        $this->middleware(['auth', 'verified']);
        $article = new Article();
        $article->title = $request->input('title');
        $article->full_text = $request->input('fullText');
        $article->short_text = substr($request->input('fullText'), 1,100);
        $article->is_active = $request->input('isActive');
        if ($request->has('isActive')) {
            $article->is_active = true;
        }
        else {
            $article->is_active = false;
        }
        $article->user_id = Auth::id();
        try {
            $article->save();
        }
        catch (\Exception $ex) {
//            $request->session()->flash('error', $ex->getMessage());
//            return back();
            return view('user.add_article')->with('error', $ex->getMessage());
        }
        $data = [
            'articles' => Article::latest()->paginate(5),
        ];
        return view('index', $data)->with('success', 'The new article was added');
    }

    public function addComment(CommentRequest $request)
    {
        $this->middleware(['auth', 'verified']);
        $comment = new Comment();
        $comment->message = $request->input('message');
        $comment->user_id = $request->input('userID');
        $comment->article_id = $request->input('articleID');
        try {
            $comment->save();
        }
        catch (\Exception $ex) {
            return $this->showArticle($request->input('articleID'))->with('error', $ex->getMessage());
        }
        return $this->showArticle($request->input('articleID'))->with('success', 'The new comment was added');
    }
}
