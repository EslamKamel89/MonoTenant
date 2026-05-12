<?php

use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination;

    public ?Tenant $editing = null;

    public string $sortBy = 'created_at';

    public string $sortDirection = 'desc';

    #[Validate('required|string|min:3|max:255')]
    public string $name = '';

    #[Validate('required|string|min:3|max:255|alpha_dash:ascii|unique:tenants,slug')]
    public string $slug = '';

    #[Validate('required|string|min:3|max:255|alpha_dash:ascii|unique:tenants,subdomain')]
    public string $subdomain = '';

    public function updatedName(): void {
        if (!$this->editing) {
            $slug = Str::slug($this->name);

            $this->slug = $slug;
            $this->subdomain = $slug;
        }
    }

    public function sort(string $column): void {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc'
                ? 'desc'
                : 'asc';

            return;
        }

        $this->sortBy = $column;
        $this->sortDirection = 'asc';
    }

    #[Computed]
    public function tenants() {
        return Tenant::query()
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);
    }

    public function create(): void {
        $this->resetForm();

        Flux::modal('tenant-create')->show();
    }

    public function store(): void {
        $validated = $this->validate();

        Tenant::create([
            ...$validated,
            'created_by' => Auth::id(),
            'database' => 'tenant_' . Str::random(10),
        ]);

        $this->resetForm();

        Flux::modal('tenant-create')->close();

        Flux::toast(
            heading: 'Tenant created',
            text: 'The tenant has been created successfully.',
            variant: 'success',
        );
    }

    public function edit(Tenant $tenant): void {
        $this->editing = $tenant;

        $this->name = $tenant->name;
        $this->slug = $tenant->slug;
        $this->subdomain = $tenant->subdomain;

        Flux::modal('tenant-edit')->show();
    }

    public function update(): void {
        if (!$this->editing) {
            return;
        }

        $validated = $this->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'slug' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'alpha_dash:ascii',
                'unique:tenants,slug,' . $this->editing->id,
            ],
            'subdomain' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'alpha_dash:ascii',
                'unique:tenants,subdomain,' . $this->editing->id,
            ],
        ]);

        $this->editing->update($validated);

        $this->resetForm();

        Flux::modal('tenant-edit')->close();

        Flux::toast(
            heading: 'Tenant updated',
            text: 'The tenant has been updated successfully.',
            variant: 'success',
        );
    }

    public function delete(Tenant $tenant): void {
        $tenant->delete();

        Flux::toast(
            heading: 'Tenant deleted',
            text: 'The tenant has been deleted successfully.',
            variant: 'danger',
        );
    }

    protected function resetForm(): void {
        $this->reset([
            'editing',
            'name',
            'slug',
            'subdomain',
        ]);
    }
};

?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">Tenants</flux:heading>

            <flux:text class="mt-2">
                Manage your platform tenants.
            </flux:text>
        </div>

        <flux:button
            variant="primary"
            icon="plus"
            wire:click="create">
            Create Tenant
        </flux:button>
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
                Actions
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
                    <div class="flex items-center justify-end gap-2">
                        <flux:button
                            size="sm"
                            variant="ghost"
                            icon="pencil"
                            wire:click="edit({{ $tenant->id }})">
                            Edit
                        </flux:button>

                        <flux:button
                            size="sm"
                            variant="danger"
                            icon="trash"
                            wire:click="delete({{ $tenant->id }})"
                            wire:confirm="Are you sure you want to delete this tenant?">
                            Delete
                        </flux:button>
                    </div>
                </flux:table.cell>
            </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    <flux:modal name="tenant-create" class="md:w-[500px]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Create Tenant</flux:heading>

                <flux:text class="mt-2">
                    Create a new tenant workspace.
                </flux:text>
            </div>

            <form wire:submit="store" class="space-y-5">
                <flux:input
                    wire:model.live.debounce.900="name"
                    label="Name"
                    placeholder="Acme Inc" />

                <flux:input
                    wire:model.live.debounce.900="slug"
                    label="Slug"
                    placeholder="acme-inc" />

                <flux:input
                    wire:model.live.debounce.900="subdomain"
                    label="Subdomain"
                    placeholder="acme-inc" />

                <div class="flex justify-end gap-2">
                    <flux:modal.close>
                        <flux:button variant="ghost">
                            Cancel
                        </flux:button>
                    </flux:modal.close>

                    <flux:button
                        type="submit"
                        variant="primary">
                        Create Tenant
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    <flux:modal name="tenant-edit" class="md:w-[500px]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Edit Tenant</flux:heading>

                <flux:text class="mt-2">
                    Update tenant information.
                </flux:text>
            </div>

            <form wire:submit="update" class="space-y-5">
                <flux:input
                    wire:model.live.debounce.900="name"
                    label="Name"
                    placeholder="Acme Inc" />

                <flux:input
                    wire:model.live.debounce.900="slug"
                    label="Slug"
                    placeholder="acme-inc" />

                <flux:input
                    wire:model.live.debounce.900="subdomain"
                    label="Subdomain"
                    placeholder="acme-inc" />

                <div class="flex justify-end gap-2">
                    <flux:modal.close>
                        <flux:button variant="ghost">
                            Cancel
                        </flux:button>
                    </flux:modal.close>

                    <flux:button
                        type="submit"
                        variant="primary">
                        Update Tenant
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>