<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_name',
        'minimum_balance',
        'monthly_interest'
    ];


    // public function wallet(){
    //     return $this->hasMany(Wallet::class);
    // }
}
