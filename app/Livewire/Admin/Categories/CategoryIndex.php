<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Catégories')]
class CategoryIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $editing = false;
    public $categoryId = null;
    public $nom = '';
    public $description = '';
    public $couleur = '#3B82F6';
    public $visible = true;

    protected $rules = [
        'nom' => 'required|string|max:255',
        'description' => 'nullable|string',
        'couleur' => 'required|string',
        'visible' => 'boolean',
    ];

    public function create()
    {
        $this->reset(['editing', 'categoryId', 'nom', 'description', 'couleur', 'visible']);
        $this->editing = true;
        $this->couleur = '#3B82F6';
        $this->visible = true;
    }

    public function edit($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $this->categoryId = $category->id;
        $this->nom = $category->nom;
        $this->description = $category->description;
        $this->couleur = $category->couleur;
        $this->visible = $category->visible;
        $this->editing = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->categoryId) {
            // Update
            Category::find($this->categoryId)->update([
                'nom' => $this->nom,
                'description' => $this->description,
                'couleur' => $this->couleur,
                'visible' => $this->visible,
            ]);
            session()->flash('success', 'Catégorie mise à jour !');
        } else {
            // Create
            Category::create([
                'nom' => $this->nom,
                'description' => $this->description,
                'couleur' => $this->couleur,
                'visible' => $this->visible,
            ]);
            session()->flash('success', 'Catégorie créée !');
        }

        $this->reset(['editing', 'categoryId', 'nom', 'description', 'couleur', 'visible']);
    }

    public function delete($categoryId)
    {
        Category::find($categoryId)?->delete();
        session()->flash('success', 'Catégorie supprimée !');
    }

    public function render()
    {
        $categories = Category::when($this->search, function($q) {
            $q->where('nom', 'like', '%' . $this->search . '%');
        })->orderBy('nom')->paginate(10);

        return view('livewire.admin.categories.category-index', [
            'categories' => $categories,
        ]);
    }
}