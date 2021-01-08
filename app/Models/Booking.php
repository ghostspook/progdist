<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'program_id',
        'instructor_id',
        'virtual_meeting_link_id',
        'physical_room_id',
        'topic',
        'date',
        'start_time',
        'end_time',
    ];

       /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    // protected $casts = [
    //     'date' => 'datetime',

    // ];

    public function supportPersons()
    {
        return $this->belongsToMany(
            SupportPerson::class,
            'booking_support_persons',
            'booking_id',

        )->withPivot('support_role', 'support_type');
    }

    public function virtualMeetingLink()
    {
        return $this->belongsTo(
            VirtualMeetingLink::class,
        );
    }

    public function area()
    {
        return $this->belongsTo(
            Area::class,

        );

    }

    public function instructor()
    {
        return $this->belongsTo(
            Instructor::class,

        );

    }

    public function program()
    {
        return $this->belongsTo(
            Program::class,
        );

    }

    public function physicalRoom()
    {
        return $this->belongsTo(
            PhysicalRoom::class,

        );

    }



}
