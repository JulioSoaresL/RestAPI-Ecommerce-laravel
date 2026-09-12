<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable([
    'tenant_id',
    'name',
    'email',
    'password',
    'cpf_cnpj',
])]
#[Hidden(['password', 'remember_token'])]
class Customer extends Model
{
    use HasApiTokens, Notifiable, BelongsToTenant;

    protected $casts = [
        'password' => 'hashed',
    ];
}
