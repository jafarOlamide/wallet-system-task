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

    public function walletType(){
        return $this->hasOne(WalletType::class, 'id', 'wallet_type_id');
    }

    public function transactions(){
        return $this->hasMany(Transaction::class, 'wallet_id')->select('amount', 'created_at');
    }
    
}
