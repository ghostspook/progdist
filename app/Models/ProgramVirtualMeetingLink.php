<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramVirtualMeetingLink extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'program_id',
        'virtual_meeting_link_id',
    ];

    public function virtualMeetingLink()
    {
        return $this->belongsTo(VirtualMeetingLink::class);
    }
}
