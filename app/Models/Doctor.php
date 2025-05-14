<?php
 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'name', 'email', 'visit_fee', 'about',
        'personal_contact_number', 'emergency_contact_number',
        'photo', 'signature',
    ];

    public function degrees()
    {
        return $this->belongsToMany(
            Degree::class,
            'doctor_degrees',
            'doctor_id',
            'degree_id'
        )->withTimestamps();
    }

    public function specialities()
    {
        return $this->belongsToMany(
            Speciality::class,
            'doctor_specialities',
            'doctor_id',
            'specialty_id'
        )->withTimestamps();
    }
}
