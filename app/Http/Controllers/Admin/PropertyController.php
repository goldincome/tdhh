<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\RejectionReason;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Property\PropertyStatus;
use App\Repositories\Properties\PropertyRepository;

class PropertyController extends Controller
{
    protected $propertyRepo;

    public function __construct(
        PropertyRepository $propertyRepo,
    ) {
        $this->propertyRepo = $propertyRepo;
    }

    public function list(){
        $query =  $this->propertyRepo->orderBy('id', 'desc');
        if (request()->customer) {
            $query->where('user_id', request()->customer);
        }
        if (request()->partner) {
            $query->where('listed_by', request()->partner);
        }
        $properties = $query->paginate( request()->per_page ?? $this::DEFAULT_PER_PAGE);
        return view('admin.account.property.list', compact('properties'));
    }

    public function show(Request $request){
        $property =  $this->propertyRepo->find($request->id)->firstOrFail();
        return view('admin.account.property.details', compact('property'));
    }

    public function disableProperty(Request $request){
        try {
            $property =  $this->propertyRepo->findOrFail($request->propertyId);
            if($property->status_id == PropertyStatus::SUSPENDED_BY_ADMIN){
                $property->status_id = PropertyStatus::AVAILABLE;
                $property->save();

                $this->flashSuccessMessage('Property enabled');
                return back();
            }else{
                    DB::beginTransaction();
                    RejectionReason::create([
                        'model_name' => RejectionReason::MODEL_PARTNER,
                        'model_id' => $property->id,
                        'reason' => $request->reason
                    ]);
                    $property->status_id = PropertyStatus::SUSPENDED_BY_ADMIN;
                    $property->save();
                    DB::commit();
                    // send mail
                    $this->flashSuccessMessage('Property disabled');
                    return back();
                }
        }
        catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

}
