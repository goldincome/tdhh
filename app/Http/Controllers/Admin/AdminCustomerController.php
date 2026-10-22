<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\RejectionReason;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AdminCustomerController extends Controller
{
    public function index(){
        $customers = User::latest()->paginate(20);
        return view('admin.account.customers.list', compact('customers'));
    }

    public function show(Request $request)
    {
        $customer = User::findOrFail($request->id);
        return view('admin.account.customers.details', compact('customer'));
    }

    public function enableDisable(Request $request)
    {
        try {
            $customer = User::findOrFail($request->id);
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
                $customer->status_id= User::STATUS_DISABLED;
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
