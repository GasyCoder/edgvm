<?php

namespace App\Livewire\Frontend;

use App\Models\Page;
use Livewire\Component;

class PageShow extends Component
{
    public Page $page;

    public function mount($slug)
    {
        $this->page = Page::with(['sections' => function($query) {
            $query->orderBy('ordre');
        }, 'sections.image'])
            ->where('slug', $slug)
            ->where('visible', true)
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.frontend.page-show')->layout('layouts.frontend', [
            'meta_title' => $this->page->titre,
        ]);
    }
}