<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Like;

class LikeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|integer',
        ]);

        $like = new Like([
            'user_id' => Auth::id(),
            'item_id' => $request->input('item_id')
        ]);
        $like->save();

        return response()->json(['success' => true]);
    }

    // いいね解除
    public function toggle(Request $request)
    {
        $user = Auth::user();
        $itemId = $request->input('item_id');
        $item = Item::findOrFail($itemId);

        // 既にいいねしているか
        $like = Like::where('user_id', $user->id)
                    ->where('item_id', $item->id)
                    ->first();

        if ($like) {
            // いいねを解除
            $like->delete();
            $liked = false;
        } else {
            // いいねを作成
            Like::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
            ]);
            $liked = true;
        }

        // 最新のいいね数
        $likesCount = $item->likes()->count();

        return response()->json([
            'liked' => $liked,
            'likesCount' => $likesCount
        ]);
    }
}