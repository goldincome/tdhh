@extends('layouts.admin')


@section('title')
    1234567890 - Wallet Funding
@endsection


@section('css')
    <style>
        .admin-sidebar-wallet{
            /* color: #000 !important; */
            background: #555 !important;
        }
    </style>
@endsection

@section('js')
    <script>
        function printDivContent() {
            var contentOfDiv = document.getElementById("divCon").innerHTML;
            var newWin = window.open('', '', 'height=650, width=650');
            newWin.document.write('');
            newWin.document.write('<title>Print Wallet Reciept</title>');
            newWin.document.write(contentOfDiv);
            newWin.document.write('');
            newWin.document.close();
            newWin.print();
        }
        function PrintElem(elem)
        {
            var mywindow = window.open('', 'PRINT', 'height=400,width=600');

            mywindow.document.write('<html><head><title>' + document.title  + '</title>');
            mywindow.document.write('</head><body >');
            mywindow.document.write('<h1>' + document.title  + '</h1>');
            mywindow.document.write(document.getElementById(elem).innerHTML);
            mywindow.document.write('</body></html>');

            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/

            mywindow.print();
            mywindow.close();

            return true;
        }
    </script>
@endsection

@section('content')
    <div class="container" id="divCon">


        <div class="page-title-account">
            <div>
                Wallet funding details
                <span class="font-montserrat text-sm text-tdhh-orange">
                    #{{$funding->reference}}
                </span>
            </div>
            <div class="pull-right flex justify-end space-x-2">
                <a href="{{ url()->previous() }}" class="btn btn-transparent-black btn-sm relative top-2">
                    <span class="fa fa-angle-left mr-2"></span>
                    Go back
                </a>
            </div>
        </div>


        <div class="box">

            <form to="" class="w-full flex justify-center space-x-4">
                @if($funding->wallet_funding_status_id ==  $funding->wallet_funding_status::SUBMITTED)
                    <button type="submit" class="btn text-white bg-green-500">
                        <span class="fa fa-check mr-2"></span>
                        Approve
                    </button>
                @endif
                @if($funding->wallet_funding_status_id ==  $funding->wallet_funding_status::SUBMITTED || $funding->wallet_funding_status_id ==  $funding->wallet_funding_status::PROCESSING)
                    <button type="submit" class="btn text-white bg-red-500">
                        <span class="fa fa-times mr-2"></span>
                        Reject
                    </button>
                @endif
            </form>

            <div class="px-6 py-4 mt-8 bg-gray-100">
                Deposit Information
            </div>

            <div class="mt-6">
                <div class="w-full flex space-x-2">
                    <div class="w-1/3 flex">
                        <div class="w-8 flex-shrink-0">
                            <span class="fas fa-image mt-1"></span>
                        </div>
                        <div>
                            <div>
                                Payment Evidence
                            </div>
                            @if(!$funding->is_refund)
                                <a href="{{asset('img/bgs/manchester.jpg')}}" target="_blank" class="block w-48 h-32 mt-2 bg-black rounded-md">
                                    <img src={{asset($funding->teller_image->getUrl())?? ''}} alt="payment" class="object-contain m-auto w-full h-full">
                                </a>
                            @endif
                            @if($funding->is_refund)
                                Refund from cancelled reservation
                            @endif
                        </div>
                    </div>
                    <div class="w-2/3 grid grid-cols-2 gap-6">
                        <div class="flex">
                            <div class="w-8 flex-shrink-0">
                                <span class="fas fa-user mt-1"></span>
                            </div>
                            <div>
                                <div>
                                    Customer
                                </div>
                                <div class="font-bold">
                                    {{$funding->user->full_name}}
                                </div>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="w-8 flex-shrink-0">
                                <span class="fas fa-hashtag mt-1"></span>
                            </div>
                            <div>
                                <div>
                                    Payment reference
                                </div>
                                <div class="font-bold">
                                    #{{$funding->reference}}
                                </div>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="w-8 flex-shrink-0">
                                <span class="fas fa-money-bill-alt mt-1"></span>
                            </div>
                            <div>
                                <div>
                                    Amount Deposited
                                </div>
                                <div class="font-bold">
                                    {{currency_format($funding->amount_paid, $funding->currency_code)}}
                                </div>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="w-8 flex-shrink-0">
                                <span class="fas fa-money-bill mt-1"></span>
                            </div>
                            <div>
                                <div>
                                    Converted to
                                </div>
                                <div class="font-bold">
                                    {{currency_format($funding->converted_amount, $funding->converted_currency_code)}}
                                </div>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="w-8 flex-shrink-0">
                                <span class="fas fa-user mt-1"></span>
                            </div>
                            <div>
                                <div>
                                    Depositor name
                                </div>
                                <div class="font-bold">
                                    {{$funding->depositor_name}}
                                </div>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="w-8 flex-shrink-0">
                                <span class="fas fa-calendar mt-1"></span>
                            </div>
                            <div>
                                <div>
                                    Deposit date
                                </div>
                                <div class="font-bold">
                                    {{$funding->date_paid ? $funding->date_paid->format('D d M Y') : ''}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-8 mt-8 border-t overflow-auto">

                <div class="px-6 py-4 mb-8 bg-gray-100">
                    Account Paid Into
                </div>


                <div class="grid grid-cols-2 gap-6">
                    
                    <div class="flex">
                        <div class="w-8 flex-shrink-0">
                            <span class="fas fa-bank mt-1"></span>
                        </div>
                        <div>
                            <div>
                                Bank Name
                            </div>
                            <div class="font-bold">
                                {{ucwords($funding->bank_name) ?? '' }}
                            </div>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="w-8 flex-shrink-0">
                            <span class="fas fa-money-bill mt-1"></span>
                        </div>
                        <div>
                            <div>
                                Currency
                            </div>
                            <div class="font-bold">
                                {{currency()->getCurrency($funding->currency_code)['symbol']}} ({{$funding->currency_code}})
                            </div>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="w-8 flex-shrink-0">
                            <span class="fas fa-briefcase mt-1"></span>
                        </div>
                        <div>
                            <div>
                                Account Name
                            </div>
                            <div class="font-bold">
                                {{$funding->bankAccount->account_name ?? '' }}
                            </div>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="w-8 flex-shrink-0">
                            <span class="fas fa-briefcase mt-1"></span>
                        </div>
                        <div>
                            <div>
                                Account Number
                            </div>
                            <div class="font-bold">
                                {{$funding->account_number ?? ''}}
                            </div>
                        </div>
                    </div>
                </div>

                @if(isset($funding->other_details->exchange_rate))
                    <br/>
                    <div class="px-6 py-4 my-8 bg-gray-100">
                        Exchange Rate as at date you paid in.
                    </div>
                
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-2">
                        @foreach($funding->other_details->exchange_rate as $currency)
                            @if($currency->code !== 'NGN')
                                <div class="p-4 box">
                                    <div>
                                        {{$currency->name}}
                                    </div>
                                    <div class="text-2xl font-bold">
                                        {{currency()->getCurrency('NGN')['symbol']}}{{$currency->exchange_rate}} = 1{{$currency->symbol}}
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
                <br/>

                <div class="px-6 py-4 my-8 bg-gray-100">
                    Other Information
                </div>


                <div class="grid grid-cols-2 gap-6">
                    <div class="flex">
                        <div class="w-8 flex-shrink-0">
                            <span class="fas fa-circle mt-1"></span>
                        </div>
                        <div>
                            <div>
                                Status
                            </div>
                            <div class="font-bold">
                                @if($funding->wallet_funding_status_id == $funding->wallet_funding_status::PROCESSING)
                                    <div class="label" style="color: white; background: {{$funding->wallet_funding_status->color}}">
                                        Awaiting <br> Final Approval
                                    </div>
                                @endif
                                @if($funding->wallet_funding_status_id == $funding->wallet_funding_status::SUBMITTED)
                                    <div class="label" style="color: white; background: {{$funding->wallet_funding_status->color}}">
                                        Awaiting <br> Approval
                                    </div>
                                @endif
                                @if($funding->wallet_funding_status_id == $funding->wallet_funding_status::REFUND_REQUESTED)
                                    <div class="label" style="color: white; background: {{$funding->wallet_funding_status->color}}">
                                        Refund <br> Requested
                                    </div>
                                @endif
                                @if($funding->wallet_funding_status_id == $funding->wallet_funding_status::REFUNDED)
                                    <div class="label" style="color: white; background: {{$funding->wallet_funding_status->color}}">
                                        Refund <br> Approved
                                    </div>
                                @endif
                                @if($funding->wallet_funding_status_id == $funding->wallet_funding_status::APPROVED)
                                    <div class="label" style="color: white; background: {{$funding->wallet_funding_status->color}}">
                                        Approved
                                    </div>
                                @endif
                                @if($funding->wallet_funding_status_id == $funding->wallet_funding_status::REJECTED)
                                    <div class="label" style="color: white; background: {{$funding->wallet_funding_status->color}}">
                                        Rejected
                                    </div>
                                @endif
                                @if($funding->wallet_funding_status_id == $funding->wallet_funding_status::CANCELLED)
                                    <div class="label" style="color: white; background: {{$funding->wallet_funding_status->color}}">
                                        Cancelled
                                    </div>
                                @endif
                                {{-- 
                                <div class="label label-green">
                                    Approved
                                </div>
                                <div class="label label-red">
                                    Rejected
                                </div>
                                 --}}
                            </div>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="w-8 flex-shrink-0">
                            <span class="fas fa-calendar mt-1"></span>
                        </div>
                        <div>
                            <div>
                                Date submitted
                            </div>
                            <div class="font-bold">
                                {{$funding->created_at->format('D d M Y')}}
                            </div>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="w-8 flex-shrink-0">
                            <span class="fas fa-user mt-1"></span>
                        </div>
                        <div>
                            <div>
                                Customer
                            </div>
                            <div class="font-bold">
                                {{$funding->user->full_name}}
                            </div>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="w-8 flex-shrink-0">
                            <span class="fas fa-hashtag mt-1"></span>
                        </div>
                        <div>
                            <div>
                                Payment reference
                            </div>
                            <div class="font-bold">
                                #{{$funding->reference}}
                            </div>
                        </div>
                    </div>
                    <div class="flex col-span-2">
                        <div class="w-8 flex-shrink-0">
                            <span class="fas fa-times-circle mt-1"></span>
                        </div>
                        <div>
                            <div class="font-bold">
                                Rejection Reason
                            </div>
                            <div>
                                {{$funding->other_details->rejected_approval_reason ?? ''}}
                                <button style="background-color: skyblue;" onclick="PrintElem('divCon')">Print</button>
                            </div>
                        </div>
                    </div>
                    
                </div>


            </div>


        </div>

    </div>
@endsection

