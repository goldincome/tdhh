@vite('resources/js/app.js')


@extends('layouts.app')


@section('title')
    My Wallet - Customer Name
@endsection



@section('content')
    <div>

        <div class="top-wide">
            <div>
                <div>
                    My Wallet
                </div>
                <div>
                    <a href="/">Home</a>
                    <span>/</span>
                    <a href="/account">Account</a>
                    <span>/</span>
                    <a>Wallet Funding</a>
                </div>
            </div>
            <div></div>
        </div>

        <div class="container py-8 sm:py-12 flex">

            <div class="w-3/12 flex-shrink-0 hidden lg:block">
                @include("common.account-sidebar", ["page" => "wallet-funding"])
            </div>

            <div class="w-9/12 flex-grow lg:pl-8 lg:border-l">
                @include('layouts.errors-and-messages')

                <div class="flex justify-between">
                    <div>
                        <a href="/account" class="lg:hidden btn btn-transparent-black btn-md">
                            <span class="fa fa-angle-left mr-2"></span>
                            Back
                        </a>
                    </div>
                    <a href="{{route('front.account.wallet-funding.create')}}" class="btn btn-tdhh-orange btn-md">
                        Add funds to wallet
                    </a>
                </div>

                <div class="mt-8 pt-8 border-t grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-2">
                    <div class="p-4 box">
                        <div>
                            Account Balance
                        </div>
                        <div class="text-2xl font-bold">
                            {{currency_format($currentBalance, config('currency.default'))}}
                        </div>
                    </div>
                    <div class="p-4 box">
                        <div>
                            Total Deposited
                        </div>
                        <div class="text-2xl font-bold">
                            {{currency_format($totalDeposited, config('currency.default'))}}
                        </div>
                    </div>
                    <div class="p-4 box">
                        <div>
                            Total Spent
                        </div>
                        <div class="text-2xl font-bold">
                            {{currency_format($totalSpent, config('currency.default'))}}
                        </div>
                    </div>
                </div>

                <div class="mt-8 table-responsive">
                    <table class="table table-auto table-border table-align">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Pay Info</th>
                                <th>Depositor Name</th>
                                <th>Amt Paid</th>
                                <th>Date Paid</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @forelse ($properties as $property) --}}
                                
                                @forelse($walletFundings as $key => $walletFunding)
                                    <div id="model-reject-funding-{{$walletFunding->id}}" tabindex="-1" aria-hidden="true" class="hidden modal h-modal">
                    
                                        <div class="modal-full max-w-2xl">
                                                <div class="modal-content">
                            
                                                    <div class="modal-header">
                                                        <div class="modal-header-text">
                                                            Reject Funding Deposit Confirmation
                                                        </div>
                                                        <button type="button" data-modal-toggle="model-reject-funding-{{$walletFunding->id}}" class="modal-header-close">
                                                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                                            <span class="sr-only">Close modal</span>
                                                        </button>
                                                    </div>
                            
                                                    <div class="modal-body">
                                                        <div class="box">

                                                            <form to="" class="w-full flex justify-center space-x-4">
                                                                @if($walletFunding->wallet_funding_status_id ==  $walletFunding->wallet_funding_status::SUBMITTED)
                                                                    <button type="submit" class="btn text-white bg-green-500">
                                                                        <span class="fa fa-check mr-2"></span>
                                                                        Approve
                                                                    </button>
                                                                @endif
                                                                @if($walletFunding->wallet_funding_status_id ==  $walletFunding->wallet_funding_status::SUBMITTED || $walletFunding->wallet_funding_status_id ==  $walletFunding->wallet_funding_status::PROCESSING)
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
                                                                            @if(!$walletFunding->is_refund)
                                                                                <a href="{{asset('img/bgs/manchester.jpg')}}" target="_blank" class="block w-48 h-32 mt-2 bg-black rounded-md">
                                                                                    <img src={{asset($walletFunding->teller_image->getUrl())?? ''}} alt="payment" class="object-contain m-auto w-full h-full">
                                                                                </a>
                                                                            @endif
                                                                            @if($walletFunding->is_refund)
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
                                                                                    <a href="{{route('admin.customer.show', $walletFunding->user_id)}}" target="_blank" class="hover:underline text-tdhh-orange">{{$walletFunding->user->full_name}}</a>
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
                                                                                    #{{$walletFunding->reference}}
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
                                                                                    {{currency_format($walletFunding->amount_paid, $walletFunding->currency_code)}}
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
                                                                                    {{currency_format($walletFunding->converted_amount, $walletFunding->converted_currency_code)}}
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
                                                                                    {{$walletFunding->depositor_name}}
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
                                                                                    {{$walletFunding->date_paid ? $walletFunding->date_paid->format('D d M Y') : ''}}
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
                                                                                {{ucwords($walletFunding->bank_name) ?? '' }}
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
                                                                                {{$walletFunding->currency_code}}
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
                                                                                {{$walletFunding->bankAccount->account_name ?? '' }}
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
                                                                                {{$walletFunding->account_number ?? ''}}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                
                                                
                                                                <div class="px-6 py-4 my-8 bg-gray-100">
                                                                    Approvals
                                                                </div>
                                                
                                                
                                                                <div class="grid grid-cols-2 gap-6">
                                                                    <div class="flex">
                                                                        <div class="w-8 flex-shrink-0">
                                                                            <span class="fas fa-user mt-1"></span>
                                                                        </div>
                                                                        <div>
                                                                            <div>
                                                                                First approval user
                                                                            </div>
                                                                            <div class="font-bold">
                                                                                {{$walletFunding->other_details->first_approval_by_name ?? ''}}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex">
                                                                        <div class="w-8 flex-shrink-0">
                                                                            <span class="fas fa-calendar mt-1"></span>
                                                                        </div>
                                                                        <div>
                                                                            <div>
                                                                                First approval date
                                                                            </div>
                                                                            <div class="font-bold">
                                                                                {{$walletFunding->other_details->first_approval_date ?? ''}}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex">
                                                                        <div class="w-8 flex-shrink-0">
                                                                            <span class="fas fa-user mt-1"></span>
                                                                        </div>
                                                                        <div>
                                                                            <div>
                                                                                Second approval user
                                                                            </div>
                                                                            <div class="font-bold">
                                                                                {{$walletFunding->other_details->final_approval_by_name ?? ''}}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex">
                                                                        <div class="w-8 flex-shrink-0">
                                                                            <span class="fas fa-calendar mt-1"></span>
                                                                        </div>
                                                                        <div>
                                                                            <div>
                                                                                Second approval date
                                                                            </div>
                                                                            <div class="font-bold">
                                                                                {{$walletFunding->other_details->final_approval_date ?? ''}}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                
                                                
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
                                                                                @if($walletFunding->wallet_funding_status_id == $walletFunding->wallet_funding_status::PROCESSING)
                                                                                    <div class="label" style="color: white; background: {{$walletFunding->wallet_funding_status->color}}">
                                                                                        Awaiting <br> Final Approval
                                                                                    </div>
                                                                                @endif
                                                                                @if($walletFunding->wallet_funding_status_id == $walletFunding->wallet_funding_status::SUBMITTED)
                                                                                    <div class="label" style="color: white; background: {{$walletFunding->wallet_funding_status->color}}">
                                                                                        Awaiting <br> Approval
                                                                                    </div>
                                                                                @endif
                                                                                @if($walletFunding->wallet_funding_status_id == $walletFunding->wallet_funding_status::REFUND_REQUESTED)
                                                                                    <div class="label" style="color: white; background: {{$walletFunding->wallet_funding_status->color}}">
                                                                                        Refund <br> Requested
                                                                                    </div>
                                                                                @endif
                                                                                @if($walletFunding->wallet_funding_status_id == $walletFunding->wallet_funding_status::REFUNDED)
                                                                                    <div class="label" style="color: white; background: {{$walletFunding->wallet_funding_status->color}}">
                                                                                        Refund <br> Approved
                                                                                    </div>
                                                                                @endif
                                                                                @if($walletFunding->wallet_funding_status_id == $walletFunding->wallet_funding_status::APPROVED)
                                                                                    <div class="label" style="color: white; background: {{$walletFunding->wallet_funding_status->color}}">
                                                                                        Approved
                                                                                    </div>
                                                                                @endif
                                                                                @if($walletFunding->wallet_funding_status_id == $walletFunding->wallet_funding_status::REJECTED)
                                                                                    <div class="label" style="color: white; background: {{$walletFunding->wallet_funding_status->color}}">
                                                                                        Rejected
                                                                                    </div>
                                                                                @endif
                                                                                @if($walletFunding->wallet_funding_status_id == $walletFunding->wallet_funding_status::CANCELLED)
                                                                                    <div class="label" style="color: white; background: {{$walletFunding->wallet_funding_status->color}}">
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
                                                                                {{$walletFunding->created_at->format('D d M Y')}}
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
                                                                                <a href="{{route('admin.customer.show', $walletFunding->user_id)}}" target="_blank" class="hover:underline text-tdhh-orange">{{$walletFunding->user->full_name}}</a>
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
                                                                                #{{$walletFunding->reference}}
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
                                                                                {{$walletFunding->other_details->rejected_approval_reason ?? ''}}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                
                                                
                                                            </div>
                                                
                                                
                                                        </div>
                                                        
                            
                                                    </div>
                            
                                                    <div class="modal-footer">
                                                        <button type="button" data-modal-toggle="model-reject-funding-{{$walletFunding->id}}" class="btn btn-transparent-black">Close</button>
                                                        <button type="submit"  class="btn text-white bg-red-500">Reject Funding</button>
                                                    </div>
                                                </div>
                                        </div>
                            
                                    </div>


                                    
                                    <tr>
                                        <td>
                                            {{$walletFundings->firstItem() + $loop->index}} 
                                        </td>
                                        <td>
                                            @if(!$walletFunding->is_refund)
                                                <div class="flex space-x-2">
                                                    <a href="{{asset($walletFunding->teller_image->getUrl())?? ''}}" target="_new" class="w-36 sm:w-48 h-24 sm:h-28 mr-2 flex flex-shrink-0 bg-black rounded-md overflow-hidden">
                                                        <img src="{{asset($walletFunding->teller_image->getUrl())?? ''}}"  alt="tdhh" class="object-cover m-auto" />
                                                    </a>
                                                </div>
                                            @endif
                                            @if($walletFunding->is_refund)
                                                Refund from cancelled reservation
                                            @endif
                                        </td>
                                        <td>
                                            {{$walletFunding->depositor_name}} 
                                        </td>
                                        <td>
                                            {{currency_format(floatval($walletFunding->converted_amount),$walletFunding->converted_currency_code)}}
                                            <br />
                                            ({{currency_format(floatval($walletFunding->amount_paid),$walletFunding->currency_code)}})
                                        </td>
                                        <td>
                                        {{date_format(date_create($walletFunding->date_paid),'M, j Y')}} 
                                        </td>
                                    
                                        <td>
                                            <div class="label" style="color: white; background: {{$walletFunding->wallet_funding_status ? $walletFunding->wallet_funding_status->color : 'grey'}}">
                                               {{ $walletFunding->wallet_funding_status ? $walletFunding->wallet_funding_status->name : ''}}
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <button id="dropdownButton{{$walletFunding->id}}" data-dropdown-toggle="dropdown-{{$walletFunding->id}}" class="dropdown" type="button">
                                                    Option 
                                                    <span class="fa fa-angle-down ml-2"></span>
                                                </button>
                                                <div id="dropdown-{{$walletFunding->id}}" class="dropdown-menu-nav hidden" >
                                                    <ul class="py-1" aria-labelledby="dropdownButton-1">
                                                        {{-- <li>
                                                            <a href="#"  data-modal-toggle="model-reject-funding-{{$walletFunding->id}}" class="dropdown-menu-option">View Reciept</a>
                                                        </li> --}}
                                                        <li>
                                                            <a href="{{route('front.account.wallet-funding.show', $walletFunding->id)}}"  class="dropdown-menu-option">Show Reciept</a>
                                                        </li>
                                                        @if($walletFunding->wallet_funding_status_id != $walletFunding->wallet_funding_status::REFUND_REQUESTED
                                                        && $walletFunding->wallet_funding_status_id != $walletFunding->wallet_funding_status::REFUNDED)
                                                            <li>
                                                                <a href="{{route('front.account.wallet-funding.request.refund', $walletFunding->id)}}" onclick="return confirm('Are you sure you want to request Refund for this transaction?');" class="dropdown-menu-option">Request Refund</a>
                                                            </li>
                                                        @endif
                                                        
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                        
                                    </tr>
                                
                                @empty
                                    <tr>
                                        <td colspan="7">
                                            <div class="table-info">
                                                <span class="fa fa-list"></span>
                                                <div class="italic mt-3 text-lg">
                                                    No fund in your wallet yet
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                <tr>
                                    <td colspan="9">
                                        {{$walletFundings->links()}}
                                    </td>
                                </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>


        
    </div>
@endsection