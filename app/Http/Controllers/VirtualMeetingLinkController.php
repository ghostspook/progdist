<?php

namespace App\Http\Controllers;

use App\Models\ProgramVirtualMeetingLink;
use App\Models\VirtualMeetingLink;
use Illuminate\Http\Request;

class VirtualMeetingLinkController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'virtual_room_id' => 'required',
            'link' => 'required|url',
        ]);

        $input = $request->all();

        $vml = VirtualMeetingLink::create([
            'virtual_room_id' => $input['virtual_room_id'],
            'password' => $input['password'],
            'waiting_room' => $input['waiting_room'],
            'link' => $input['link'],
        ]);

        ProgramVirtualMeetingLink::create([
            'program_id' => $input['program_id'],
            'virtual_meeting_link_id' => $vml->id,
        ]);

        return redirect()->route('programs.edit', [ 'id' => $input['program_id']]);
    }

    public function destroy($id)
    {
        $vml = VirtualMeetingLink::find($id);
        $pvmls = $vml->programVirtualMeetingLinks;
        $program_id = 0;
        if ($pvmls->count() > 0)
        {
            $program_id = $pvmls[0]->id;
        }

        $vml->delete();

        return redirect()->route('programs.edit', [ 'id' => $program_id]);
    }

}
