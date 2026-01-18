<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'employee_id',
        'phone',
        'status',
        'grade_level',
        'section',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function advisees()
    {
        return $this->hasMany(Student::class, 'adviser_id');
    }

    public function advisedStudents()
    {
        return $this->hasMany(Student::class, 'adviser_id');
    }

    public function reportedIncidents()
    {
        return $this->hasMany(Incident::class, 'reported_by');
    }

    public function approvedIncidents()
    {
        return $this->hasMany(IncidentApproval::class, 'approved_by');
    }

    public function hasRole($role)
    {
        return $this->role && $this->role->name === $role;
    }

    public function hasAnyRole($roles)
    {
        return $this->role && in_array($this->role->name, (array) $roles);
    }

    public function isDisciplineChair()
    {
        return $this->hasRole(Role::DISCIPLINE_CHAIR);
    }

    public function isPrincipal()
    {
        return $this->hasRole(Role::PRINCIPAL);
    }

    public function isAssistantPrincipal()
    {
        return $this->hasRole(Role::ASSISTANT_PRINCIPAL);
    }

    public function isAdviser()
    {
        return $this->hasRole(Role::ADVISER);
    }
}
