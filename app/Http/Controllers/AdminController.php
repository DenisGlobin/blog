<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Library\Date\ArticlesArchive;

class AdminController extends Controller
{
    use ArticlesArchive;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Show all users.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data = [
            'users' => User::get(),
        ];
        return view('admin.index', $data);
    }

    public function getStatistic()
    {
        $data = [
            'dates' => $this->getArticleArchive("allArticles"),
            'title' => 'Statistic for all articles'
        ];
        return view('admin.statistic', $data);
    }

    public function getUserStatistic(int $userID)
    {
        $data = [
            'dates' => $this->getArticleArchive("userArticles", $userID),
            'title' => "Statistic for user's articles"
        ];
        return view('admin.statistic', $data);
    }
}
