<?php

namespace App\Http\Controllers;

use App\Models\Program;
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

    public function destroy(Request $request)
    {
        $input = $request->all();
        $id = $input['link_id'];

        $vml = VirtualMeetingLink::find($id);

        $vml->delete();

        $program_id = $input['link_program_id'];
        return redirect()->route('programs.edit', [ 'id' => $program_id]);
    }


    public function setDefaultLink($id)
    {

        $pvmls = ProgramVirtualMeetingLink::where('virtual_meeting_link_id',$id)->first();

        $program = Program::find($pvmls->program_id);
        $program->default_virtual_meeting_link_id = $id;

        $program->save();

        return redirect()->route('programs.edit', [ 'id' => $program->id]);
    }


    public function api_setDefaultLink($id)
    {

        $pvmls = ProgramVirtualMeetingLink::where('virtual_meeting_link_id',$id)->first();

        $program = Program::find($pvmls->program_id);
        $program->default_virtual_meeting_link_id = $id;

        $program->save();

        return response()->json([
            "status" => "success"
        ]);


    }


    public function addLinkForVirtualMeeting(Request $request)
    {

        $request->validate([
            'newVirtualMeetingLink.virtual_room_id' => 'required',
            'newVirtualMeetingLink.link' => 'required|url',
        ]);


     //   dd($request->newVirtualMeetingLink);
        $input = $request["newVirtualMeetingLink"];
        //    dd($input);
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

        $newLink = [ "virtual_meeting_link_id" => $vml->id ,
                    "link" => $input['link'],
        ];

        return response()->json($newLink);

    }
    public function getVirtualRoomByLinkId($id)
    {

        $vml = VirtualMeetingLink::where('id',$id)->with('virtualRoom')->get();
        $vr = [];

        foreach ($vml as $link ){

            $vr [] = [ "id" => $link->virtualRoom["id"],
                        "name"=>$link->virtualRoom["name"],
                        "mnemonic" => $link->virtualRoom["mnemonic"]
                    ];
        }
       // dd($vr);
        return response()->json($vr);
    }
}
