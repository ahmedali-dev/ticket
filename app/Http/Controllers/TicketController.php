<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd(auth()->user()->type);
        $ticket = auth()->user()->type === 'admin' ?
            Ticket::latest()->paginate(10) :
            auth()->user()->tickets()->latest()->paginate(10);

        return view('ticket.dashborad', ['ticket' => $ticket]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ticket.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function formatFileSize(int|float $bytes, int $decimals = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        if ($bytes <= 0) {
            return '0 B';
        }

        $factor = floor(log($bytes, 1024));

        return sprintf(
            "%.{$decimals}f %s",
            $bytes / (1024 ** $factor),
            $units[$factor]
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',

            'images' => 'nullable|array|max:5',
            'images.*' => 'file|mimes:jpg,jpeg,png,webp,pdf,mp4,mp3,mov|max:5120',
        ]);
        $data['id'] = Ticket::count() + 1;
        $media = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $filename = Str::orderedUuid().time().'.'.$file->getClientOriginalExtension();
                $path = $file->storeAs('tickets', $filename, 'public');

                array_push($media, [
                    'uuid' => Str::orderedUuid(),
                    'ticket_id' => $data['id'],
                    'type' => $file->getMimeType(),
                    'path' => $path,
                    'size' => $this->formatFileSize($file->getSize()),
                ]);
            }
        }
        $request->user()->tickets()->create($data);
        Media::insert($media);
        // dd(Media::all());


        return to_route('ticket.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $ticket)
    {
        $isAdmin = $request->user()->type === 'admin';

        $t = $isAdmin
            ? Ticket::findOrFail($ticket)
            : $request->user()->tickets()->findOrFail($ticket);

        if ($isAdmin && $t->status !== Ticket::STATUS_IN_PROGRESS && $t->status !== Ticket::STATUS_COMPLETED) {

            $t->status = Ticket::STATUS_IN_PROGRESS;
            $t->save();
        }
        // dd(isset($t->ticketView));

        if (! $t->ticketView()
            ->where('user_id', auth()->id())
            ->where('created_at', '>=', Carbon::now()->subHour())
            ->exists()
        ) {
            $t->ticketView()->create([
                'user_id' => auth()->id(),
            ]);
        }
        // dd($t);

        // $t->media();
        return view('ticket.reply', [
            'ticket' => $t,
        ]);
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
    public function update(Request $request, Ticket $ticket)
    {
        if (auth()->user()->type !== 'admin') {
            return to_route('ticket.index');
        }

        $ticket->status = Ticket::STATUS_COMPLETED;
        $ticket->save();


        // dd($ticket);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function search(Request $request){
        $data = $request->validate([
            'search' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'status' => 'nullable|string',
        ]);

        $isAdmin = auth()->user()->type === 'admin';

        $query = $isAdmin ? Ticket::query() : auth()->user()->tickets();

        if (! empty($data['search'])) {
            $term = $data['search'];
            $query->where(function ($q) use ($term) {
                $q->where('id', 'like', "%{$term}%")
                    ->orWhere('title', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            });
        }

        if (! empty($data['date'])) {
            $query->whereDate('created_at', $data['date']);
        }

        if (! empty($data['status']) && strtolower($data['status']) !== 'all') {
            $query->where('status', $data['status']);
        }

        $ticket = $query->latest()->paginate(10)->withQueryString();

        return view('ticket.dashborad', [
            'ticket' => $ticket,
            'status_arr' => Ticket::select('status')->distinct()->pluck('status'),
        ]);

    }
}
