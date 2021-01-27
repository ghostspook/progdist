<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VirtualMeetingLink extends Model
{
    use HasFactory;

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'virtual_room_id',
        'topic',
        'password',
        'waiting_room',
        'link',
    ];

    public function virtualRoom()
    {
        return $this->belongsTo(
            VirtualRoom::class,
        );
    }

    public function program()
    {
        return $this->belongsTo(
            Program::class,
        );
    }

}
