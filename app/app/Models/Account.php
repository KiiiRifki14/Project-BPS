<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = ['sub_component_id', 'code', 'name'];

    public function subComponent()
    {
        return $this->belongsTo(SubComponent::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
