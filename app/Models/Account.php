<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(\App\Observers\AccountObserver::class)]
class Account extends Model
{
    protected $fillable = [
        'name'
    ];

    use HasFactory;

    public function user(){
        return $this->belongsTo(\App\Models\User::class);
    }

    public function transaction(){
        return $this->hasMany(\App\Models\Transactions::class);
    }

    protected static function booted(): void
    {
        
        static::addGlobalScope('user', function (Builder $query) {
            if (auth()->check()) {
                $query->where('user_id', auth()->id());
            }
        });
    }
}
