<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->input('sort_by', 'id');
        $sortDirection = $request->input('sort_dir', 'asc');

        $query = User::query();

        if ($sortField !== 'id') {
            $sortField = 'id';
            $sortDirection = 'asc';
        }

        $query->orderBy($sortField, $sortDirection);

        $users = $query->paginate(10);
        $roles = Role::all();
        return view('admin.users', compact('users', 'roles'));
    }


    public function show($slug)
    {
        $user = User::where('slug', $slug)->firstOrFail();
        return view('profil', compact('user'));
    }

    public function edit($slug)
    {
        $user = User::where('slug', $slug)->firstOrFail();
        return view('profile', compact('user'));
    }

    public function update(Request $request, $slug)
    {
        $user = User::where('slug', $slug)->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'current_password' => 'nullable|string|min:8',
            'new_password' => 'nullable|string|min:8',
            'pict' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('new_password')) {
            if (Hash::check($request->current_password, $user->password)) {
                $data['password'] = bcrypt($request->new_password);
            } else {
                return redirect()->back()->withErrors(['current_password' => 'Current password does not match.']);
            }
        }

        if ($request->hasFile('pict')) {
            if ($user->pict) {
                Storage::delete('public/' . $user->pict);
            }
            $imageName = time() . '.' . $request->pict->extension();
            $request->pict->storeAs('public/images', $imageName);
            $data['pict'] = 'images/' . $imageName;
        }

        $user->update($data);

        return redirect()->route('profile', $user->slug)->with('success', 'Profile updated successfully');
    }

    public function destroy($slug)
    {
        $user = User::where('slug', $slug)->firstOrFail();

        if ($user->pict) {
            Storage::delete('public/' . $user->pict);
        }

        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Profile deleted successfully');
    }

    public function updateRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::find($request->user_id);
        $user->syncRoles([$request->role]);

        return redirect()->back()->with('success', 'User role updated successfully.');
    }
}
