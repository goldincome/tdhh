@vite('resources/js/app.js')


@extends('layouts.app')


@section('title')
    Contact
@endsection


@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet">
@endsection


@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript">
        $('#reload').click(function () {
            $.ajax({
                type: 'GET',
                url: 'reload-captcha',
                success: function (data) {
                    $(".captcha .captchaImage").html(data.captcha);
                }
            });
        });
    </script>
@endsection


@section('content')
    <div>

        <div class="top-wide">
            <div>
                <div>
                    Contact Us
                </div>
                <div>
                    <a href="/">Home</a>
                    <span>/</span>
                    <a>Contact</a>
                </div>
            </div>
            <div></div>
        </div>

        <div class="container mx-auto py-8 sm:py-12">
            @include('layouts.errors-and-messages')
            <div class="lg:flex">

                <div class="w-full lg:w-1/2 xl:w-auto flex-grow lg:border-r border-gray-300 xl:mr-10 lg:pr-10">

                    <div class="font-extrabold text-2xl md:text-3xl xl:text-4xl leading-10">
                        Welcome to our Helpdesk
                       
                    </div>
                    <div class="mt-3 max-w-lg">
                        <div class="text-fade">
                            Want to make an enquiry? Kindly fill out the form.
                        </div>
                        <div class="italic">
                            For more information about a property, please message the owner of the property instead.
                        </div>
                    </div>

                    <div class="mt-6 border-t border-gray-300"></div>

                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <div class="flex">
                            <div class="w-8 h-8 my-auto flex flex-shrink-0 mr-3 rounded-full bg-tdhh-orange">
                                <span class="fa fa-phone-square m-auto"></span>
                            </div>
                            <div class="my-auto">
                                <a href="tel:+442083161616" class="hover:underline">+44 (0) 203 247 4747</a>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="w-8 h-8 my-auto flex flex-shrink-0 mr-3 rounded-full bg-tdhh-orange">
                                <span class="fa fa-envelope-square m-auto"></span>
                            </div>
                            <div class="my-auto">
                                <a href="mailto:info@tdhh.co.uk" class="hover:underline">info@tdhh.co.uk</a>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex">
                        <div class="w-8 h-8 my-auto flex flex-shrink-0 mr-3 rounded-full bg-tdhh-orange">
                            <span class="fa fa-map-marker m-auto"></span>
                        </div>
                        <div class="my-auto">
                            <a href="https://goo.gl/maps/KoYUbkZxWWjis4fu6" class="hover:underline">
                                Unit 6, Block 3 Woolwich Dockyard Industrial Estate, Woolwich Church Street, Charlton, London. SE18 5PQ.
                            </a>
                        </div>
                    </div>

                    <div class="w-full h-full max-h-96 mt-10 bg-gray-200 rounded-lg overflow-hidden">
                         <img src={{asset('img/bgs/contact-us.jpeg')}} alt="Contact TD Holiday Homes" class="object-cover" /> 
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1392.5530469700082!2d0.04919612724307746!3d51.49277962343358!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47d8a8f4dda29465%3A0x70755e1bd6b9021a!2sDockyard%20Industrial%20Estate%2C%20Woolwich%20Church%20St%2C%20Woolwich%2C%20London%20SE18%205PQ%2C%20UK!5e0!3m2!1sen!2sng!4v1586265494367!5m2!1sen!2sng"
                            frameborder="0" style="width: 100%; height: 100%; border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                    </div>

                </div>


                <div class="mt-10 lg:mt-0 w-full lg:w-1/2 xl:w-[28rem] flex-shrink-0 pl-0 lg:pl-10 xl:pl-0">

                    <form action="{{route('contact-us.store')}}" method="post" class="bg-white p-8 rounded-xl shadow-lg">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6">
                            <div class="form-group">
                                <label>
                                    Full Name
                                    <span class="form-input-required">*</span>
                                </label>
                                <input type="text" name="name" placeholder="eg: John Doe" class="form-input" value="{{old('name')}}"/>
                            </div>
                            <div class="form-group">
                                <label>
                                    Email Address
                                    <span class="form-input-required">*</span>
                                </label>
                                <input type="email" name="email" placeholder="email@example.com" class="form-input"  value="{{old('email')}}"/>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6">
                            <div class="form-group">
                                <label>
                                    Phone Number
                                    <span class="form-input-required">*</span>
                                </label>
                                <input type="tel" name="phone" placeholder="eg: +44 XX XXXX XXXX" class="form-input" value="{{old('phone')}}"/>
                            </div>
                            
                            <div class="form-group">
                                <label>
                                    Order Number
                                </label>
                                <input type="number" name="order_number" placeholder="eg: 1234567890" class="form-input" value="{{old('order_number')}}"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>
                                Category
                                <span class="form-input-required">*</span>
                            </label>
                            <select name="category" class="form-input" required>
                                <option value="" disabled selected>-- Select --</option>
                                <option value="Order Confirmation">Order Confirmation</option>
                                <option value="Order Cancellation">Order Cancellation</option>
                                <option value="Order Payment">Order Payment</option>
                                <option value="Order Refund">Order Refund</option>
                                <option value="Account Issues">Account Issues</option>
                                <option value="Other Requests">Other Requests</option>
                            </select>
                        </div>


                        <div class="form-group">
                            <label>
                                Subject
                                <span class="form-input-required">*</span>
                            </label>
                            <input type="text" name="subject" placeholder="eg: I want to ask about..." class="form-input" value="{{old('subject')}}" />
                        </div>


                        <div class="form-group">
                            <label>
                                Message
                                <span class="form-input-required">*</span>
                            </label>
                            <textarea name="message" id="message" cols="30" rows="10" class="form-input" style="width: 100%; height: 130px;  resize: vertical">{{old('message')}}</textarea>
                        </div>

                        <div class="mt-3 text-sm font-semibold text-gray-500">
                            <div class="captcha">
                                <div class="flex space-x-2">
                                    <span class="captchaImage">{!! captcha_img() !!}</span>
                                    <button type="button" class="reload btn bg-black text-white btn-md" id="reload">
                                        <span class="text-2xl relative -top-2">
                                            &#x21bb;
                                        </span>
                                    </button>
                                </div>
                                <div class="captcha span mt-2">
                                    <input id="captcha" type="text" class="form-input" placeholder="Enter Captcha" name="captcha">
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="btn btn-lg btn-block btn-tdhh-orange">
                                Send message
                            </button>
                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>
@endsection
