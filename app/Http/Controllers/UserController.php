<?php

namespace App\Http\Controllers;

use App\User;
use App\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the user profile.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('user.profile', ['user' => Auth::user()]);
    }

    public function getMyArticles()
    {
        $data = [
            'articles' => Article::latest()->where('user_id', Auth::id())->paginate(5),
            'user' => Auth::user(),
        ];
        return view('user.articles', $data);
    }

    public function getUserInfo(int $id)
    {
        return view('profile', ['user' => User::findOrFail($id)]);
    }

    public function saveUserInfo(Request $request)
    {
        $user = User::find($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        try {
            $user->save();
        }
        catch (\Exception $ex) {
            return view('user.profile', ['user' => $user])->with('error', $ex->getMessage());
        }
        return view('user.profile', ['user' => $user])->with('success', 'Changes saved');
    }
}
