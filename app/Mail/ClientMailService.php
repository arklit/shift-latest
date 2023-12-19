<?php

namespace App\Mail;

class ClientMailService extends AbstractMailService
{
    public function setEmail(string $email)
    {
        $this->prodMails[] = $email;
        return $this;
    }

    public function setView(string $view)
    {
        $this->view = 'client.' . $view;
        return $this;
    }

    public function regularMailMethod(array $formData, string $subject): bool
    {
        $this->subject = $subject;
        return $this->send($formData);
    }
}
