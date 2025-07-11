<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;


class ItemController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('page') && $request->page == 'mylist') {
            $items = Item::whereHas('likes', function($query){
                $query->where('user_id', Auth::id());
            })->get();
        } else {
            if (Auth::check()) {
                $items = Item::where('user_id', '!=', Auth::id())->get();
            } else {
                $items = Item::all();
            }
        }
        return view('list', compact('items'));
    }

    public function details($id)
    {
        $user = auth()->user();
        $item = Item::find($id);
        return view('details', compact('item','user'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $items = Item::where('name', 'LIKE', '%' . $keyword . '%')->get();

        return view('list', compact('items'));
    }

    public function exhibit()
    {
        $user = auth()->user();
        $categories = Category::all();
        return view('exhibit',compact('user','categories'));
    }

    public function store(ExhibitionRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/uploads');
            $image_path = str_replace('public/', '', $path);
        }

        $categoryIds = $request->input('categories', []);

            $item = new Item();
            $item->name = $validated['name'];
            $item->image = $image_path;
            $item->description = $validated['description'];
            $item->condition = $validated['condition'];
            $item->price = $validated['price'];
            $item->brand_name = $validated['brand_name'] ?? null;
            $item->user_id = auth()->user()->id;
            $item->save();

            if (!empty($categoryIds)) {
                $item->categories()->sync($categoryIds);
            }
        return redirect('/mypage');
    }

    public function show($id)
    {
        $item = Item::findOrFail($id);
        $likedItems = Like::where('user_id', auth()->id())->pluck('item_id')->toArray();
        $likesCount = $item->likes()->count();
        $comments = Comment::where('item_id', $id)->with('user')->get();

        return view('details', compact('item', 'comments', 'likesCount', 'likedItems'));
    }
}

