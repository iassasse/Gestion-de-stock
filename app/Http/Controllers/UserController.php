<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('role', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('users.index', compact('users', 'search'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:Magasinier,Chef Magasinier'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_active' => true,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        if ($user->is_super_chef_magasinier && Auth::id() !== $user->id) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot edit the protected Chef Magasinier account.');
        }

        if (!Auth::user()->canManage($user)) {
            return redirect()->route('users.index')
                ->with('error', 'You do not have permission to edit this user.');
        }

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->is_super_chef_magasinier) {
            if (Auth::id() !== $user->id) {
                return redirect()->route('users.index')
                    ->with('error', 'You cannot edit the protected Chef Magasinier account.');
            }
            if ($request->input('role') !== 'Chef Magasinier') {
                return back()->withErrors(['role' => 'You cannot change the role of the protected Chef Magasinier account.'])->withInput();
            }
            if (!$request->input('is_active') || $request->input('is_active') == '0') {
                return back()->withErrors(['is_active' => 'You cannot deactivate the protected Chef Magasinier account.'])->withInput();
            }
        }

        if (!Auth::user()->canManage($user)) {
            return redirect()->route('users.index')
                ->with('error', 'You do not have permission to edit this user.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'in:Magasinier,Chef Magasinier'],
            'is_active' => ['required', 'boolean'],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'is_active' => $validated['is_active'],
        ];

        if ($user->id === Auth::id() && !$validated['is_active']) {
            return back()->withErrors([
                'is_active' => 'You cannot deactivate your own account.',
            ]);
        }

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->is_super_chef_magasinier) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete the protected Chef Magasinier account.');
        }

        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        if (!Auth::user()->canManage($user)) {
            return redirect()->route('users.index')
                ->with('error', 'You do not have permission to delete this user.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->is_super_chef_magasinier) {
            return back()->with('error', 'You cannot deactivate the protected Chef Magasinier account.');
        }

        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot toggle your own active status.');
        }

        if (!Auth::user()->canManage($user)) {
            return back()->with('error', 'You do not have permission to deactivate this user.');
        }

        $user->update([
            'is_active' => !$user->is_active,
        ]);

        $status = $user->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "User account has been {$status} successfully.");
    }
}
