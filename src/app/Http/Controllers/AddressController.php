<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;

class AddressController extends Controller
{
    public function change()
    {
        $user = auth()->user();
        return view('change', compact('user'));
    }

    public function update(Request $request)
    {
        $address = Address::where('user_id', auth()->id())->first();

        $address->postal_code = $request->input('postal_code');
        $address->address = $request->input('address');
        $address->building_name = $request->input('building_name');

        $success = $address->save();

        return redirect('/');
    }
}
