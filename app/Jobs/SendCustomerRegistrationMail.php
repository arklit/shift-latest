<?php

namespace App\Jobs;

use App\Models\Customer;
use App\Notifications\SitemapGenerated;
use App\Services\XMLMapGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Orchid\Platform\Models\User;

class SendCustomerRegistrationMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Customer $customer;
    private string $password;
    public function __construct(Customer $customer,  string $password)
    {
        $this->customer = $customer;
        $this->password = $password;
    }

    public function handle(): void
    {
        // TODO: сделать отправку письма клиенту о регистрации
    }
}
