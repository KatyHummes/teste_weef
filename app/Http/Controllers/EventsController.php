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
        $evensts = Events::all();
        return Inertia::render('Dashboard', [
            'events' => $evensts
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $selectedDate = Carbon::parse($request->date)->format('Y-m-d');
        Events::create([
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
    }
}
