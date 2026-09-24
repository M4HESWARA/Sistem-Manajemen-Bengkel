<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label small fw-semibold">Username</label>
        <input type="text" name="username" class="form-control"
               value="{{ old('username', $user->username ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label small fw-semibold">Nama Lengkap</label>
        <input type="text" name="full_name" class="form-control"
               value="{{ old('full_name', $user->full_name ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label small fw-semibold">Role</label>
        <select name="role" class="form-select" required>
            <option value="admin" @selected(old('role', $user->role ?? '') === 'admin')>Admin</option>
            <option value="mekanik" @selected(old('role', $user->role ?? '') === 'mekanik')>Mekanik</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label small fw-semibold">No. HP (opsional)</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone ?? '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label small fw-semibold">Email (opsional)</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label small fw-semibold">
            Password @isset($user) <span class="text-muted fw-normal">(kosongkan jika tidak ingin mengubah)</span> @endisset
        </label>
        <input type="password" name="password" class="form-control" {{ isset($user) ? '' : 'required' }}>
    </div>
    <div class="col-md-6">
        <label class="form-label small fw-semibold">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="form-control" {{ isset($user) ? '' : 'required' }}>
    </div>
</div>
