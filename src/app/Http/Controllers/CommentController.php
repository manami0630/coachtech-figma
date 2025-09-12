<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(CommentRequest $request)
    {
        $validated = $request->validated();

        Comment::create([
            'item_id' => $validated['item_id'],
            'user_id' => $validated['user_id'],
            'content' => $validated['content'],
        ]);

        return redirect()->back();
    }
}
