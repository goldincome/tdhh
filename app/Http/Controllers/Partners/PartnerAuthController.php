<?php

namespace App\Http\Controllers\Partners;

use App\Models\Country;
use App\Models\Partner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Partners\LoginRequest;
use App\Repositories\Countries\CountryRepository;
use App\Http\Requests\Partners\PartnerRegisterRequest;

class PartnerAuthController extends Controller
{
    private $countryRepo;

    public function __construct(
        CountryRepository $countryRepo,
        )
    {
        $this->countryRepo = $countryRepo;
    }

    public function register(Request $request){
        $countries = $this->countryRepo->all(['id', 'name']);
        return view('partners.auth.register', compact('countries'));
    }

    public function registerSubmit(PartnerRegisterRequest $request){
        $partner = Partner::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'country_id' => $request->country_id,
            'password' => Hash::make($request->password),
        ]);

        // event(new Registered($user));

        Auth::guard('partner')->login($partner);

        return redirect()->route('partner.register.success');
    }

    public function resisterSuccess(){
        return view('partners.auth.register-success');
    }

    public function login(Request $request){
        return view('partners.auth.login');
    }

    public function loginSubmit(LoginRequest $request){
        $request->authenticate('partner');

        $request->session()->regenerate();
        return redirect()->route('partner.dashboard');

    }

    public function logout(Request $request)
    {
        Auth::guard('partner')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('partner.login');
    }
}
