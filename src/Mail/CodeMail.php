<?php

namespace Snawbar\Guardian\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CodeMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function build()
    {
        return $this->subject(sprintf('Guardian Security Code - %s', config('app.name')))
            ->view('snawbar-guardian::mail.code')
            ->with(['code' => $this->code]);
    }
}
