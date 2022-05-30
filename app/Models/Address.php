<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'postal_code',
        'facturation_name',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function order(){
        return $this->hasOne(Order::class);
    }
}
