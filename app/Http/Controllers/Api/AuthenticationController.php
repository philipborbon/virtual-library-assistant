<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
     
        return $user->createToken($user->name)->plainTextToken;
    }
}
