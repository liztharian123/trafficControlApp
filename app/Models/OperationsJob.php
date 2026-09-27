<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class OperationsJob extends Model
{
    use HasFactory;
    protected $fillable = [
        'permit_id', 'created_by', 'scheduled_date', 'shift_start', 'shift_end',
        'site_address', 'formatted_address', 'latitude', 'longitude',
        'geocoded_at', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
            'latitude'       => 'decimal:7',
            'longitude'      => 'decimal:7',
            'geocoded_at'    => 'datetime',
        ];
    }

    public function permit(): BelongsTo
    {
        return $this->belongsTo(Permit::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function crew(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('site_role')
                    ->withTimestamps();
    }

    public function hasCoordinates(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }   



}
