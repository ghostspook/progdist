<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportPersonConstraint extends Model
{
    use HasFactory;

    protected $fillable = [
        'from',
        'to',
        'constraint_type',
        'support_person_id',
        ];

    protected $casts = [
        'from' => 'datetime',
        'to' => 'datetime',
    ];

    public function supportPerson() {
        return $this->belongsTo(SupportPerson::class);
    }

}
