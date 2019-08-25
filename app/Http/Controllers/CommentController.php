<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
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
     * Save new comment to Database.
     *
     * @param CommentRequest $request
     * @return \Illuminate\View\View
     */
    public function addComment(CommentRequest $request)
    {
        //$this->middleware(['auth', 'verified']);
        $comment = new Comment();
        $comment->message = $request->input('message');
        $comment->user_id = Auth::id();
        $comment->article_id = $request->input('articleID');
        try {
            $comment->save();
        }
        catch (\Exception $ex) {
            $request->session()->flash('error', __('comment.save_err') . $ex->getMessage());
            return redirect()->route('article', ['id' => $request->input('articleID')]);
        }
        $request->session()->flash('success', __('comment.save_ok'));
        return redirect()->route('article', ['id' => $request->input('articleID')]);
    }

    public function deleteComment(Request $request)
    {
        //
    }
}
