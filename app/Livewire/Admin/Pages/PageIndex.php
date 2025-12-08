<?php

namespace App\Livewire\Admin\Pages;

use App\Models\Page;
use Livewire\Component;
use Livewire\WithPagination;

class PageIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingPageDeletion = false;
    public $pageToDelete = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($pageId)
    {
        $this->pageToDelete = $pageId;
        $this->confirmingPageDeletion = true;
    }

    public function deletePage()
    {
        $page = Page::find($this->pageToDelete);
        
        if ($page) {
            $page->delete();
            session()->flash('success', 'Page supprimée avec succès.');
        }

        $this->confirmingPageDeletion = false;
        $this->pageToDelete = null;
    }

    public function toggleVisibility($pageId)
    {
        $page = Page::find($pageId);
        
        if ($page) {
            $page->visible = !$page->visible;
            $page->save();
            
            session()->flash('success', 'Visibilité mise à jour.');
        }
    }

    public function getPagesProperty()
    {
        return Page::with(['auteur', 'sections'])
            ->when($this->search, function($query) {
                $query->where('titre', 'like', '%' . $this->search . '%')
                      ->orWhere('slug', 'like', '%' . $this->search . '%');
            })
            ->orderBy('ordre')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.admin.pages.page-index');
    }
}