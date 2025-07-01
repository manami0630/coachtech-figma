<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;

class LikeController extends Controller
{
    public function toggle(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $itemId = $request->input('item_id');

        $like = Like::where('user_id', $user->id)->where('item_id', $itemId)->first();

        if ($like) {
            $like->delete();
            return response()->json(['liked' => false]);
        } else {
            Like::create([
                'user_id' => $user->id,
                'item_id' => $itemId,
            ]);
            return response()->json(['liked' => true]);
        }
    }
}