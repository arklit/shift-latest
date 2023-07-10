<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles;

    protected $table = 'client_customers';

    protected string $guard_name = self::GUARD;
    protected string $guard = self::GUARD;

    public $timestamps = false;


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'roles',
        'permissions'
    ];


    const TYPE_WHOLESALE = 'wholesale';
    const TYPE_RETAIL = 'retail';

    const GUARD = 'customers';

    public function setPassword(string $password): self
    {
        $this->password = Hash::make($password);

        return $this;
    }
}
