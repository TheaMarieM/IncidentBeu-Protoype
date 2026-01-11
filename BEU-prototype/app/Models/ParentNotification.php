<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'incident_id',
        'parent_id',
        'student_id',
        'notification_type',
        'message',
        'status',
        'sent_at',
        'error_message',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }

    public function parent()
    {
        return $this->belongsTo(ParentModel::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
