<?php

namespace App\Models;

use App\Observers\TransactionsObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(TransactionsObserver::class)]
class Transactions extends Model
{
    protected $fillable = [ 'value', 'name', 'happened_at', 'spent' ];

    use HasFactory;

    public function account(){
        return $this->belongsTo(\App\Models\Account::class);
    }

    protected $casts = [
        'value' => 'decimal:2',
    ];
}
