<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    protected $fillable = [ 'value', 'name', 'happened_at' ];

    use HasFactory;

    public function account(){
        return $this->belongsTo(\App\Models\Account::class);
    }
}
