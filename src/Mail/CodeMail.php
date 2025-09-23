<?php

namespace Snawbar\Guardian\Mail;

use Illuminate\Mail\Mailable;

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
            ->with(['code' => $this->code]);
    }

    private function getSubject(): string
    {
        return sprintf('Guardian Security Code - %s', $this->getAppName());
    }

    private function getAppName(): string
    {
        return config('app.name', 'Application');
    }
}
