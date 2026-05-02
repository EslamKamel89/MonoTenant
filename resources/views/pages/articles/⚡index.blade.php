<?php

use App\Models\Article;
use App\Models\User;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;

new class extends Component {
    /** @var Collection<int, Article> $articles */
    public $articles;
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public function mount() {
        $this->articles =  Article::with(['user', 'tenant'])
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
            <flux:table.column>Title</flux:table.column>
            <flux:table.column> User</flux:table.column>
            <flux:table.column> Tenant</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'created_at'" :direction="$sortDirection" wire:click="sort('created_at')">Created At</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($this->articles as $article)
            <flux:table.row :key="$article->id">
                <flux:table.cell class="flex items-center gap-3">
                    {{ $article->title }}
                </flux:table.cell>
                <flux:table.cell class="whitespace-nowrap">{{ $article->user->name }}</flux:table.cell>
                <flux:table.cell>
                    {{ '@'.$article->tenant->name }}
                </flux:table.cell>
                <flux:table.cell variant="strong">{{ $article->created_at }}</flux:table.cell>

            </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>