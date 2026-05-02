<?php

use App\Models\User;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;

new class extends Component {
    /** @var Collection<int, User> $users */
    public $users;
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public function mount() {
        $this->users =  User::with(['tenant'])
            ->orderBy($this->sortBy, $this->sortDirection)
            ->myTenant()
            ->get();
    }
    public function sort(string $column) {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }
};
?>

<div>
    <flux:table>
        <flux:table.columns>
            <flux:table.column>Name</flux:table.column>
            <flux:table.column>Email</flux:table.column>
            <flux:table.column sortable> Tenant</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'created_at'" :direction="$sortDirection" wire:click="sort('created_at')">Created At</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($this->users as $user)
            <flux:table.row :key="$user->id">
                <flux:table.cell class="flex items-center gap-3">
                    {{ $user->name }}
                </flux:table.cell>
                <flux:table.cell class="whitespace-nowrap">{{ $user->email }}</flux:table.cell>
                <flux:table.cell>
                    {{ '@'.$user->tenant->name }}
                </flux:table.cell>
                <flux:table.cell variant="strong">{{ $user->created_at }}</flux:table.cell>

            </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>