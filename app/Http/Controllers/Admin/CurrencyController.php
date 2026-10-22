<?php

namespace App\Http\Controllers\Admin;

use App\Models\Currency2;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Currency;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency2::all();
        return view('admin.account.currency.list', compact('currencies'));
    }

    public function edit(Request $request)
    {
        $currency = Currency2::find($request->currencyId);
        return view('admin.account.currency.edit', compact('currency'));
    }

    public function update(Request $request)
    {
        $currency = Currency2::find($request->currencyId);
        $currency->exchange_rate = $request->exchange_rate;
        $currency->save();
        $this->flashSuccessMessage('currency update successful');
        return redirect()->route('admin.currency.list');
    }

}
