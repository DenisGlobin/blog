<?php

namespace App\Http\Controllers;

use App\Mail\ChangeEmail;
use App\User;
use App\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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

    public function editUserInfo(Request $request)
    {
        $user = User::find($request->id);
        $user->name = $request->name;
        //Save new name
        try {
            $user->save();
        }
        catch (\Exception $ex) {
            return view('user.profile', ['user' => $user])->with('error', $ex->getMessage());
        }
        //If user try to change his email
        if ($user->email != $request->email) {
            //Notify user about change email
            try {
                $token = hash('md5', $user->name);
                $request->session()->put('token', $token);
                $request->session()->put('id', $user->id);
                $request->session()->put('email', $request->email);
                Mail::to($user)->queue(new ChangeEmail($user, $token));
            } catch (\Exception $ex) {
                $request->session()->flash('error', 'There was an error sending the email. ' . $ex->getMessage());
                return view('user.profile', ['user' => $user]);
            }
            return view('user.verify_change_email');
        }
        $request->session()->flash('success', 'Changes saved');
        return view('user.profile', ['user' => $user]);
    }

    public function confirmedEmailChange(string $token)
    {
        //Verify token
        if (session()->has('token') && session('token') == $token) {
            $user = User::find(session('id'));
            $user->email = session('email');
            try {
                $user->save();
            }
            catch (\Exception $ex) {
                return view('user.profile', ['user' => $user])->with('error', $ex->getMessage());
            }
        }
        session()->flash('success', 'Email changed');
        return view('user.profile', ['user' => $user]);
    }
}
