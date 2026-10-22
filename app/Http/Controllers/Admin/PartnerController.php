<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::latest()->paginate(20);
        return view('admin.account.partners.list', compact('partners'));
    }

    public function show(Request $request)
    {
        $partner = Partner::findOrFail($request->id);
        return view('admin.account.partners.details', compact('partner'));
    }

    public function enableDisable(Request $request)
    {
        try {
            $customer = Partner::findOrFail($request->id);
            if ($customer->status_id == User::STATUS_DISABLED) {
                $customer->status_id = User::STATUS_ACTIVE;
                $customer->save();
                $this->flashSuccessMessage('User enabled');
                return back();
            } else {
                DB::beginTransaction();
                RejectionReason::create([
                    'model_name' => RejectionReason::MODEL_CUSTOMER,
                    'model_id' => $customer->id,
                    'reason' => $request->reason
                ]);
                $customer->status_id = User::STATUS_DISABLED;
                $customer->save();
                DB::commit();
                //TODO:: send mail
                $this->flashSuccessMessage('Customer disabled');
                return back();
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
