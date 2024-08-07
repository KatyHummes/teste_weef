<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventsRequest;
use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class EventsController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return Inertia::render('Dashboard', [
            'events' => $events
        ]);
    }

    public function store(EventsRequest $request)
    {
        // dd($request->all());
        $selectedDate = Carbon::parse($request->date)->format('Y-m-d');
        $event = Event::create([
            'name' => $request->name,
            'date' => $selectedDate,
            'responsible' => $request->responsible,
            'state' => $request->state, 
            'city' => $request->city,
            'neighborhood' => $request->neighborhood,
            'street' => $request->street,
            'number' => $request->number,
            'complement' => $request->complement,
            'phone' => $request->phone,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $photo) {
                $path = $photo->store('public/' . $event->id);
                $path = str_replace('public/', 'storage/', $path); 
    
                $event->images()->create([
                    'photo_path' => $path
                ]);
            }
        }
    }

    public function edit($id)
    {
        $event = Event::find($id)->load('images');
        return Inertia::render('EditEvent', [
            'event' => $event
        ]);
    }

    public function update(EventsRequest $request, $id)
    {
        
        // dd($request->all());
        $selectedDate = Carbon::parse($request->date)->format('Y-m-d');
        $event = Event::find($id);
        $event->update([
            'name' => $request->name,
            'date' => $selectedDate,
            'responsible' => $request->responsible,
            'state' => $request->state, 
            'city' => $request->city,
            'neighborhood' => $request->neighborhood,
            'street' => $request->street,
            'number' => $request->number,
            'complement' => $request->complement,
            'phone' => $request->phone,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $photo) {
                $path = $photo->store('public/' . $event->id);
                $path = str_replace('public/', 'storage/', $path); 
    
                $event->images()->create([
                    'photo_path' => $path
                ]);
            }
        }
    }

    public function destroy($id)
    {
        $event = Event::find($id);
        $event->delete();
    }
}
