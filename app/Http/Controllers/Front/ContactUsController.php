<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\ContactUsFormRequest;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\MessageFormRequest;
use App\Support\Seo;

class ContactUsController extends Controller
{
    
    public function index(){

        return view('contact.form', Seo::contact());
    }

    public function store(ContactUsFormRequest $request)
    {

        try {
            $data = $request->validated();
            unset($data['captcha']);
            Mail::send('emails.php-builds.contact-us-message', ['data'=>$data], function ($message) {
                $message->to(config('app.admin_email'), 'TDHH');
                $message->subject('New Contact Us Message - TDHH');
                $message->replyTo('info@tdhh.co.uk');
            });
        } catch (Exception $e) {
            \Log::info($e->getMessage());
        }

        $this->flashSuccessMessage('Contact Us Message Sent Successfully');
        return back();
    }

    public function reloadCaptcha()
    {
        return response()->json(['captcha' => captcha_img()]);
    }
}
