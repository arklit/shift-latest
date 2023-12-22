<?php

namespace App\Mail;

class ManagerMailService extends AbstractMailService
{
    public function regularMailMethod(array $formData, string $subject, $letterView, string $mailKey): bool
    {
        $this->view = $letterView;
        $this->subject = $subject;
        $this->mailKey = $mailKey;
        return $this->send($formData);
    }
}
