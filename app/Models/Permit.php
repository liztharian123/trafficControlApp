<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Permit extends Model
{
    use HasFactory;
    protected $fillable = [
        'quote_id', 'created_by', 'permit_number', 'authority',
        'lodged_date', 'expiry_date', 'status', 'document_path', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'lodged_date' => 'date',
            'expiry_date' => 'date',
        ];
    }
    
    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function job(): HasOne
    {
        return $this->hasOne(OperationsJob::class);
    }

    public function scopeReadyToSchedule(Builder $query): Builder
    {
        return $query->where('status', 'approved')->doesntHave('job');
    }

}
 