<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::orderBy('role')->orderBy('full_name')->get();

        return view('admin.settings.pengguna.index', compact('users'));
    }

    public function create()
    {
        return view('admin.settings.pengguna.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request, requirePassword: true);

        User::create([
            'username' => $data['username'],
            'full_name' => $data['full_name'],
            'role' => $data['role'],
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'password' => bcrypt($data['password']),
            'is_active' => true,
        ]);

        return redirect()->route('admin.settings.pengguna')->with('success', "Akun '{$data['full_name']}' berhasil dibuat.");
    }

    public function edit(User $user)
    {
        return view('admin.settings.pengguna.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validateData($request, requirePassword: false, ignoreId: $user->id);

        $user->username = $data['username'];
        $user->full_name = $data['full_name'];
        $user->role = $data['role'];
        $user->phone = $data['phone'] ?? null;
        $user->email = $data['email'] ?? null;

        if (! empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }

        $user->save();

        return redirect()->route('admin.settings.pengguna')->with('success', "Akun '{$user->full_name}' berhasil diperbarui.");
    }

    public function toggleActive(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->withErrors(['toggle' => 'Anda tidak bisa menonaktifkan akun Anda sendiri.']);
        }

        $user->update(['is_active' => ! $user->is_active]);
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Akun '{$user->full_name}' berhasil {$status}.");
    }

    private function validateData(Request $request, bool $requirePassword, ?int $ignoreId = null): array
    {
        return $request->validate([
            'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($ignoreId)],
            'full_name' => ['required', 'string', 'max:150'],
            'role' => ['required', Rule::in(['admin', 'mekanik'])],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:150'],
            'password' => [$requirePassword ? 'required' : 'nullable', 'string', 'min:6', 'confirmed'],
        ]);
    }
}
