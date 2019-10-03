<?php

namespace App\Http\Controllers;

use App\Http\Requests\BanRequest;
use Carbon\Carbon;
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
            'users' => User::orderBy('id', 'asc')->get(),
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

    public function setBanForUsers(BanRequest $request)
    {
        $data = [
            'users' => User::orderBy('id', 'asc')->get(),
        ];
        $selectedUsers = $request->input('usrChkBox');
        // If no user is selected
        if (is_null($selectedUsers)) {
            $request->session()->flash('error', __('banned.no_usr_select'));
            return view('admin.index', $data);
        }
        $bannedUntil = Carbon::now();
        $banFor = $request->input('banSelector');
        // Which action is selected
        switch ($banFor) {
            case 0:
                $bannedUntil = null;
                break;
            case 1:
                $bannedUntil->addDay();
                break;
            case 2:
                $bannedUntil->addWeek();
                break;
            case 3:
                $bannedUntil->addMonth();
                break;
            default:
                $request->session()->flash('error', __('banned.no_act_select'));
                return view('admin.index', $data);
        }
        foreach ($selectedUsers as $userID) {
            $user = User::findOrFail($userID);
            $user->banned_until = $bannedUntil;
            $user->save();
        }
        $request->session()->flash('success', __('banned.ban_ok'));
        return view('admin.index', $data);
    }
}
