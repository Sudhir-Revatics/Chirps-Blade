<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use App\Models\User;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ChirpController extends Controller
{

    public function index()
    {
        return view('chirps.index',[
            'chirps' => Chirp::with('user')->latest()->get(),
        ]);
    }

    public function store(Request $request){
        $validated = $request -> validate([
            'message' => 'required|max:255'
        ]);

        $request->user()->chirps()->create($validated);
        return redirect()->route('chirps.index');
    }

    public function edit(Chirp $chirp): View
    {
        Gate::authorize('update', $chirp);

        return view('chirps.edit', [
            'chirp' => $chirp,
        ]);
    }

    public function update(Request $request, Chirp $chirp): RedirectResponse{
        Gate::authorize('update', $chirp);
        $validated = $request -> validate([
            'message' => 'required|max:255'
        ]);
        $chirp->update($validated);
        return redirect()->route('chirps.index');
    }

    public function destroy(Chirp $chirp): RedirectResponse{
        Gate::authorize('delete', $chirp);
        $chirp->delete();
        return redirect()->route('chirps.index');
    }

}
