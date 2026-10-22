<?php

namespace App\Http\Controllers\Front;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Repositories\Countries\CountryRepositoryInterface;

class FrontUserAccountController extends Controller
{

    protected $countryRepo;

    public function __construct(CountryRepositoryInterface $countryRepo)
    {
        $this->countryRepo = $countryRepo;
        $this->middleware('auth'); 
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('account.index');
    }

    public function profile()
    {
        return view('account.index');
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $countries = $this->countryRepo->all();
        return view('account.profile.edit',compact('countries'));
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            //'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:255',
            'country_id' => ['required', 'exists:countries,id']
        ]);
        //dd($request->all(), $user);
        $updated = $user->update($request->except('_token'));
        if($updated){
            return redirect()->route('front.account.profile.edit')->with('success', 'Successfully Updated');
        }
    }

    public function settings()
    {
        return view('account.settings.index');
    }


    public function passwordForm()
    {
        return view('account.settings.change-password');
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            '_token' => 'required',
            'old_password' => 'required',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        $check = Hash::check($request->old_password, auth()->user()->password);
        if(!$check){
            return redirect()->back()->with('error','Old password does not matched!');
        }
        if(Hash::check($request->password , auth()->user()->password)) {
            return redirect()->back()->with('error','New password cannot be the same with the old password!');
        }

        $user = User::find(auth()->id());
        $user->update([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ]);

        //event(new PasswordReset($user));

        Auth::login($user);
        return redirect()->back()->with('success', 'Password Changed Successful');
    }

    public function deleteAccount()
    {
        return view('account.settings.delete-account');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if($user->id == auth()->user()->id)
        {
            $user->delete();
            return redirect()->route('register')->with('success', 'Your record has been delete');
        }
        return redirect()->back()->with('error', 'No record found');
    }
}
