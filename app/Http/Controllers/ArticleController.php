<?php

namespace App\Http\Controllers;

use App\Article;
use App\Comment;
use App\Tag;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\ArticleRequest;
use Illuminate\Support\Facades\Auth;
use App\Library\ArticlesArchive;
use App\Library\SearchQuery;
use App\Library\TagsProcessing;

class ArticleController extends Controller
{
    use ArticlesArchive;
    use SearchQuery;
    use TagsProcessing;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])
            ->only(['showAddArticleForm', 'addNewArticle', 'showEditArticleForm', 'updateArticle', 'deleteArticle']);
    }

    /**
     * Show articles list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = [
            'title' => __('article.articles'),
            'articles' => Article::latest()
                        ->where('is_active', true)
                        ->paginate(5),
            'dates' => $this->getArticleArchive(),
            'tags' => Tag::get(),
        ];
        return view('index', $data);
    }

    /**
     * Show selected article
     *
     * @param int $articleID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showArticle(int $articleID)
    {
        $data = [
            'article' => Article::findOrFail($articleID),
            'comments' => Comment::where('article_id', $articleID)
                        ->orderBy('created_at', 'desc')
                        ->get(),
            'tags' => Tag::get(),
        ];
        return view('article', $data);
    }

    /**
     * Show articles written by the user
     *
     * @param int $userID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUserArticles(int $userID)
    {
        $user = User::find($userID);
        if (Auth::check() && Auth::id() == $userID) {
        $data = [
            'title' => __('article.usr_articles', ['user' => $user->name]),
            'articles' => Article::latest()
                        ->where('user_id', $userID)
                        ->paginate(5),
            'dates' => $this->getArticleArchive(),
            'tags' => Tag::get(),
        ];
        } else {
            $data = [
                'title' => __('article.usr_articles', ['user' => $user->name]),
                'articles' => Article::latest()
                        ->where('user_id', $userID)
                        ->where('is_active', true)
                        ->paginate(5),
                'dates' => $this->getArticleArchive(),
                'tags' => Tag::get(),
            ];
        }
        return view('index', $data);
    }

    /**
     * Show articles from the month.
     *
     * @param int $month
     * @param int $year
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getArticlesFromMonth(int $month, int $year)
    {
        $data = [
            'title' => __('article.articles'),
            'articles' => Article::latest()
                        ->where('is_active', true)
                        ->whereMonth('created_at', $month)
                        ->whereYear('created_at', $year)
                        ->paginate(5),
            'dates' => $this->getArticleArchive(),
            'tags' => Tag::get(),
        ];
        return view('index', $data);
    }

    /**
     * Show form for adding new article.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAddArticleForm()
    {
        if (Auth::user()->can('create', Article::class)) {
            return view('user.add_article', ['tags' => Tag::get()]);
        }
        return redirect()->back();
    }

    /**
     * Save new article to Database.
     *
     * @param ArticleRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addNewArticle(ArticleRequest $request)
    {
        if (Auth::user()->can('create', Article::class)) {
            $article = new Article();
            $article->title = $request->input('title');
            $article->full_text = $request->input('fullText');
            $article->short_text = substr($request->input('fullText'), 0, 100);
            $article->is_active = $request->input('isActive');
            if ($request->has('isActive')) {
                $article->is_active = true;
            } else {
                $article->is_active = false;
            }
            $article->user_id = Auth::id();
            // Save article
            try {
                $article->save();
            } catch (\Exception $ex) {
                $request->session()->flash('error', __('article.save_err') . $ex->getMessage());
                return view('user.add_article');
            }
            // Add tags
            $tags = $request->tags;
            if (!is_null($tags)) {
                $this->saveTags($article, $request->tags);
            }
            // Get articles
            $data = [
                'title' => __('article.articles'),
                'articles' => Article::latest()->paginate(5),
                'dates' => $this->getArticleArchive(),
                'tags' => Tag::get(),
            ];
            $request->session()->flash('success', __('article.save_ok'));
            return view('index', $data);
        }
        return redirect()->back();
    }

    /**
     * Show form for editing article
     *
     * @param int $articleID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showEditArticleForm(int $articleID)
    {
        $article = Article::findOrFail($articleID);
        if (Auth::user()->can('update', $article)) {
            $data = [
                'article' => $article,
                'comments' => Comment::where('article_id', $articleID)
                           ->orderBy('created_at', 'desc')->get(),
                'tags' => Tag::get(),
            ];
            return view('user.edit_article', $data);
        }
        return redirect()->back();
    }

    /**
     * Save changes from the article
     *
     * @param ArticleRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function updateArticle(ArticleRequest $request)
    {
        $id = (int)$request->input('id');
        $article = Article::find($id);
        if (Auth::user()->can('update', $article)) {
            $article->title = (string)$request->input('title');
            $article->full_text = (string)$request->input('fullText');
            $article->short_text = substr($request->input('fullText'), 0,100);
            if ($request->has('isActive')) {
                $article->is_active = true;
            }
            else {
                $article->is_active = false;
            }
            $data = [
                'article' => $article,
                'comments' => Comment::where('article_id', $id)
                    ->orderBy('created_at', 'desc')->get(),
                'tags' => Tag::get(),
            ];
            // Delete old tags
            $article->tags()->detach();
            // Add tags
            $this->saveTags($article, (array) $request->tags);
            // Save changes
            try {
                $article->save();
            }
            catch (\Exception $ex) {
                $request->session()->flash('error', __('article.update_err') . $ex->getMessage());
                return redirect()->route('article', $data);
            }
            // Get articles
            $request->session()->flash('success', __('article.update_ok'));
            return redirect()->route('article', $data);
        } else {
            $request->session()->flash('error', __('article.delete_perm_err'));
            return redirect()->back();
        }
    }

    /**
     * Delete selected article
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function deleteArticle(Request $request)
    {
        if($request->ajax()) {
            $id = (int)$request->input('id');
            $article = Article::find($id);
            if (Auth::user()->can('delete', $article)) {
            //if ($article->user->id == Auth::id() || Auth::user()->is_admin) {
                $article->delete();
            } else {
                $request->session()->flash('error', __('article.delete_perm_err'));
                return redirect()->back();
            }
        }
    }

    public function showSearchForm()
    {
        $data = [
            'dates' => $this->getArticleArchive(),
            'tags' => Tag::get(),
        ];
        return view('search', $data);
    }

    public function getQueryResults(Request $request)
    {
        $data = [
            'title' => __('article.search_articles', ['request' => (string)$request->input('query')]),
            'articles' => $this->searchQueryProcessing((string)$request->input('query')),
            'dates' => $this->getArticleArchive(),
            'tags' => Tag::get(),
        ];
        return view('index', $data);
    }

    public function getArticlesByTag(string $tag)
    {
        $data = [
            'title' => __('article.tag_articles', ['tag' => $tag]),
            'articles' => $this->getRelevantArticles($tag),
            'dates' => $this->getArticleArchive(),
            'tags' => Tag::get(),
        ];
        return view('index', $data);
    }
}
