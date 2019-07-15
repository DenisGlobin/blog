<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;

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
            'articles' => Article::latest()->paginate(5),
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
            'artcile' => Article::findOrFail($arcticleID),
        ];
        return view('article', $data);
    }
}
