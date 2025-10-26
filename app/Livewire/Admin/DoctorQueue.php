<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class DoctorQueue extends Component
{
    use WithPagination;

    public string $search = '';
    public ?string $status = null;

    protected $queryString = ['search', 'status'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $appointments = Appointment::with(['patient', 'user'])
            ->whereDate('scheduled_at', today())
            ->where('status', '!=', 'cancelled')
            ->whereNull('checked_out_at')
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->when($this->search, function ($q) {
                $q->where(function ($inner) {
                    $inner->whereHas('patient', function ($pq) {
                        $pq->where('child_name', 'like', '%'.$this->search.'%');
                    })->orWhere('reason', 'like', '%'.$this->search.'%');
                });
            })
            ->orderByRaw('CASE WHEN queue_position IS NULL THEN 1 ELSE 0 END, queue_position ASC')
            ->orderByRaw('CASE WHEN checked_in_at IS NULL THEN 1 ELSE 0 END, checked_in_at ASC')
            ->orderBy('scheduled_at', 'asc')
            ->paginate(20);

        return view('livewire.admin.doctor-queue', [
            'appointments' => $appointments,
        ])->layout('components.layouts.app', ['title' => __('Doctor Queue')]);
    }
}