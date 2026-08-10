<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    use HasFactory;

    protected $fillable = ['sub_output_id', 'code', 'name'];

    public function subOutput()
    {
        return $this->belongsTo(SubOutput::class);
    }

    public function subComponents()
    {
        return $this->hasMany(SubComponent::class);
    }
}
