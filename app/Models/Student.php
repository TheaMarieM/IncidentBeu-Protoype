<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'first_name',
        'middle_name',
        'last_name',
        'date_of_birth',
        'gender',
        'grade_level',
        'section',
        'adviser_id',
        'address',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function adviser()
    {
        return $this->belongsTo(User::class, 'adviser_id');
    }

    public function parents()
    {
        return $this->belongsToMany(ParentModel::class, 'student_parent', 'student_id', 'parent_id')
            ->withPivot('is_primary_contact')
            ->withTimestamps();
    }

    public function incidents()
    {
        return $this->belongsToMany(Incident::class, 'incident_students')
            ->withPivot('narrative_report', 'narrative_file_path', 'offense_count', 'sanction_id')
            ->withTimestamps();
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }

    public function getPrimaryContactAttribute()
    {
        return $this->parents()->wherePivot('is_primary_contact', true)->first();
    }

    public function getOffenseCountAttribute()
    {
        return $this->incidents()->count();
    }

    public function getTardyCountAttribute()
    {
        return $this->attendanceRecords()
            ->where('status', 'tardy')
            ->whereYear('date', now()->year)
            ->count();
    }

    public function getAbsentCountAttribute()
    {
        return $this->attendanceRecords()
            ->where('status', 'absent')
            ->whereYear('date', now()->year)
            ->count();
    }
}
