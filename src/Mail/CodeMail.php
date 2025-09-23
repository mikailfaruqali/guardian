<?php

namespace Snawbar\Guardian\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Auth;

class CodeMail extends Mailable
{
    public string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function build(): self
    {
        return $this->subject($this->getSubject())
            ->view('snawbar-guardian::mail.code')
            ->with([
                'subject' => $this->getSubject(),
                'appName' => $this->getAppName(),
                'code' => $this->code,
                'user' => Auth::user(),
            ]);
    }

    private function getSubject(): string
    {
        return sprintf('%s OTP Code - %s', $this->getAppName(), $this->getDomainName());
    }

    private function getAppName(): string
    {
        return config('app.name');
    }

    private function getDomainName(): string
    {
        return request()->getHost();
    }
}
