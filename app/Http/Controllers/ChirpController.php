<?php

namespace App\Http\Controllers;

use App\Events\deleteChirp;
use App\Events\teste;
use App\Models\Chirp;
use App\Models\User;
use App\Notifications\NewChirp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Inertia\Inertia;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Chirp/index', 
        [
            'chirps' => Chirp::with('user:id,name')->latest()->get(),
            'userId' => Auth::id()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $chirp = $request->user()->chirps()->create($validated);

        broadcast(new teste($chirp->with('user:id,name')->orderBy('id', 'desc')->first()));
    }

    public function update(Request $request, Chirp $chirp)
    {
        $this->authorize('modify', $chirp);

        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $chirp->update($validated);
    }

    public function destroy(Chirp $chirp)
    {
        $this->authorize('modify', $chirp);

        $chirp->delete();

        event(new deleteChirp($chirp->id));
    }
}
