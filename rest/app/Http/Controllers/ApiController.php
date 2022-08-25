<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;

class ApiController extends Controller
{
    public function index()
    {
        $clients = Client::get();

        return view('pages.api.list', compact('clients'));
    }

    public function createAuthCodeClient(ClientRepository $client, Request $request)
    {
        $isPublic = true;
        $userid = $request->userId ?? null;
        $userName = $request->userName;
        $redirectUrl = $request->redirectUrl ?? '';

        if($redirectUrl !== '') {
            $isPublic = false;
        }

        $cl = $client->create($userid, $userName, $redirectUrl, null, false, false, $isPublic);
        $access = [
            'client_id' => $cl->id ?? null,
            'name' => $cl->name,
            'client_secret' => $cl->secret
        ];
        return redirect()->route('api.list')->with('client', $access);
    }
}
