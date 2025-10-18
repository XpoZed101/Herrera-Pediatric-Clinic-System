<?php

namespace App\Repositories;

use App\Models\User;

class StaffRepository
{
    public function all()
    {
        return User::query()
            ->where('role', 'staff')
            ->orderByDesc('id')
            ->paginate(15);
    }

    public function find(int $id): ?User
    {
        return User::query()->where('role', 'staff')->find($id);
    }

    public function create(array $data): User
    {
        $data['role'] = 'staff';
        $data['password'] = $data['password'] ?? bcrypt(str()->random(12));
        return User::query()->create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->fill($data);
        if (!empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }
        $user->save();
        return $user;
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}