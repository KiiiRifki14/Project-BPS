<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        'is_checked',
        'checked_by_user_id',
        'checked_at',
    ];

    protected $casts = [
        'is_checked' => 'boolean',
        'checked_at' => 'datetime',
    ];


    /**
     * 🧹 GUARD 3: Physical Storage Garbage Collection
     * Automatically deletes the file from private disk whenever
     * a Document record is removed from the database.
     */
    protected static function booted(): void
    {
        static::deleting(function (Document $document) {
            if ($document->file_path && Storage::disk('private')->exists($document->file_path)) {
                Storage::disk('private')->delete($document->file_path);
            }
        });
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }

    public function checkedBy()
    {
        return $this->belongsTo(User::class, 'checked_by_user_id');
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
