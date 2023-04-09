<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;

use App\Models\User;

class AuthenticationController extends Controller
{
    public function token(Request $request)
    {
        $request->validate([
            'library_id' => ['required'],
            'password' => 'required',
        ]);
     
        $user = User::where('library_id', $request->library_id)->first();
     
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw new AuthenticationException;
        }
     
        return $user->createToken($user->name)->plainTextToken;
    }

    public function revokeToken(Request $request)
    {
        $user = $request->user();

        // Clear login token
        $user->currentAccessToken()->delete();
        
        // Clear push token
        $user->push_token = null;
        $user->save();

        return response()->noContent();
    }

    public function updatePushToken(Request $request)
    {
        $request->validate(['push_token' => 'required']);

        $user = $request->user();

        $user->push_token = $request->input('push_token');
        $user->save();

        return response()->noContent(Response::HTTP_CREATED);
    }

    public function clearPushToken(Request $request)
    {
        $user = $request->user();

        $user->push_token = null;
        $user->save();

        return response()->noContent();
    }
}
