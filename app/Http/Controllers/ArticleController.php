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
        //
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

    /**
     * Show form for adding new article.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAddArticleForm()
    {
        $this->middleware(['auth', 'verified']);
        return view('user.add_article');
    }

    /**
     * Save new article to Database.
     *
     * @param ArticleRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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
            $request->session()->flash('error', __('article.save_err') . $ex->getMessage());
            return view('user.add_article');
        }
        $data = [
            'articles' => Article::latest()->paginate(5),
        ];
        return view('index', $data)->with('success', __('article.save_ok'));
    }

    /**
     * Save new comment to Database.
     *
     * @param CommentRequest $request
     * @return \Illuminate\View\View
     */
    public function addComment(CommentRequest $request)
    {
        $this->middleware(['auth', 'verified']);
        $comment = new Comment();
        $comment->message = $request->input('message');
        $comment->user_id = Auth::id();
        $comment->article_id = $request->input('articleID');
        try {
            $comment->save();
        }
        catch (\Exception $ex) {
            $request->session()->flash('error', __('article.comment_err') . $ex->getMessage());
            return redirect()->route('article', ['id' => $request->input('articleID')]);
        }
        $request->session()->flash('success', __('article.comment_ok'));
        return redirect()->route('article', ['id' => $request->input('articleID')]);
    }
}
