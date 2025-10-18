<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\StaffRepository;

class StaffService
{
    public function __construct(private StaffRepository $repo)
    {
    }

    public function list()
    {
        return $this->repo->all();
    }

    public function get(int $id): ?User
    {
        return $this->repo->find($id);
    }

    public function create(array $data): User
    {
        return $this->repo->create($data);
    }

    public function update(User $user, array $data): User
    {
        return $this->repo->update($user, $data);
    }

    public function delete(User $user): void
    {
        $this->repo->delete($user);
    }
}