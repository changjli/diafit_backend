<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionHeader extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $guarded = [''];

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function TransactionDetail()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id');
    }
}
