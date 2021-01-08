<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingSupportPerson extends Model
{
    use HasFactory;

      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'booking_id',
        'support_person_id',
        'support_role',
        'suppor_type',
    ];

    protected $table = 'booking_support_persons';

    public function supportPerson()
    {
        return $this->belongsTo(SupportPerson::class);
    }

    public function supportTypeText()
    {
        switch ($this->support_type)
        {
            case 1:
                return "Físico";
            case 2:
                return "Virtual";
        }
    }
}
