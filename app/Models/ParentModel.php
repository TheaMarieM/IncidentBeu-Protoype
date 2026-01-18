<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentModel extends Model
{
    use HasFactory;

    protected $table = 'parents';

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'relationship',
        'email',
        'phone',
        'alternate_phone',
        'address',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_parent', 'parent_id', 'student_id')
            ->withPivot('is_primary_contact')
            ->withTimestamps();
    }

    public function notifications()
    {
        return $this->hasMany(ParentNotification::class, 'parent_id');
    }

    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }
}
