<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'contact'];

    public function collections(){
        return $this->hasMany(Collection::class, 'customer_id');
    }
    // public function collectionstoday(){
    //     return $this->hasMany(Collection::class, 'customer_id')->whereDate('created_at', Carbon::today());
    // }
}
