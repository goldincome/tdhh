<div class="col-sm-6">

    <form action="{{ route('bank-transfer.index') }}">
        
    <input type="hidden" name="agent_id" value="{{$agent->first()->id}}">
        <input type="hidden" class="address_id" name="billing_address" value="{{$addressIds['deliveryAddressId']}}">
        <input type="hidden" class="rate" name="rate" value="">
        <input type="hidden" name="shipment_obj_id" value="{{ $shipment_object_id }}">
        <button type="submit">
            <div>
                <span class="fa fa-building"></span>
            </div>
            <div>
                <div>
                    <span class="fa fa-angle-right"></span>
                </div>
                Pay with
                <br>
                {{ ucwords($payment['name']) }}
            </div>
        </button>
    </form>

</div>