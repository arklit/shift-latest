<?php

namespace App\Mail;

use Illuminate\Support\Facades\Mail;

abstract class AbstractMailService
{
    protected CommonMailer $mailer;
    protected string $view;
    protected string $subject;
    protected array $recipients;

    protected function send(array $data, array $savedFiles = [], array $memoryFiles = []): bool
    {
        if (config('rocont.is_debug')) {
            return false;
        }

        $this->mailer = new CommonMailer($data, $this->view);
        $this->mailer->subject = $this->subject;

        if (!empty($savedFiles)) {
            foreach ($savedFiles as $file) {
                $this->mailer->attach($file);
            }
        }

        if (!empty($memoryFiles)) {
            foreach ($memoryFiles as $name => $file) {
                $this->mailer->attachData($file, $name);
            }
        }

        Mail::to($this->recipients)->send($this->mailer);
        return true;
    }
}
