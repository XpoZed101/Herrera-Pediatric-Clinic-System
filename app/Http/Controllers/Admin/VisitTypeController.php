<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisitType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class VisitTypeController extends Controller
{
    public function index(): View
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);
        $types = VisitType::orderBy('name')->paginate(15);
        return view('admin.visit-types.index', compact('types'));
    }

    public function create(): View
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);
        return view('admin.visit-types.create');
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:64', 'unique:visit_types,slug'],
            'amount' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable'],
            'description' => ['nullable', 'string'],
        ]);

        $slug = $validated['slug'] ?? Str::slug($validated['name'], '_');
        $amountCents = (int) round(((float) $validated['amount']) * 100);

        VisitType::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'amount_cents' => $amountCents,
            'is_active' => !empty($validated['is_active']),
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.visit-types.index')->with('status', 'Visit type created.');
    }

    public function edit(VisitType $visitType): View
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);
        return view('admin.visit-types.edit', compact('visitType'));
    }

    public function update(Request $request, VisitType $visitType): RedirectResponse
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:64', 'unique:visit_types,slug,' . $visitType->id],
            'amount' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable'],
            'description' => ['nullable', 'string'],
        ]);

        $slug = $validated['slug'] ?? Str::slug($validated['name'], '_');
        $amountCents = (int) round(((float) $validated['amount']) * 100);

        $visitType->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'amount_cents' => $amountCents,
            'is_active' => !empty($validated['is_active']),
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.visit-types.index')->with('status', 'Visit type updated.');
    }

    public function destroy(VisitType $visitType): RedirectResponse
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);
        $visitType->delete();
        return redirect()->route('admin.visit-types.index')->with('status', 'Visit type deleted.');
    }
}