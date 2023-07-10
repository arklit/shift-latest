<?php

namespace App\Services;

use App\Jobs\SendRegistrationMailJob;
use App\Models\Company;
use App\Models\Customer;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerService
{
    /**
     * @throws Exception
     */
    public function register(array $data): array
    {

        try {
            DB::beginTransaction();

            $companyId = $this->registerCompany($data);

            $customer = $this->registerCustomer($data, $companyId);

            $customer->assignRole('admin');

            DB::commit();
        } catch (\PDOException $e) {
            DB::rollBack();
            throw new Exception('Не удалось зарегистрировать пользователя или компанию');
        }

        $token = $customer->createToken('customer_access')->plainTextToken;

        $password = $this->generatePassword($customer);

        SendRegistrationMailJob::dispatch($customer, $password);

        // TODO: отправка пользователя в УС в очереди

        return ['token' => $token];

    }

    private function registerCustomer(array $data, int $companyId): Customer
    {
        if (Customer::where('email', $data['email'])->orWhere('phone', $data['phone'])->first()) {
            throw new Exception('Пользователь с таким email или номером телефона уже зарегистрирован');
        }

        $customer = new Customer();

        $customer->name = $data['name'];
        $customer->surname = $data['surname'];
        $customer->patronymic = $data['patronymic'];
        $customer->phone = $this->prettyPhone($data['phone']);
        $customer->email = $data['email'];
        $customer->company_id = $companyId;
        $customer->type = Customer::TYPE_WHOLESALE;
        $customer->save();

        return $customer;
    }

    private function registerCompany(array $data): int
    {
        if (Company::where('inn', $data['inn'])->first()) {
            throw new Exception('Организация с таким ИНН уже зарегистрирована');
        }

        $company = new Company();

        $company->legal_name = $data['companyName'];
        $company->inn = $data['inn'];
        $company->save();

        // TODO: сделать сохранение видов деятельности
//        $this->saveCompanyActivities($company->id, $data['activities']);

        return $company->id;
    }

    private function saveCompanyActivities($companyId, ?array $activities = null)
    {
        if (!empty($activities)) {
            foreach ($activities as $activity) {

            }
        }
    }

    private function generatePassword(Customer $customer): string
    {
        $password = Str::random(10);
        $customer->setPassword($password)->save();

        return $password;
    }

    private function createCustomer(array $data)
    {
        try {
            DB::beginTransaction();

            $customer = $this->registerCustomer($data, auth()->user()->company_id);

            $customer->assignRole('manager');

            DB::commit();
        } catch (\PDOException $e) {
            DB::rollBack();
            throw new Exception('Не удалось зарегистрировать пользователя');
        }

        $password = $this->generatePassword($customer);

        SendRegistrationMailJob::dispatch($customer, $password);

        return true;
    }

    private function prettyPhone($phone)
    {
        return preg_replace('/[^0-9]/', '', $phone);
    }

    /**
     * @throws Exception
     */
    public function authAttempt(array $loginData): void
    {
        $credentials = [
            'email' => $loginData['login'],
            'password' => $loginData['password']
        ];
        if (!Auth::guard(Customer::GUARD)->attempt($credentials)) {
            $credentials = [
                'phone' => $this->prettyPhone($loginData['login']),
                'password' => $loginData['password'],
            ];
            if (!Auth::guard(Customer::GUARD)->attempt($credentials)) {
                throw new \Exception('Incorrect data');
            }
        }
    }
}
