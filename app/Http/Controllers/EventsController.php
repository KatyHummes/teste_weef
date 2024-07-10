<?php

namespace App\Http\Controllers;

use App\Models\Events;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class EventsController extends Controller
{
    public function index()
    {
        $events = Events::all();
        return Inertia::render('Dashboard', [
            'events' => $events
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $selectedDate = Carbon::parse($request->date)->format('Y-m-d');
        $event = Events::create([
            'name' => $request->name,
            'date' => $selectedDate,
            'responsible' => $request->responsible,
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
                $path = str_replace('public/', '', $path);

                $event->images()->create([
                    'photo_path' => $path
                ]);
            };
        }
    }

    public function edit($id)
    {
        $event = Events::find($id);
        return Inertia::render('EditEvent', [
            'event' => $event
        ]);
    }

    public function update(Request $request, $id)
    {
        $selectedDate = Carbon::parse($request->date)->format('Y-m-d');
        $event = Events::find($id);
        $event->update([
            'name' => $request->name,
            'date' => $selectedDate,
            'responsible' => $request->responsible,
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
                $path = str_replace('public/', '', $path);

                $event->images()->create([
                    'photo_path' => $path
                ]);
            };
        }
    }

    public function destroy($id)
    {
        $event = Events::find($id);
        $event->delete();
    }
}
