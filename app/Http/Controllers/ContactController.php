<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Webmozart\Assert\Assert;

class ContactController extends Controller
{
    public function __construct(private Mailer $mailer)
    {
    }

    public function __invoke(Request $request): RedirectResponse
    {
        $contactData = $request->get('contact');

        $senderName = $contactData['senderName'];
        $senderEmail = $contactData['senderEmail'];
        $message = $contactData['message'];

        Assert::stringNotEmpty($senderName);
        Assert::email($senderEmail);
        Assert::stringNotEmpty($message);

        $mail = new ContactMail($senderName, $senderEmail, $message);
        $mail
            ->replyTo($senderEmail, $senderName)
            ->subject('[tentacode.dev] Contact '.$senderName)
        ;

        $this->mailer
            ->to(config('mail.from.address'))
            ->send($mail)
        ;

        return redirect()->back()->with([
            'success' => 'Thanks for getting in touch! I will get back to you as soon as possible.'
        ]);
    }
}
