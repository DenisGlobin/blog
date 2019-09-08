<?php

namespace App\Http\Controllers;

use App\Article;
use App\Comment;
use App\Http\Requests\CommentRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\ArticleRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    /**
     * Get articles from months sorted by date
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getArticlesFromMonths()
    {
//        return DB::select("select date_part('month', created_at) as month,
//                                  date_part('year', created_at) as year, count(*) from articles
//                                  where is_active='true' and created_at > :lastYear
//                                  group by year, month order by year, month", ['lastYear' => Carbon::now()->subYear()]);

        return DB::table('articles')
            ->select(DB::raw("date_part('month', created_at) as month,
                                  date_part('year', created_at) as year, count(*)"))
            ->where('is_active', 'true')
            ->where('created_at', '>', Carbon::now()->subYear())
            ->groupBy('year', 'month')
            ->orderByRaw('year, month DESC')
            ->get();

//        return Article::latest()->where('is_active', 'true')->where('created_at', '>', Carbon::now()->subYear())
//            ->get()->groupBy(function($val) {
//                return Carbon::parse($val->created_at)->isoFormat('MMMM  YYYY');
//            });
    }

    /**
     * Get array of archive sorted by date
     *
     * @return array
     */
    protected function getArchives()
    {
        $archives = array();
        $months = $this->getArticlesFromMonths();
        $index = 0;
        foreach ($months as $month) {
            $date = Carbon::createFromIsoFormat('!YYYY-M', $month->year . '-' . $month->month, 'UTC');
            $archives[$index]['date'] = $date->isoFormat('MMMM YYYY');
            $archives[$index]['month'] = $month->month;
            $archives[$index]['year'] = $month->year;
            $archives[$index]['count'] = $month->count;
            $index++;
        }
        return $archives;
    }
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
            'articles' => Article::latest()
                        ->where('is_active', true)
                        ->paginate(5),
            'dates' => $this->getArchives(),
        ];
        //dd($data);
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
        if (Auth::check() && Auth::id() == $userID) {
        $data = [
            'articles' => Article::latest()
                        ->where('user_id', $userID)
                        ->paginate(5),
            'dates' => $this->getArchives(),
        ];
        } else {
            $data = [
                'articles' => Article::latest()
                        ->where('user_id', $userID)
                        ->where('is_active', true)
                        ->paginate(5),
                'dates' => $this->getArchives(),
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
            'articles' => Article::latest()
                        ->where('is_active', true)
                        ->whereMonth('created_at', $month)
                        ->whereYear('created_at', $year)
                        ->paginate(5),
            'dates' => $this->getArchives(),
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
        $article->short_text = substr($request->input('fullText'), 0,100);
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
        $request->session()->flash('success', __('article.save_ok'));
        return view('index', $data);
    }

    /**
     * Show form for editing article
     *
     * @param int $articleID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showEditArticleForm(int $articleID)
    {
        $this->middleware(['auth', 'verified']);
        $data = [
            'article' => Article::findOrFail($articleID),
            'comments' => Comment::where('article_id', $articleID)
                ->orderBy('created_at', 'desc')->get(),
        ];
        return view('user.edit_article', $data);
    }

    /**
     * Save changes from the article
     *
     * @param ArticleRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function updateArticle(ArticleRequest $request)
    {
        $this->middleware(['auth', 'verified']);
        $id = (int)$request->input('id');
        $article = Article::find($id);
        if ($article->user->id == Auth::id()) {
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
            ];
            try {
                $article->save();
            }
            catch (\Exception $ex) {
                $request->session()->flash('error', __('article.update_err') . $ex->getMessage());
                return redirect()->route('article', $data);
            }
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
        $this->middleware(['auth', 'verified']);
        if($request->ajax()) {
            $id = (int)$request->input('id');
            $article = Article::find($id);
            if ($article->user->id == Auth::id()) {
                $article->delete();
            } else {
                $request->session()->flash('error', __('article.delete_perm_err'));
                return redirect()->back();
            }
        }
    }
}
