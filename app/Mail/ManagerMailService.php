<?php

namespace App\Mail;

class ManagerMailService extends AbstractMailService
{
    public function addEmail(string|array $mail): static
    {
        if (is_array($mail)) {
            $this->prodMails = array_merge($this->prodMails, $mail);
        } else {
            $this->prodMails[] = $mail;
        }
        return $this;
    }

    public function setView(string $view)
    {
        $this->view = 'admin.' . $view;
        return $this;
    }

    public function regularMailMethod(array $formData, string $subject, $letterView, string $mailKey): bool
    {
        $this->view = $letterView;
        $this->subject = $subject;
        $this->mailKey = $mailKey;
        return $this->send($formData);
    }
}
