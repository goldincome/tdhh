@extends('layouts.admin')


@section('title')
    Wallet Funding
@endsection


@section('css')
    <style>
        .admin-sidebar-wallet{
            /* color: #000 !important; */
            background: #555 !important;
        }
    </style>
@endsection


@section('content')
    <div class="container">


        <div class="page-title-account">
            <div>
                Wallet Funding
            </div>
            <div class="pull-right">
            </div>
        </div>


        <div>

            <div class="mb-8  grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-2">
                <div class="p-4 box" style="box-shadow: none;">
                    <div>
                        Total Deposit (approved)
                    </div>
                    <div class="text-2xl font-bold">
                        {{currency_format($data['totalDeposited'], config('currency.default'))}}
                    </div>
                </div>
                <div class="p-4 box" style="box-shadow: none;">
                    <div>
                        Total Spent
                    </div>
                    <div class="text-2xl font-bold">
                        {{currency_format($data['totalSpent'], config('currency.default'))}}
                    </div>
                </div>
                <div class="p-4 box" style="box-shadow: none;">
                    <div>
                        Remaining Balance
                    </div>
                    <div class="text-2xl font-bold">
                        {{currency_format($data['currentBalance'], config('currency.default'))}}
                    </div>
                </div>
            </div>

            <div class="mt-4 table-container">
                <table class="table table-auto table-border table-align">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Ref No. / Username</th>
                            <th>Bank details</th>
                            <th>Deposit Info</th>
                            <th>Approvals</th>
                            {{-- <th>Rejection Reasons</th> --}}
                            <th>Status</th>
                            <th>Date submitted</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($fundings  as $funding)
                            <div id="model-reject-funding-{{$funding->id}}" tabindex="-1" aria-hidden="true" class="hidden modal h-modal">
                
                                <div class="modal-full max-w-2xl">
                                    <form action="{{route('admin.wallet-funding.reject', $funding->id)}}" method="POST">
                                        @csrf
                                        <div class="modal-content">
                    
                                            <div class="modal-header">
                                                <div class="modal-header-text">
                                                    Reject Funding Deposit Confirmation
                                                </div>
                                                <button type="button" data-modal-toggle="model-reject-funding-{{$funding->id}}" class="modal-header-close">
                                                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                                    <span class="sr-only">Close modal</span>
                                                </button>
                                            </div>
                    
                                            <div class="modal-body">
                    
                                                <div class="mt-10 border p-4">
                                                    Please reveiw below before you continue:
                                                    <ul class="mt-4 ml-4 list-disc">
                                                        <li>
                                                            You are about to reject Funding deposit by customer.
                                                        </li>
                                                        <li>
                                                            Make sure that you have accessed this fund deposit.
                                                        </li>
                                                        <li>
                                                            Once you reject this funding deposit, it can only be undone by Financial Admin.
                                                        </li>
                                                        <li>
                                                            The customer will be notified about this fund deposit rejection.
                                                        </li>
                                                        
                                                    </ul>
                                                </div>
                    
                                                <div class="mt-10">
                                                    <label class="cursor-pointer">
                                                        <div class="flex space-x-3">
                                                            <div class="mt-0.5 flex">
                                                                Reject Reason
                                                                <input type="textarea" row="5" name="reject_reason" class="form-input-checkbox" required />
                                                            </div>
                                                            
                                                        </div>
                                                    </label>
                                                </div>
                    
                                            </div>
                    
                                            <div class="modal-footer">
                                                <button type="button" data-modal-toggle="model-reject-funding-{{$funding->id}}" class="btn btn-transparent-black">Close</button>
                                                <button type="submit"  class="btn text-white bg-red-500">Reject Funding</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                    
                            </div>
                            <tr>
                                <td>
                                   {{$loop->iteration}}
                                </td>

                                <td>
                                   {{$funding->reference}}
                                   <br />
                                   {{$funding->user->full_name}}
                                </td>

                                <td>
                                    <div class="flex space-x-2">
                                        @if(!$funding->is_refund)
                                            <a href="{{route('admin.wallet-funding.details', $funding->id)}}" class="w-24 h-16 mr-2 flex flex-shrink-0 bg-black rounded-md overflow-hidden">
                                                <img src="{{asset($funding->teller_image->getUrl())?? ''}}" alt="tdhh" class="object-cover m-auto" />
                                            </a>
                                        @endif
                                        @if($funding->is_refund)
                                            <a href="{{route('admin.wallet-funding.details', $funding->id)}}" class="w-24 h-16 mr-2 flex flex-shrink-0">
                                                Refund from cancelled reservation
                                            </a>
                                        @endif
                                        <div class="md:my-auto">
                                            <div>
                                                <a href="{{route('admin.wallet-funding.details', $funding->id)}}" class="hover:underline font-bold">
                                                    {{$funding->bank_name ?? '' }}
                                                </a>
                                            </div>
                                            <div>
                                                <a href="{{route('admin.wallet-funding.details', $funding->id)}}" class="text-fade hover:underline">
                                                    {{$funding->bankAccount->account_name ?? '' }}
                                                </a>
                                            </div>
                                            <div>
                                                <a href="{{route('admin.wallet-funding.details', $funding->id)}}" class="text-fade hover:underline">
                                                    {{$funding->account_number ?? ''}}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div>
                                        <span class="text-fade whitespace-nowrap">
                                            Amount Deposited:
                                        </span>
                                        <div class="font-bold whitespace-nowrap">
                                            {{currency_format($funding->amount_paid, $funding->currency_code)}}
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-fade whitespace-nowrap">
                                            Converted to:
                                        </span>
                                        <div class="font-bold whitespace-nowrap">
                                            {{currency_format($funding->converted_amount, $funding->converted_currency_code)}}
                                        </div>
                                    </div>
                                    {{-- 
                                    <div class="h-px my-2 bg-gray-200"></div>
                                    <div>
                                        <span class="text-fade">
                                            Depositor name:
                                        </span>
                                        <div class="whitespace-nowrap">{{$funding->depositor_name}}</div>
                                    </div>
                                    <div>
                                        <span class="text-fade">
                                            Deposit date:
                                        </span>
                                        <div class="whitespace-nowrap">{{$funding->date_paid->format('D d M Y d:i')}}</div>
                                    </div>
                                     --}}
                                </td>

                                <td>
                                    First: {{$funding->first_approval ? 'Done': 'not Done'}}
                                    <br />
                                    Final: {{$funding->final_approval ? 'Done': 'not Done'}}
                                </td>

                                {{-- <td> --}}
                                    {{-- {{$funding->rejection_reasons}} --}}
                                {{-- </td> --}}

                                <td>
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
                                </td>

                                <td>
                                    {{$funding->created_at->format('D d M Y d:i')}}
                                </td>
                                <td>
                                    <div>
                                        {{-- @if($funding->wallet_funding_status_id == $funding->partner_commission_status::IN_PROGRESS) --}}
                                            <button id="dropdownButton{{$funding->id}}" data-dropdown-toggle="dropdown-{{$funding->id}}" class="dropdown" type="button">
                                                Option
                                                <span class="fa fa-angle-down ml-2"></span>
                                            </button>
                                            <div id="dropdown-{{$funding->id}}" class="dropdown-menu-nav hidden" >
                                                <ul class="py-1" aria-labelledby="dropdownButton-1">
                                                    <li>
                                                        <a href="{{route('admin.wallet-funding.details', $funding->id)}}" class="dropdown-menu-option">View Reciept</a>
                                                    </li>
                                                    @if($funding->wallet_funding_status_id ==  $funding->wallet_funding_status::SUBMITTED)
                                                        <li>
                                                            <a href="{{route('admin.wallet-funding.first.approval', $funding->id)}}" class="dropdown-menu-option" onclick="return confirm('Are you sure you want to Approve this funding? Do you want to continue?');">Admin Approve Funding</a>
                                                        </li>
                                                    @endif
                                                    @if($funding->wallet_funding_status_id ==  $funding->wallet_funding_status::PROCESSING)
                                                        <li>
                                                            <a href="{{route('admin.wallet-funding.approval', $funding->id)}}" class="dropdown-menu-option" onclick="return confirm('Are you sure you want to Approve this funding? Do you want to continue?');">Final Approve Funding</a>
                                                        </li>
                                                    @endif
                                                    @if($funding->wallet_funding_status_id ==  $funding->wallet_funding_status::SUBMITTED || $funding->wallet_funding_status_id ==  $funding->wallet_funding_status::PROCESSING)
                                                        <li>
                                                            <a href="#"  data-modal-toggle="model-reject-funding-{{$funding->id}}" class="dropdown-menu-option">Reject Funding</a>
                                                        </li>
                                                    @endif
                                                    @if($funding->wallet_funding_status_id ==  $funding->wallet_funding_status::REFUND_REQUESTED)
                                                    <li>
                                                        <a href="{{route('admin.refund.approval', $funding->id)}}" class="dropdown-menu-option" onclick="return confirm('Are you sure you want to Approve this refund? Do you want to continue?');">Approve Refund</a>
                                                    </li>
                                                @endif
                                                </ul>
                                            </div>
                                        {{-- @endif --}}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="table-info">
                                        <span class="fa fa-list"></span>
                                        <div class="italic mt-3 text-lg">
                                            No data yet
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

        </div>

    </div>
@endsection

