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

    /**
     * Show user's info.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUserInfo(int $id)
    {
        return view('profile', ['user' => User::findOrFail($id)]);
    }

    /**
     * Edit user's profile info.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editUserInfo(Request $request)
    {
        $user = User::find($request->id);
        //Check if this login is unique
        if (User::where('name', $request->name)->get()) {
            $request->session()->flash('error', __('profile.login_exst'));
            return view('user.profile', ['user' => $user]);
        }
        $user->name = $request->name;
        //Save new name
        try {
            $user->save();
        }
        catch (\Exception $ex) {
            return view('user.profile', ['user' => $user])->with('error', __('profile.save_err') . $ex->getMessage());
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
                $request->session()->flash('error', __('profile.email_err') . $ex->getMessage());
                return view('user.profile', ['user' => $user]);
            }
            return view('user.verify_change_email');
        }
        $request->session()->flash('success', __('profile.save_ok'));
        return view('user.profile', ['user' => $user]);
    }

    /**
     * Confirming to change user's email adress.
     *
     * @param string $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function confirmedEmailChange(string $token)
    {
        //Verify token
        if (session()->has('token') && session('token') == $token) {
            //Save changes.
            $user = User::find(session('id'));
            $user->email = session('email');
            try {
                $user->save();
            }
            catch (\Exception $ex) {
                session()->flash('error', __('profile.save_err') . $ex->getMessage());
                return view('user.profile', ['user' => $user]);
            }
        }
        session()->flash('success', __('profile.save_ok'));
        return view('user.profile', ['user' => $user]);
    }
}
