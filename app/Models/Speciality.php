<?php
 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Speciality extends Model
{
    protected $table = 'specialities'; // or 'specialties'
    protected $fillable = ['name'];     // adjust as needed

    public function doctors()
    {
        return $this->belongsToMany(
            Doctor::class,
            'doctor_specialities',
            'specialty_id',
            'doctor_id'
        )->withTimestamps();
    }
}

