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

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function minimumBalance($id)
    {
        $minimum_balance = $this::
        join('wallet_types', 'wallet_types.id', '=', 'wallets.wallet_type_id')
        ->select('wallet_types.minimum_balance')
        ->where('wallets.id', $id)
        ->first();

        return $minimum_balance;
    }

    
}
