<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;

use function GuzzleHttp\Promise\all;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        return view('admin.auth.login');
    }

    public function loginSubmit(LoginRequest $request)
    {
        $this->logout($request);

        $request->authenticate('admin');

        return redirect()->route('admin.property.list');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
