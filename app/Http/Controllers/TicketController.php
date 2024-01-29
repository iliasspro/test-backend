<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:agent')->except(['index', 'show']);
        $this->middleware('role:supervisor')->only(['index', 'show']);
    }

    public function index()
    {
        return Ticket::all();
    }

    public function show($id)
    {
        $ticket = Ticket::find($id);

        if ($ticket) {
            return response()->json($ticket, 200);
        } else {
            return response()->json(['message' => 'Ticket not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'issue_details' => 'required|string',
            'call_id' => 'exists:calls,id',
        ]);

        $ticket = Ticket::create($request->all());
        return response()->json($ticket, 201);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'issue_details' => 'string',
            'call_id' => 'exists:calls,id',
        ]);

        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json(['message' => 'Ticket not found'], 404);
        }

        $this->authorize('update-ticket', $ticket);

        $ticket->update($request->all());

        return response()->json($ticket, 200);
    }

    public function destroy($id)
    {
        $ticket = Ticket::find($id);

        if ($ticket) {
            $this->authorize('delete-ticket', $ticket);

            $ticket->delete();
            return response()->json(null, 204);
        } else {
            return response()->json(['message' => 'Ticket not found'], 404);
        }
    }
}


