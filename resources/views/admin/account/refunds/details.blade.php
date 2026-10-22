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


@section('content')
    <div class="container">


        <div class="page-title-account">
            <div>
                Wallet funding details
                <span class="font-montserrat text-sm text-tdhh-orange">
                    #134567890
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
                <button type="submit" class="btn text-white bg-green-500">
                    <span class="fa fa-check mr-2"></span>
                    Approve
                </button>
                <button type="submit" class="btn text-white bg-red-500">
                    <span class="fa fa-times mr-2"></span>
                    Reject
                </button>
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
                            <a href="{{asset('img/bgs/manchester.jpg')}}" target="_blank" class="block w-48 h-32 mt-2 bg-black rounded-md">
                                <img src={{asset('img/bgs/manchester.jpg')}} alt="payment" class="object-contain m-auto w-full h-full">
                            </a>
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
                                    <a href="/admin/customers/12345" target="_blank" class="hover:underline text-tdhh-orange">Firstname Lastname</a>
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
                                    #134567890
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
                                    NGN 950000.00
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
                                    GBP 1021.51
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
                                    Name here
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
                                    Fri 24 Mar 2023
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
                                Zenith Bank
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
                                NGN
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
                                TD Holiday Homes
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
                                0000000000
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
                                Admin 1
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
                                Fri 24 Mar 2023
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
                                Admin 2
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
                                Sat 25 Mar 2023
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
                                <div class="label label-yellow">
                                    Awaiting approval
                                </div>
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
                                Fri 24 Mar 2023
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
                                <a href="/admin/customers/12345" target="_blank" class="hover:underline text-tdhh-orange">Firstname Lastname</a>
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
                                #134567890
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
                                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                            </div>
                        </div>
                    </div>
                </div>


            </div>


        </div>

    </div>
@endsection

