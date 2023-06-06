<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Services\MailService;

class ForgotPasswordController extends Controller
{
    protected $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }
    public function showLinkRequestForm()
    {
        return view('auth.forgotPassword');
    }

    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        $this->mailService->mailResetPassword($request);
        return  view('auth.vertifyEmailForgotPassword');
    }
}
