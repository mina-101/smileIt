<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['account_id', 'amount', 'type', 'uuid'];

    /**
     * Get account of a transaction
     * @return BelongsTo
     */
    function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
