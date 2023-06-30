<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $guarded = [''];

    public function TransactionDetail()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function Cart()
    {
        return $this->hasMany(Cart::class);
    }
}
