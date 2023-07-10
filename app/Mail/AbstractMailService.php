<?php

namespace App\Mail;

use Illuminate\Support\Facades\Mail;

abstract class AbstractMailService
{
    protected CommonMailer $mailer;
    protected string $view;
    protected string $subject;
    protected array $emails = [];
    protected string $mailer_name = 'smtp';

    protected function send(array $data, array $savedFiles = [], array $memoryFiles = []): bool
    {
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

        Mail::mailer($this->mailer_name)->to($this->emails)->send($this->mailer);
        return true;
    }
}
