<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstructorConstraint extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'from',
        'to',
        'constraint_type',
        'instructor_id',
        ];

    protected $casts = [
        'from' => 'datetime',
        'to' => 'datetime',
    ];

    public function instructor() {
        return $this->belongsTo(Instructor::class);
    }

}
