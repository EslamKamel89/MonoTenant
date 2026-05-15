<?php

use App\Models\Tenant;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination;
    public string $sortBy = 'created_at';

    public string $sortDirection = 'desc';

    #[Computed]
    public function tenants() {
        return Tenant::query()
            ->where('owner_id', auth()->id())
            ->with(['createdBy', 'owner'])
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);
    }
};

?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">My Tenants</flux:heading>

            <flux:text class="mt-2">
                Manage your platform tenants.
            </flux:text>
        </div>


    </div>

    <flux:table :paginate="$this->tenants">
        <flux:table.columns>
            <flux:table.column
                sortable
                :sorted="$sortBy === 'name'"
                :direction="$sortDirection"
                wire:click="sort('name')">
                Name
            </flux:table.column>

            <flux:table.column
                sortable
                :sorted="$sortBy === 'slug'"
                :direction="$sortDirection"
                wire:click="sort('slug')">
                Slug
            </flux:table.column>

            <flux:table.column
                sortable
                :sorted="$sortBy === 'subdomain'"
                :direction="$sortDirection"
                wire:click="sort('subdomain')">
                Subdomain
            </flux:table.column>

            <flux:table.column
                sortable
                :sorted="$sortBy === 'database'"
                :direction="$sortDirection"
                wire:click="sort('database')">
                Database
            </flux:table.column>

            <flux:table.column>
                Owner
            </flux:table.column>

            <flux:table.column>
                Created By
            </flux:table.column>


        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->tenants as $tenant)
            <flux:table.row :key="$tenant->id">
                <flux:table.cell variant="strong">
                    {{ $tenant->name }}
                </flux:table.cell>

                <flux:table.cell>
                    <flux:badge size="sm">
                        {{ $tenant->slug }}
                    </flux:badge>
                </flux:table.cell>

                <flux:table.cell>
                    {{ $tenant->subdomain }}
                </flux:table.cell>

                <flux:table.cell>
                    <code class="text-sm">
                        {{ $tenant->database }}
                    </code>
                </flux:table.cell>

                <flux:table.cell>
                    {{ $tenant->owner->name }}
                </flux:table.cell>

                <flux:table.cell>
                    {{ $tenant->createdBy->name }}
                </flux:table.cell>


            </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>


</div>