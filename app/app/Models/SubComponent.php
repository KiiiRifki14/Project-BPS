<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubComponent extends Model
{
    use HasFactory;

    protected $fillable = ['component_id', 'code', 'name'];

    public function component()
    {
        return $this->belongsTo(Component::class);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
