<?php

namespace App\Http\Controllers;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd(auth()->user()->type);
        $admin = auth()->user()->type === "admin" ? new Ticket() : auth()->user()->tickets();
        // $query = Ticket::query();

        // dd($admin->get());
        return view("ticket.dashborad");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("ticket.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',

            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
        if ($request->hasFile("images")) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('tickets', 'public');
                // dump($path);

            }
        }

        $data['id'] = Ticket::count() + 1;


        $request->user()->tickets()->create($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
