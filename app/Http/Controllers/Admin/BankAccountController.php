<?php

namespace App\Http\Controllers\Admin;

use App\Models\Country;
use App\Models\Currency2;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBankAccountRequest;

class BankAccountController extends Controller
{
    public function index()
    {
        $accounts = BankAccount::all();
        return view('admin.account.bank-account.list', compact('accounts'));
    }

    public function edit(Request $request)
    {
        $setting = BankAccount::find($request->settingId);
        return view('admin.account.bank-account.edit', compact('setting'));
    }

    public function create(Request $request)
    {
        $countries = Country::whereIn('id', [Country::NIGERIA, Country::UNITED_KINGDOM, Country::UNITED_STATE])->get();
        $currencies = Currency2::get();
        return view('admin.account.bank-account.create', compact('countries', 'currencies'));
    }

    public function store(CreateBankAccountRequest $request)
    {
        $data = $request->validated();
        BankAccount::create($data);
        $this->flashSuccessMessage('Bank Account Created');
        return redirect()->route('admin.bank-account.list');
    }

    public function update(Request $request)
    {
        BankAccount::where('id', $request->settingId)->update([
            'name' => $request->name,
            'value' => $request->value,
            'description' => $request->description,
        ]);
        $this->flashSuccessMessage('Setting Updated');
        return redirect()->route('admin.bank-account.list');
    }
}
