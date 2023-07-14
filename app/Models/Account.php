<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'balance'];

    /**
     * Get owner od account
     * @return BelongsTo
     */
    function user() : BelongsTo{
        return $this->belongsTo(User::class);
    }

    /**
     * Get all transaction of an account
     * @return HasMany
     */
    function transactions(){
        return $this->hasMany(Transaction::class)->orderBy('created_at', "DESC");
    }
}
