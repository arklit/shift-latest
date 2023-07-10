<?php

namespace App\Mail;

class ClientMailService extends AbstractMailService
{
    public function setEmail(string $email): self
    {
        $this->emails[] = $email;

        return $this;
    }

    public function setView(string $view): self
    {
        $this->view = 'mails.' . $view;

        return $this;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function regularMailMethod(array $formData): bool
    {
        return $this->send($formData);
    }
}
