<?php

namespace App\Livewire\Admin\Tags;

use App\Models\Tag;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Tags')]
class TagIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $editing = false;
    public $tagId = null;
    public $nom = '';

    protected $rules = [
        'nom' => 'required|string|max:255',
    ];

    public function create()
    {
        $this->reset(['editing', 'tagId', 'nom']);
        $this->editing = true;
    }

    public function edit($tagId)
    {
        $tag = Tag::findOrFail($tagId);
        $this->tagId = $tag->id;
        $this->nom = $tag->nom;
        $this->editing = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->tagId) {
            Tag::find($this->tagId)->update(['nom' => $this->nom]);
            session()->flash('success', 'Tag mis à jour !');
        } else {
            Tag::create(['nom' => $this->nom]);
            session()->flash('success', 'Tag créé !');
        }

        $this->reset(['editing', 'tagId', 'nom']);
    }

    public function delete($tagId)
    {
        Tag::find($tagId)?->delete();
        session()->flash('success', 'Tag supprimé !');
    }

    public function render()
    {
        $tags = Tag::when($this->search, function($q) {
            $q->where('nom', 'like', '%' . $this->search . '%');
        })->withCount('actualites')->orderBy('nom')->paginate(15);

        return view('livewire.admin.tags.tag-index', [
            'tags' => $tags,
        ]);
    }
}