<?php
 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Degree extends Model
{
    protected $fillable = ['name']; // adjust as needed

    public function doctors()
    {
        return $this->belongsToMany(
            Doctor::class,
            'doctor_degrees',
            'degree_id',
            'doctor_id'
        )->withTimestamps();
    }
}
