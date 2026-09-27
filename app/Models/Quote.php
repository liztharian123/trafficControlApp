<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'created_by', 'reference_no', 'site_address',
        'description', 'start_date', 'end_date', 'amount', 'status',]; 

        protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date'   => 'date',
            'amount'     => 'decimal:2',
        ];
    }
    
    public function client():BelongsTo
    {
        return $this->belongsTo(Client::class);
    }   

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    public function permit(): HasOne
    {
        return $this->hasOne(Permit::class);
    }

    public function scopeAwaitingPermit(Builder $query): Builder
    {
        return $query->where('status', 'approved')->doesntHave('permit');
    }

    public function scopeFilter(Builder $query, ?string $search, ?string $status): Builder
    {
        return $query
            ->when($search, fn (Builder $q) => $q->where(fn (Builder $w) => $w
                ->where('reference_no', 'like', "%{$search}%")
                ->orWhere('site_address', 'like',  "%{$search}%")
                ->orWhereHas('client', fn (Builder $c) => $c->where('company_name', 'like', "%{$search}%"))))
            ->when($status, fn (Builder $q) => $q->where('status', $status));
    }


}
