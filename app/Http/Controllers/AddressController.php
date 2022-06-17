<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAddressRequest;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function createAddress(StoreAddressRequest $request){

        $address = Address::factory()->create([
            'address' => $request->address,
            'receiver_name' => $request->receiver_name,
            'postal_code' => $request->postal_code,
            'user_id' => Auth::user()->id,
        ]);

        session()->forget('error');

        $addressEncoded = json_encode($address);

        return "<option id='$address->id' value='$address->id' data-address='$addressEncoded' name='$address->id'>$address->address</option>";
    }

    public function deleteAddress(Request $request){
        $address = Address::query()->find($request->address);

        $address->delete();
    }
}
