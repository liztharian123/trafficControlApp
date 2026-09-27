<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;
   protected $fillable = ['company_name', 'contact_name', 'email', 'phone'];

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

}
