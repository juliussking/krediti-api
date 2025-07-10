<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientAddressController extends Controller
{
    public function update($id, Request $request) {

        $client = Client::findOrFail($id);

        $client->address->update($request->all());

        return $client;
    }
}
