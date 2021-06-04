<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'mnemonic',
    ];


    public function instructorAreas()
    {
        return $this->hasMany(
            InstructorArea::class
        );
    }

    public function instructorConstraints()
    {
        return $this->hasMany(
            InstructorConstraint::class
        );
    }



}
