<?php

namespace App\Mail;

use App\Models\Configurator;
use Illuminate\Support\Facades\Mail;

abstract class AbstractMailService
{
    protected CommonMailer $mailer;
    protected string $view;
    protected string $subject;
    protected string $mailKey;
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

        $key = $this->mailKey ?? 'email';

        $recipients = Configurator::query()->where('key', $key)->first()->value;
        Mail::to('pechenkov39@gmail.com')->send($this->mailer);
        return true;
    }
}
