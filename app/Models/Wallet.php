<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;
    protected $fillable = [
        'balance',
        'wallet_type_id',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    
}
