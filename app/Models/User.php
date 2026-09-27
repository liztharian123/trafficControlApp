<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{

    protected $fillable = ['name', 'email', 'password', 'role', 'department_id'];
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class, 'created_by');
    }

    public function permits(): HasMany
    {
        return $this->hasMany(Permit::class, 'created_by');
    }

    public function operationsJobs(): HasMany
    {
        return $this->hasMany(OperationsJob::class, 'created_by');
    }

    public function crewJobs(): BelongsToMany
    {
        return $this->belongsToMany(OperationsJob::class)
                    ->withPivot('site_role')
                    ->withTimestamps();
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function inDepartment(string $slug): bool
    {
        return $this->isAdmin() || $this->department?->slug === $slug;
    }

}
