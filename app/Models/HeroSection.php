<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSection extends Model
{
    use HasFactory;
    public $incrementing=false;
    protected $table ='hero_section';
    protected $fillable = ['title', 'description', 'lang'];
}
