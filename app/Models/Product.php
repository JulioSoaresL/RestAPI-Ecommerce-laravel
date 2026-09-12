<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'tenant_id',
    'category_id',
    'name',
    'description',
    'price',
    'stock'
])]
class Product extends Model
{
    use BelongsToTenant;


}
