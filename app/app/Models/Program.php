<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = ['fiscal_year_id', 'code', 'name'];

    public function fiscalYear()
    {
        return $this->belongsTo(FiscalYear::class);
    }

    public function outputs()
    {
        return $this->hasMany(Output::class);
    }
}
