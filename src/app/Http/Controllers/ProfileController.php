<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;
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

        $page = $request->query('page');

        if ($page === 'sell') {
            $items = Item::where('user_id', $user->id)->get();
        } elseif ($page === 'buy') {
            $orders = Order::where('user_id', $user->id)->get();

            $items = Item::whereIn('id', $orders->pluck('item_id'))->get();
        } else {
            $items = Item::where('user_id', '!=', Auth::id())->get();
        }
        return view('profile', compact('user', 'address', 'items'));
    }
}
