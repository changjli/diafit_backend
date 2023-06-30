<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $guarded = [''];

    public function TransactionHeader()
    {
        return $this->belongsTo(TransactionHeader::class, 'transaction_id');
    }

    public function Food()
    {
        return $this->belongsTo(Food::class);
    }
}
