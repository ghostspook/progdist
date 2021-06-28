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
        'area_id',
        'booking_date',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'booking_date' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function bookingSupportPersons()
    {
        return $this->hasMany(
            BookingSupportPerson::class
        );
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

    public function actions() {
        return $this->hasMany(BookingAction::class);
    }


    function getCoordinatingSupportPerson()
    {
        $coordPeople=[];

        foreach($this->bookingSupportPersons as $bsp)
        {
            if ($bsp->support_role == 1) //return $bsp;
                array_push($coordPeople,$bsp);
        }
        return $coordPeople;
       // dd($coordPeople);
        //return null;
    }

    function getAcademicSupportPerson()
    {
        $acadPeople = [];

        foreach($this->bookingSupportPersons as $bsp)
        {
            if ($bsp->support_role == 2)
                array_push($acadPeople,$bsp);

        }

        return $acadPeople;
    }

    function getTiSupportPerson()
    {
        $tiPeople = [];

        foreach($this->bookingSupportPersons as $bsp)
        {
            if ($bsp->support_role == 3)
                array_push($tiPeople,$bsp);

        }

        return $tiPeople;
    }

    function getSupportPersonsSummary()
    {
        $coord = $this->getCoordinatingSupportPerson();
        $acad = $this->getAcademicSupportPerson();
        $ti = $this->getTiSupportPerson();

        $coordText = "**Coord**: ";

        foreach($coord as $coordPerson)
        {
            $coordText = $coordText . "   " .  $coordPerson->supportPerson->mnemonic.", ".$coordPerson->supportTypeText() . "  ";
        }
        // if($coord){
        //     $coordText = "**Coord**: ".$coord->supportPerson->mnemonic.", ".$coord->supportTypeText();
        // }

        $acadText = " **Acad**: ";

        foreach($acad as $acadPerson)
        {
            $acadText = $acadText . " " . $acadPerson->supportPerson->mnemonic.", ".$acadPerson->supportTypeText() . "  " ;
        }

        $tiText = " **TI**: ";
        foreach($ti as $tiPerson)
        {
            $tiText = $tiText. " " . $tiPerson->supportPerson->mnemonic.", ".$tiPerson->supportTypeText() . "  " ;
        }

        return $coordText.$acadText.$tiText;
    }

}
