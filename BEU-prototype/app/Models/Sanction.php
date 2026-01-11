<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sanction extends Model
{
    use HasFactory;

    protected $fillable = [
        'violation_clause_id',
        'offense_count',
        'sanction_description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function clause()
    {
        return $this->belongsTo(ViolationClause::class, 'violation_clause_id');
    }
}
