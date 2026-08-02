<?php

namespace App\Http\Controllers;

use App\Events\SendMessage;
use App\Models\Ticket;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function store(Request $request, $ticket)
    {
        $validated = $request->validate([
            'description' => ['required', 'string', 'max:5000'],
        ]);
        $reply = '';
        if (auth()->user()->type == 'admin') {
            $reply = Ticket::find($ticket)->reply()->create([
                'user_id' => auth()->id(),
                'body' => $validated['description'],
            ]);

        } else {
            $reply = Ticket::where('user_id', auth()->user()->id)->find($ticket)->reply()->create([
                'user_id' => auth()->id(),
                'body' => $validated['description'],
            ]);

        }
        $reply->load('user');


        broadcast(new SendMessage($reply))->toOthers();
        if ($request->wantsJson()) {
            return response()->json($reply, 201);
        }

        return back()->with('success', 'Reply added.');
    }
}
