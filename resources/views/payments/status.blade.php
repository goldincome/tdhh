@extends('layouts.app')

@section('title')
    Payment - Checkout
@endsection

@section('content')
    
    <div class="payment-status">
        <div class="page-alert page-alert-success">
            <span class="fa fa-check-circle"></span>
            <div style="margin-top: 10px;">
                Payment Successful
            </div>
            <div>
                Your payment was successful and
                your order is being processed.
                <br>
                Thank you, {{ ucwords(Auth::guard('web')->user()->first_name )}}.
                <br>
            Order Number <code style="font-weight: bold;">{{$order->reference}}</code>
            </div>
        </div>
        
  
        <div class="payment-status-track">
        @if($products[0]->is_africana != 1)
            @if($products[0]->isAvailableInNigeria())
                <div class="row track-summary-2 track-summary-2-mini">
                    <div class="col-sm-4 track-yes">
                        <div>
                            <div></div>
                            <div></div>
                        </div>
                        <div>
                            <div>
                                <span class="fa fa-check"></span>
                            </div>
                            <div>
                                <div>
                                    Product Purchase
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="col-sm-4 track-current">
                        <div>
                            <div></div>
                            <div></div>
                        </div>
                        <div>
                            <div>
                                <span class="fa fa-spin fa-spinner"></span>
                            </div>
                            <div>
                                <div>
                                    Delivered to Freeki Depo (UK)
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    {{-- <div class="col-sm-4 track-no">
                        <div>
                            <div></div>
                            <div class="d-none"></div>
                        </div>
                        <div>
                            <div>
                                <span class="fa fa-ellipsis-h"></span>
                            </div>
                            <div>
                                <div>
                                        Order on International Transit
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    {{-- <div class="col-sm-4 track-no">
                        <div>
                            <div class="invisible"></div>
                            <div></div>
                        </div>
                        <div>
                            <div>
                                <span class="fa fa-ellipsis-h"></span>
                            </div>
                            <div>
                                <div>
                                        Package delivered to Freeki (NIG.) Depo
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-sm-4 track-no">
                        <div>
                            <div></div>
                            <div></div>
                        </div>
                        <div>
                            <div>
                                <span class="fa fa-ellipsis-h"></span>
                            </div>
                            <div>
                                <div>
                                        Delivering
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 track-no">
                        <div>
                            <div></div>
                            <div></div>
                        </div>
                        <div>
                            <div>
                                <span class="fa fa-ellipsis-h"></span>
                            </div>
                            <div>
                                <div>
                                        Order received by Customer
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
            @else
                    <div class="row track-summary-2 track-summary-2-mini">
                    <div class="col-sm-4 track-yes">
                        <div>
                            <div></div>
                            <div></div>
                        </div>
                        <div>
                            <div>
                                <span class="fa fa-check"></span>
                            </div>
                            <div>
                                <div>
                                    Product Purchase
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4 track-current">
                        <div>
                            <div></div>
                            <div></div>
                        </div>
                        <div>
                            <div>
                                <span class="fa fa-spin fa-spinner"></span>
                            </div>
                            <div>
                                <div>
                                    Delivered to Freeki Depo (UK)
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4 track-no">
                        <div>
                            <div></div>
                            <div class="d-none"></div>
                        </div>
                        <div>
                            <div>
                                <span class="fa fa-ellipsis-h"></span>
                            </div>
                            <div>
                                <div>
                                        Order on International Transit
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4 track-no">
                        <div>
                            <div class="invisible"></div>
                            <div></div>
                        </div>
                        <div>
                            <div>
                                <span class="fa fa-ellipsis-h"></span>
                            </div>
                            <div>
                                <div>
                                        Package delivered to Freeki (NIG.) Depo
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 track-no">
                        <div>
                            <div></div>
                            <div></div>
                        </div>
                        <div>
                            <div>
                                <span class="fa fa-ellipsis-h"></span>
                            </div>
                            <div>
                                <div>
                                        Package delivered to "Pickup Agent" Awaiting Customer Pick-UP
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 track-no">
                        <div>
                            <div></div>
                            <div></div>
                        </div>
                        <div>
                            <div>
                                <span class="fa fa-ellipsis-h"></span>
                            </div>
                            <div>
                                <div>
                                        Order received by Customer
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
            @endif
        @else


            {{-- FOR AFRICANA --}}
            <div class="row track-summary-2 track-summary-2-mini">
                <div class="col-sm-2 track-yes">
                    <div>
                        <div></div>
                        <div></div>
                    </div>
                    <div>
                        <div>
                            <span class="fa fa-check"></span>
                        </div>
                        <div>
                            <div>
                                Order Received by Seller
                                <br/>
                                (Seller Processing Order)
                            </div>
                            <div>
                               {{now()}}
                            </div>
                        </div>
                    </div>
                </div> 
                <div class="col-sm-2 track-current">
                    <div>
                        <div></div>
                        <div></div>
                    </div>
                    <div>
                        <div>
                            <span class="fa fa-spin  fa-spinner"></span>
                        </div>
                        <div>
                            <div>
                                Order Delivered to Freeki Depo (Supplier)
                            </div>
                            <div>
                                    ---
                            </div>
                        </div>
                    </div>
                </div> 
                <div class="col-sm-2 track-no">
                    <div>
                        <div></div>
                        <div></div>
                    </div>
                    <div>
                        <div>
                            <span class="fa  fa-ellipsis-h"></span>
                        </div>
                        <div>
                            <div>
                                Order Packaged in Transit to Freeki Depo (Customer)
                            </div>
                            <div>
                                -
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2 track-no">
                    <div>
                        <div></div>
                        <div></div>
                    </div>
                    <div>
                        <div>
                            <span class="fa fa-ellipsis-h"></span>
                        </div>
                        <div>
                            <div>
                                Order Delivered To Freeki Depo (Customer)
                            </div>
                            <div>
                                    ---  
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2 track-no">
                    <div>
                        <div></div>
                        <div></div>
                    </div>
                    <div>
                        <div>
                            <span class="fa fa-ellipsis-h"></span>
                        </div>
                        <div>
                            <div>
                                @if($order->agent_id == null)
                                Order in forward Postage to Customer 
                                @else Awaiting Customer pick-up
                                @endif
                            </div>
                            <div>
                                    ---  
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2 track-no">
                    <div>
                        <div></div>
                        <div></div>
                    </div>
                    <div>
                        <div>
                            <span class="fa fa-ellipsis-h"></span>
                        </div>
                        <div>
                            <div>
                                Order Received by customer
                            </div>
                            <div>
                                    ---  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif



        </div>
        

        <div class="payment-status-orders">
            <div class="caption">
                Your Order
            </div>
            <table class="table table-products table-tr-lines wishlist-table">
                <tbody>
                    @foreach ($products as $product)
                    {{-- {{dd($product->cover)}} --}}
                         <tr>
                        <td>
                            <a href="" class="table-products-img" style="height: 70px;">
                                    <img src="{{asset('uploads'.'/'.$product->cover)}}" onerror="{{ asset('img/users/dp.png') }}" alt="" class="dp-contain" />

                                {{-- <img src="{{ asset('front/img/app/samples/boxes.jpg') }}" alt="" class="dp-contain" /> --}}
                            </a>
                            <div>
                                <div class="table-products-text-main">
                                    <a href="" style="font-size: 15px;">
                                        {{$product->name}}
                                    </a>
                                </div>
                                <div class="product-rating">
                                    Qty: {{$product->pivot->quantity}}
                                </div>
                                <div class="table-products-text-sub text-small ellipsis-line-2">
                                    @if ($product->pivot->shipping_method == 'bysea')
                                    Shipping:  by Sea 
                                        @if(isset(($product->pivot->shipment_type)))
                                            ({{ $product->pivot->shipment_type}} )
                                        @endif
                                    @elseif ($product->pivot->shipping_method == 'byair')
                                    Shipping: by Air  
                                    @endif
                                    {{-- the shipping options I chose, postage option, delivery port, etc. --}}
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="payment-status-orders-info text-center">
                To track your order, please visit
                <br>
                <span class="font-bold">
                    My Account > Order History > View Details > Track Order
                </span>
                <br>
                <a href="{{ route('accounts', ['tab' => 'orders']) }}" class="btn btn-orange btn-block btn-md">View My Orders</a>
                <a href="{{url('')}}" class="btn btn-transparent-black btn-block btn-md" style="margin-left: 0px !important;">Goto Homepage</a>
            </div>
        </div>

    </div>

@endsection