<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function user_profile(Request $request): View
    {
        $shop = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->id;
        $authenticatedUser = auth()->user()->nik;
        $employee = DB::table('v_employee')->where('nik', $authenticatedUser)->first();
         $birth_date = Carbon::parse($employee->birth_date);
        return view('profile.partials.update-profile-information-form', compact('employee', 'birth_date'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $request->validate([
            'shop_logo' => 'image|mimes:jpg,jpeg,png|max:5000'
        ]);
        $authenticatedUser = auth()->user()->id;
        $shop_logo_exist = DB::table('shop')->where('user_id', $authenticatedUser)->firstOrFail();

        DB::table('users')->where('id', $authenticatedUser)->update([
            'username' => $request->username,
            'email' => $request->email
        ]);

        if ($request->hasFile('shop_logo')) {
            $shop_logo = $request->file('shop_logo');
            $logoPath = $shop_logo->storeAs('shop_logo', uniqid() . '.' . $shop_logo->getClientOriginalExtension(), 'public');
            DB::table('shop')->where('user_id', $authenticatedUser)->update([
                'shop_name' => $request->shop_name,
                'owner_name' => $request->owner_name,
                'shop_logo' => $logoPath,
                'updated_at' => now(),
                'updated_by' => app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->shop_name

            ]);

            if ($shop_logo_exist->shop_logo) {
                $oldLogo = public_path('storage/' . $shop_logo_exist->shop_logo);
                if (file_exists($oldLogo)) {
                    unlink($oldLogo);
                }
            }
        } else {
            DB::table('shop')->where('user_id', $authenticatedUser)->update([
                'shop_name' => $request->shop_name,
                'owner_name' => $request->owner_name,
                'updated_at' => now(),
                'updated_by' => app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->shop_name

            ]);
        }

        session()->flash('message_success', 'Data pengguna berhasil disimpan!');
        return redirect()->route('profile_information');
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