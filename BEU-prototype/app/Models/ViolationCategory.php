<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViolationCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'severity',
        'requires_parent_notification',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'requires_parent_notification' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function clauses()
    {
        return $this->hasMany(ViolationClause::class);
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }
}
