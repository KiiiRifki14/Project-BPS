<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'file_name',
        'stored_file_name',
        'file_path',
        'file_size',
        'file_type',
        'uploaded_by_user_id',
        'label',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        }
        return round($bytes / 1024, 2) . ' KB';
    }

    public function isImage(): bool
    {
        return in_array(strtolower($this->file_type), ['jpg', 'jpeg', 'png']);
    }

    public function isPdf(): bool
    {
        return strtolower($this->file_type) === 'pdf';
    }
}
