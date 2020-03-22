<?php

namespace App\Http\Controllers;

use App\Http\Requests\BanRequest;
use App\Http\Requests\StatisticRequest;
use Carbon\Carbon;
use App\User;
use App\Library\TagsProcessing;
use App\Library\ArticlesAndCommentsStatistic;

class AdminController extends Controller
{
    use TagsProcessing;
    use ArticlesAndCommentsStatistic;

    protected $dateFrom;
    protected $dateUntil;
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

    /**
     * Get statistic from all adding new articles and comments
     *
     * @param int|null $userID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getStatistic(int $userID = null)
    {
        $dates = $this->getAllDatesPeriod();
        $this->dateFrom = $dates->getStartDate()->format('Y-m');
        $this->dateUntil = $dates->getEndDate()->format('Y-m');
        if (!is_null($userID)) {
            $user = User::find($userID);
            $title = "Statistic for user " . $user->name;
        } else {
            $title = "Statistic";
        }
        $data = [
            'dates' => $dates,
            'userID' => $userID,
            'articlesAndCommentsStat' => $this->getArticlesAndCommentStatistic($this->dateFrom, $this->dateUntil, $userID),
            'tagsCount' => $this->getTagsChart(),
            'title' => $title
        ];
        return view('admin.statistic', $data);
    }

    /**
     * Set date period for statistic from all adding new articles and comments
     *
     * @param StatisticRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function setStatisticPeriod(StatisticRequest $request)
    {
        $this->dateFrom = $request->input('dateFrom');
        $this->dateUntil = $request->input('dateUntil');
        $userID = $request->input('userID');
        if (!is_null($userID)) {
            $user = User::find($userID);
            $title = "Statistic for user " . $user->name;
        } else {
            $title = "Statistic";
        }

        $data = [
            'selectedFrom' => $this->dateFrom,
            'selectedUntil' => $this->dateUntil,
            'userID' => $userID,
            'dates' => $this->getAllDatesPeriod(),
            'articlesAndCommentsStat' => $this->getArticlesAndCommentStatistic($this->dateFrom, $this->dateUntil, $userID),
            'tagsCount' => $this->getTagsChart(),
            'title' => $title
        ];
        return view('admin.statistic', $data);
    }

    /**
     * Set date while user is banned
     *
     * @param BanRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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
