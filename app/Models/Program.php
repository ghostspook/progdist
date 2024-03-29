<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
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
        'short_name',
        'start_date',
        'end_date',
        'class',
        'is_visible',

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function virtualMeetingLink()
    {
        return $this->hasOne(
            VirtualMeetingLink::class,
        );
    }

    public function links()
    {
        return $this->hasMany(ProgramVirtualMeetingLink::class);
    }
}

