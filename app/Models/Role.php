<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Role constants
    const DISCIPLINE_CHAIR = 'discipline_chair';
    const PRINCIPAL = 'principal';
    const ASSISTANT_PRINCIPAL = 'assistant_principal';
    const ADVISER = 'adviser';
    const PARENT = 'parent';
}
