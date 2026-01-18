<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile based on their role.
     */
    public function show(Request $request): View
    {
        $user = $request->user();
        
        if ($user->isAdmin()) {
            $stats = [
                'users' => User::count(),
                'alats' => Alat::count(),
                'peminjamans' => Peminjaman::count(),
                'pengembalians' => Pengembalian::count(),
            ];
            return view('admin.profile-show', compact('stats'));
        } elseif ($user->isPetugas()) {
            $stats = [
                'peminjamans' => Peminjaman::where('status', 'menunggu')->count(),
                'pengembalians' => Peminjaman::where('status', 'disetujui')->whereDoesntHave('pengembalian')->count(),
            ];
            return view('petugas.profile-show', compact('stats'));
        } else {
            $stats = [
                'peminjamans' => Peminjaman::where('user_id', auth()->id())->count(),
                'pengembalians' => Pengembalian::whereHas('peminjaman', function($q) {
                    $q->where('user_id', auth()->id());
                })->count(),
                'alats' => Alat::count(),
                'menunggu' => Peminjaman::where('user_id', auth()->id())->where('status', 'menunggu')->count(),
                'disetujui' => Peminjaman::where('user_id', auth()->id())->where('status', 'disetujui')->count(),
            ];
            return view('peminjam.profile-show', compact('stats'));
        }
    }

    /**
     * Display the user's profile form based on their role.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        
        if ($user->isAdmin()) {
            return view('admin.profile-edit', ['user' => $user]);
        } elseif ($user->isPetugas()) {
            return view('petugas.profile-edit', ['user' => $user]);
        } else {
            return view('peminjam.profile-edit', ['user' => $user]);
        }
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        
        $request->user()->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // Update password if provided
        if (!empty($validated['password'])) {
            $request->user()->password = $validated['password'];
        }

        $request->user()->save();

        $message = !empty($validated['password']) 
            ? 'Profil dan password berhasil diperbarui!' 
            : 'Profil berhasil diperbarui!';

        return Redirect::route('profile.edit')->with('success', $message);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
