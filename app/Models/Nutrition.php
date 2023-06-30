<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nutrition extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $guarded = [''];

    public function User()
    {
        $this->belongsTo(User::class);
    }
}
