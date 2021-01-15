<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstructorArea extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'instructor_id',
        'area_id',
    ];

    public function instructor() {
        return $this->belongsTo(Instructor::class);
    }

    public function area() {
        return $this->belongsTo(Area::class);
    }
}
