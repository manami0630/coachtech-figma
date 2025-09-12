<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Item;
use App\Models\Address;
use App\Models\Evaluation;
use App\Models\ChatRead;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $address = Address::where('user_id', $user->id)->first();

        return view('edit', compact('user', 'address'));
    }

    public function update(AddressRequest $addressRequest, ProfileRequest $profileRequest)
    {
        $validatedAddress = $addressRequest->validated();
        $validatedProfile = $profileRequest->validated();

        $user = auth()->user();

        if ($profileRequest->hasFile('profile_image')) {
            $path = $profileRequest->file('profile_image')->store('public/profile_images');
            $user->profile_image = str_replace('public/', '', $path);
        }

        if (isset($validatedAddress['name'])) {
            $user->name = $validatedAddress['name'];
        }
        $user->save();

        $address = Address::firstOrNew(['user_id' => $user->id]);

        if (isset($validatedAddress['postal_code'])) {
            $address->postal_code = $validatedAddress['postal_code'];
        }

        if (isset($validatedAddress['address'])) {
            $address->address = $validatedAddress['address'];
        }

        if (isset($validatedAddress['building_name'])) {
            $address->building_name = $validatedAddress['building_name'];
        }

        $address->save();

        return redirect('/');
    }

    public function profile(Request $request)
    {
        $user = auth()->user();

        $address = Address::where('user_id', $user->id)->first();

        $order = Order::where('user_id', $user->id)->latest()->first();

        $page = $request->query('page');

        if ($page === 'sell') {
            $items = Item::where('user_id', $user->id)->get();
        } elseif ($page === 'buy') {
            $orders = Order::where('user_id', $user->id)->get();

            $items = Item::whereIn('id', $orders->pluck('item_id'))->get();
        } elseif ($page === 'transaction') {
            $orders = Order::where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                ->orWhereHas('item', function ($q2) use ($user) {
                    $q2->where('user_id', $user->id);
                });
            })
            ->get()
            ->filter(function ($order) use ($user) {
                return !Evaluation::where('order_id', $order->id)
                ->where('user_id', $user->id)
                ->exists();
            });

            $itemIds = $orders->pluck('item_id')->unique();
            $items = Item::whereIn('id', $itemIds)->get();
            if ($page === 'transaction') {
                $items = $items->map(function ($item) {
                    $latestChat = $item->chats()->latest('created_at')->first();
                    $item->latest_chat_time = optional($latestChat)->created_at;
                    return $item;
                })->sortByDesc('latest_chat_time')->values();
            }
        } else {
            $items = Item::where('user_id', '!=', Auth::id())->get();
        }

        $averageRating = Evaluation::selectRaw('AVG(rating) as avg_rating')
        ->join('orders', 'evaluations.order_id', '=', 'orders.id')
        ->join('items', 'orders.item_id', '=', 'items.id')
        ->where('items.user_id', $user->id)
        ->value('avg_rating');

        $averageRating = Evaluation::where('target_user_id', $user->id)
        ->avg('rating');

        $averageRating = is_null($averageRating) ? 0.0 : (float) $averageRating;

        $fullStars = (int) floor($averageRating);
        $hasHalf = ($averageRating - $fullStars) >= 0.5;

        $lastAccessTime = session('last_access_time', now()->subMinutes(10));

        $relatedOrders = Order::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
            ->orWhereHas('item', function ($q2) use ($user) {
                $q2->where('user_id', $user->id);
            });
        })->get();

        $unreadMessageCount = 0;

        foreach ($relatedOrders as $order) {
            $item = $order->item;
            if ($item) {
                $chats = $item->chats()
                ->where('created_at', '>', $lastAccessTime)
                ->where('user_id', '!=', $user->id)
                ->count();

                $unreadMessageCount += $chats;
            }
        }

        session(['last_access_time' => now()]);

        $unreadCounts = [];

        foreach ($relatedOrders as $order) {
            $item = $order->item;
            if ($item) {
                $lastRead = ChatRead::where('user_id', $user->id)
                ->where('item_id', $item->id)
                ->value('last_read_at');

                $unreadCount = $item->chats()
                ->where('user_id', '!=', $user->id)
                ->when($lastRead, function ($q) use ($lastRead) {
                    $q->where('created_at', '>', $lastRead);
                })
                ->count();

                $unreadCounts[$item->id] = $unreadCount;
            }
        }

        $totalUnreadCount = array_sum($unreadCounts);

        return view('profile', compact('user', 'address', 'items','averageRating','fullStars','hasHalf','unreadMessageCount','order','unreadCounts','totalUnreadCount'));
    }
}