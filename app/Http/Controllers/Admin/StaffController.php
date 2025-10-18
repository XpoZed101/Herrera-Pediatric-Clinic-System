<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\StaffService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function __construct(private StaffService $service)
    {
    }

    public function index(): View
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $staff = $this->service->list();
        return view('admin.staff.index', compact('staff'));
    }

    public function create(): View
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        return view('admin.staff.create');
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $this->service->create($data);

        return redirect()->route('admin.staff.index')->with('status', 'Staff account created.');
    }

    public function edit(int $id): View
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $user = $this->service->get($id);
        abort_unless($user instanceof User, 404);

        return view('admin.staff.edit', compact('user'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $user = $this->service->get($id);
        abort_unless($user instanceof User, 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $this->service->update($user, $data);

        return redirect()->route('admin.staff.index')->with('status', 'Staff account updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $user = $this->service->get($id);
        abort_unless($user instanceof User, 404);

        $this->service->delete($user);

        return redirect()->route('admin.staff.index')->with('status', 'Staff account removed.');
    }
}