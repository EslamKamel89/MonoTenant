<?php

use App\Models\Article;
use Livewire\Component;

new class extends Component {
    public string $title = '';
    public string $content = '';
    public function add() {
        Article::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'content' => $this->content,
        ]);
        $this->dispatch('article-created');
        $this->modal('add-article')->close();
    }
};
?>

<div>
    <flux:modal.trigger name="add-article">
        <flux:button>Add Article</flux:button>
    </flux:modal.trigger>

    <flux:modal name="add-article" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Add Article</flux:heading>
                <flux:text class="mt-2">Share what you love with who you care about</flux:text>
            </div>
            <form wire:submit="add" class="space-y-6">
                <flux:input wire:model.defer="title" label="Title" placeholder="Title" />
                <flux:textarea wire:model.defer="content" label="Content" placeholder="Add what you think" />
                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">Save</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>