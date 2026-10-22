<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Invoice - TD Holiday Homes</title>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script> 
        <script type="text/JavaScript" src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.js"></script>
        <script>
            $(document).ready(function(){
                $("#printInvoice").click(function(){
                    $("#invoiceDocument").print();
                });
            });
        </script>
    </head>
    <body id="body" style="background: {{(!$reservation->isDownload) ? "#888" : "#fff"}}; margin: 0px; padding: 0px;">

        @if(!$reservation->isDownload)
            <div style="max-width: 1000px; margin: 30px auto; display: flex;">
                <a href="{{route('front.account.reservation.invoice',  $reservation->id)}}"  style="margin-right: 10px; padding: 8px 24px; text-decoration: none; border-radius: 4px; white-space: nowrap; color: #000; background-color: #fff; border: none; box-shadow: 0px 2px 5px rgba(0,0,0,0.4); cursor: pointer;">
                    Download
                </a>
                <button type="button" id="printInvoice" style="margin-right: 10px; padding: 8px 24px; border-radius: 4px; white-space: nowrap; color: #000; background-color: #fff; border: none; box-shadow: 0px 2px 5px rgba(0,0,0,0.4); cursor: pointer;">
                    Print
                </button>
            </div>
        @endif
        
        <div id="invoiceDocument" style="max-width: 1000px; margin: {{(!$reservation->isDownload) ? "30px auto" : "0px auto"}}; background: #fff; font-size: 16px; line-height: 24px; font-weight: normal; font-family: Montserrat;">

            <div style="height: 10px; background-color: #ffa200;"></div>

            <div style="padding: 0px 30px; overflow: hidden;">
                <div style="font-size: 56px; font-weight: bold; padding: 52px 0;">
                    INVOICE
                </div>


                <div>
                    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                        <tbody>
                            <tr>
                                <td>
                                    <div>
                                        <div style="font-weight: bold;">
                                            TD Holiday Homes
                                        </div>
                                        <div>
                                            89, the company street, city, state, country
                                            <br />
                                            123-456-7890
                                            <br />
                                            hello@tdholidayhomes.com
                                            <br />
                                            tdholidayhomes.com
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <table border="0" cellpadding="0" cellspacing="0">
                                        <tbody>
                                            <tr>
                                                <td style="padding-right: 30px; font-weight: bold;">
                                                    Invoice No:
                                                </td>
                                                <td>
                                                    {{$reservation->reservation_no}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-right: 30px; font-weight: bold;">
                                                    Issue date:
                                                </td>
                                                <td>
                                                    {{date_format(date_create($reservation->created_at),'j-M-Y')}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-right: 30px; font-weight: bold;">
                                                    Due date:
                                                </td>
                                                <td>
                                                    {{date_format(date_create($reservation->created_at),'j-M-Y')}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-right: 30px; font-weight: bold;">
                                                    Status:
                                                </td>
                                                <td>
                                                    Fully paid
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                
                <div style="margin-top: 36px; overflow: hidden;">
                    <div>
                        <div style="font-weight: bold;">
                            Billed To
                        </div>
                        <div>
                            {{$reservation->guest_information->guest_name}}
                            <br />
                            {{$reservation->guest_information->guest_phone}}
                            <br />
                            {{$reservation->guest_information->guest_email}}
                            <br />
                            {{auth()->user()->address}}
                        </div>
                    </div>
                </div>

                <div style="height: 10px; margin: 52px 0 0; background-color: #000;"></div>
                
                <div>
                    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>
                                    SN
                                </th>
                                <th>
                                    Item details
                                </th>
                                <th style="width: 100px;">
                                    Number of nights
                                </th>
                                <th style="width: 100px;">
                                    Avg Price per night
                                </th>
                                <th style="width: 100px;">
                                    Sub Total
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    1
                                </td>
                                <td>
                                    <div style="font-weight: bold;">
                                        {{$reservation->room_information->name}}
                                    </div>
                                    <div style="color: #888;">
                                        The short description of the propery here
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    {{$reservation->num_of_night}}
                                </td>
                                <td style="text-align: center;">
                                    {{currency_format(number_format($reservation->price_information->total/$reservation->num_of_night, 2), $reservation->price_information->country_code ?? config('currency.default'))}}
                                </td>
                                <td style="text-align: right;">
                                    {{currency_format($reservation->subtotal, $reservation->price_information->country_code ?? config('currency.default'))}}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div style="color: #f00; font-size: 14px; margin-top: 10px;">
                        <div>
                            * Service charges are a summation of Cleaning fee 
                        </div>
                        <div style="margin-top: -5px;">
                            * This charge varies depending on your booking preferences
                        </div>
                    </div>
                </div>

                
                <div style="margin-top: 36px;">
                    <table border="0" cellpadding="10" cellspacing="0" style="width: 100%;">
                        <tbody>
                            <tr>
                                <td colspan="4" style="text-align: right;">
                                    Sub Total
                                </td>
                                <th style="width: 100px; text-align: right;">
                                    {{currency_format($reservation->subtotal, $reservation->price_information->country_code ?? config('currency.default'))}}
                                </td>
                            </tr>
                            
                            <tr>
                                <td colspan="4" style="text-align: right;">
                                    Cleaning Fee
                                </td>
                                <th style="width: 100px; text-align: right;">
                                    {{currency_format($reservation->cleaning_fee + $reservation->parking_fee, $reservation->price_information->country_code ?? config('currency.default'))}}
                                </td>
                            </tr>
                            
                            <tr>
                                <td colspan="3"></td>
                                <td style="width: 100px; text-align: right; background: #ffa200;">
                                    Total
                                </td>
                                <th style="width: 100px; text-align: right; font-weight: bold; background: #ffa200;">
                                    {{currency_format($reservation->total, $reservation->price_information->country_code ?? config('currency.default'))}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>


                <div style="border-top: 10px solid #ffa200; padding: 48px 0 0; margin: 48px 0;">
                    <div style="font-size: 20px; font-weight: bold; color: #000;">
                        Thank you for choosing TD Holiday Homes
                    </div>
                </div>
            </div>
           

        </div>
            
    </body>
</html>