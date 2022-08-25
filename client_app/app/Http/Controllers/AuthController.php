<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use \InvalidArgumentException;

class AuthController extends Controller
{
    public function redirect(Request $request)
    {
        session()->put('state', $state = Str::random(40));

        session()->put(
            'code_verifier',
            $code_verifier = Str::random(128)
        );

        $codeChallenge = strtr(rtrim(
            base64_encode(hash('sha256', $code_verifier, true)),
            '='
        ), '+/', '-_');

        // dd(session('state'));

        $query = http_build_query([
            'client_id' => config('lanuma.client_id'),
            'redirect_uri' => 'http://localhost:8000/callback',
            'response_type' => 'code',
            'scope' => '',
            'state' => $state,
            'code_challenge' => $codeChallenge,
            'code_challenge_method' => 'S256',
        ]);

        return redirect(config('lanuma.passport_url') . '/oauth/authorize?' . $query);
    }

    public function callback(Request $request)
    {
        $err = $request->query('error') ?? null;

        $state = session()->pull('state');

        $codeVerifier = session()->pull('code_verifier');

        if($err) {
            return response()->json([
                'error' => $err,
                'error_message' => 'You\'re reject authorization request.',
                'state' => $state
            ]);
        }

        throw_unless(
            strlen($state) > 0 && $state === $request->state,
            InvalidArgumentException::class
        );

        $response = Http::asForm()->post(config('lanuma.passport_url') . '/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => config('lanuma.client_id'),
            'redirect_uri' => 'http://localhost:8000/callback',
            'code_verifier' => $codeVerifier,
            'code' => $request->code,
        ]);

        return $response->json();
    }
}
