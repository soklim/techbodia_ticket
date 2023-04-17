<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Hash;
use Carbon\Carbon;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   
    public function Create_Ticket_API(Request $request)
    {
        try{
            $request->validate([
                'summary' => 'required',               
                'description' => 'required',
                'ticket_type_id' => 'required',
                'severity' => 'required',
                'priority' => 'required',
            ]);

            $current_date_time = Carbon::now()->toDateTimeString();
            $input['summary'] = $request->summary;
            $input['description'] = $request->description;           
            $input['status_id'] = 1;
            $input['ticket_type_id'] = $request->ticket_type_id;
            $input['created_by'] = Auth::user()->id;
            $input['created_date'] = $current_date_time;
            $input['severity'] = $request->severity;
            $input['priority'] = $request->priority;

            Ticket::create($input);
            return Response()->json(array(
                'code' => 0,
                'message'=>"Created Successful"
            ));
        }
        catch (\Exception $e) {
            return Response()->json(array(
                'code' => 1,
                'message'=>$e->getMessage()
            ));
        }
        
    }
   
}
