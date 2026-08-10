<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'code',
        'name',
        'pagu',
        'verification_status',
        'rejection_note',
    ];

    protected $casts = [
        'pagu' => 'decimal:2',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Get the full breadcrumb path for this item.
     */
    public function getBreadcrumbAttribute(): array
    {
        $account      = $this->account;
        $subComponent = $account->subComponent;
        $component    = $subComponent->component;
        $subOutput    = $component->subOutput;
        $output       = $subOutput->output;
        $program      = $output->program;

        return [
            'program'      => $program,
            'output'       => $output,
            'sub_output'   => $subOutput,
            'component'    => $component,
            'sub_component'=> $subComponent,
            'account'      => $account,
            'item'         => $this,
        ];
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->verification_status) {
            'APPROVED' => 'badge-approved',
            'REJECTED' => 'badge-rejected',
            default    => 'badge-pending',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->verification_status) {
            'APPROVED' => 'Siap Cair',
            'REJECTED' => 'Ditolak',
            default    => 'Menunggu Verifikasi',
        };
    }

    public function getPaguFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->pagu, 0, ',', '.');
    }
}
