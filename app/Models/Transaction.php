<?php

namespace App\Models;

use App\Constants\TransactionConstants;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['account_id', 'amount', 'type', 'uuid', 'balance'];

    /**
     * Get account of a transaction
     * @return BelongsTo
     */
    function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    function type(): Attribute
    {
        return Attribute::make(
            get: function (string $value){
                if($value==TransactionConstants::TYPE_DEPOSIT)
                    return "DEPOSIT";

                return "WITHDRAW";
            }
        );
    }
}
