<?php

namespace App\Livewire\Admin\Pages;

use App\Models\Page;
use App\Models\MenuItem; // ✅ important pour gérer la visibilité dans les menus
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
            // Optionnel : supprimer aussi les entrées de menu associées
            MenuItem::where('page_id', $page->id)->delete();

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
            // On inverse la visibilité de la page
            $page->visible = ! $page->visible;
            $page->save();

            // ✅ Synchronisation avec tous les MenuItem liés à cette page
            // Si la page est masquée → les entrées de menu correspondantes sont masquées
            // Si la page est rendue visible → on ré-affiche les entrées de menu
            MenuItem::where('page_id', $page->id)
                ->update(['visible' => $page->visible]);

            session()->flash('success', 'Visibilité mise à jour.');
        }
    }

    public function getPagesProperty()
    {
        return Page::with(['auteur', 'sections'])
            ->when($this->search, function($query) {
                $query->where(function ($q) {
                    $q->where('titre', 'like', '%' . $this->search . '%')
                      ->orWhere('slug', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('ordre')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.admin.pages.page-index');
    }
}
