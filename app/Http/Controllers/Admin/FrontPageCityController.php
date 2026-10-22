<?php

namespace App\Http\Controllers\Admin;

use App\Models\State;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\FrontPageCity;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class FrontPageCityController extends Controller
{
    public function index()
    {
        $cities = FrontPageCity::all();
        return view('admin.account.front-page-city.list', compact('cities'));
    }

    public function states()
    {
        $states = State::whereIn('country_id', [Country::NIGERIA,  Country::UNITED_KINGDOM])->get();
        return view('admin.account.front-page-city.state-list', compact('states'));
    }

    public function edit(Request $request)
    {
        $fpc = FrontPageCity::findOrFail($request->fpcId);
        return view('admin.account.front-page-city.edit', compact('fpc'));
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $fpc = FrontPageCity::findOrFail($request->fpcId);

            if ($request->has('cover_image')) {
                $fpc
                    ->addMediaFromRequest('cover_image')
                    ->toMediaCollection('fpc_image');
            }

            $fpc->status = $request->status;
            $fpc->save();
            DB::commit();

            return redirect()->route('admin.fpc.list');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }


        return view('admin.account.front-page-city.edit', compact('fpc'));
    }

    public function addToFPC(Request $request)
    {
        FrontPageCity::updateOrCreate(
            ['state_id' => $request->stateId],
            ['status' => true],
        );
        $this->flashSuccessMessage('City Added to Front Page Cities');
        return redirect()->route('admin.fpc.list');
    }
}
