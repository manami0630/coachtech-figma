<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Item;
use App\Models\Address;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $address = Address::where('user_id', $user->id)->first();

        return view('edit', compact('user', 'address'));
    }

    public function update(AddressRequest $request)
    {
        $validated = $request->validated();

        $user = auth()->user();
        $user->name = $validated['name'];
        $user->save();

        $address = Address::firstOrNew(['user_id' => $user->id]);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('public/profile_images');
            $address->profile_image = str_replace('public/', '', $path);
        }

        if (isset($validated['postal_code'])) {
            $address->postal_code = $validated['postal_code'];
        }

        if (isset($validated['address'])) {
            $address->address = $validated['address'];
        }

        if (isset($validated['building_name'])) {
            $address->building_name = $validated['building_name'];
        }

        $address->save();
        $addresses = Address::all();

        return redirect('/');
    }

    public function profile(Request $request)
    {
        $user = auth()->user();

        $address = Address::where('user_id', $user->id)->first();

        $tab = $request->query('tab');

        if ($tab === 'sell') {
            $items = Item::where('user_id', $user->id)->get();
        } elseif ($tab === 'buy') {
            $orders = Order::where('user_id', $user->id)->get();

            $items = Item::whereIn('id', $orders->pluck('item_id'))->get();
        } else {
            $items = Item::where('user_id', '!=', Auth::id())->get();
        }
        return view('profile', compact('user', 'address', 'items'));
    }
}
