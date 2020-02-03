<?php

namespace App\Http\Controllers\Admin;

use App\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentsController extends Controller
{
  public function index() //отображение в админке
  {
    $comments = Comment::all();

    return view('admin.comments.index', ['comments' => $comments]);
  }

  public function toggle($id)
  {
    $comment = Comment::find($id);
    $comment->toggleStatus();

    return redirect()->back();
  }

  public function destroy($id)
  {
    $comment = Comment::find($id)->delete();

    return redirect()->back();
  }
}
 