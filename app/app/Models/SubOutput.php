<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubOutput extends Model
{
    use HasFactory;

    protected $fillable = ['output_id', 'code', 'name'];

    public function output()
    {
        return $this->belongsTo(Output::class);
    }

    public function components()
    {
        return $this->hasMany(Component::class);
    }
}
