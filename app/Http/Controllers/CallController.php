<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Call;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class CallController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:agent')->except(['index', 'show']);
        $this->middleware('role:supervisor')->only(['index', 'show']);
    }

    public function index()
    {
        return Call::all();
    }

    public function show($id)
    {
        $call = Call::with('tickets')->find($id);
        if ($call) {
            return response()->json($call, 200);
        } else {
            return response()->json(['message' => 'Call not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'call_time' => 'required|date',
            'duration' => 'required|integer',
            'subject' => 'required|string',
        ]);

        $call = Call::create($request->all());
        return response()->json($call, 201);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'call_time' => 'date',
            'duration' => 'integer',
            'subject' => 'string',
        ]);

        $call = Call::find($id);

        if (!$call) {
            return response()->json(['message' => 'Call not found'], 404);
        }

        $this->authorize('update-call', $call);

        $call->update($request->all());

        return response()->json($call, 200);
    }

    public function destroy($id)
    {
        $call = Call::find($id);

        if ($call) {
            $this->authorize('delete-call', $call);

            $call->delete();
            return response()->json(null, 204);
        } else {
            return response()->json(['message' => 'Call not found'], 404);
        }
    }
}
