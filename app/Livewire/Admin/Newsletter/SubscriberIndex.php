<?php

namespace App\Livewire\Admin\Newsletter;

use App\Exports\NewsletterSubscribersExport;
use App\Imports\NewsletterSubscribersImport;
use App\Models\NewsletterSubscriber;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Maatwebsite\Excel\Facades\Excel;

#[Title('Abonnés Newsletter')]
class SubscriberIndex extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public string $filterType = 'all';
    public string $filterActif = 'all';

    // modal create/edit
    public bool $editing = false;
    public ?int $subscriberId = null;
    public string $email = '';
    public string $nom = '';
    public string $type = 'autre';
    public bool $actif = true;
    public $subscriberCreatedAt = null;

    // Import
    public $importFile = null;

    protected function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                Rule::unique('newsletter_subscribers', 'email')->ignore($this->subscriberId),
            ],
            'nom'   => ['nullable', 'string', 'max:255'],
            'type'  => ['required', Rule::in(['doctorant', 'encadrant', 'autre'])],
            'actif' => ['boolean'],
        ];
    }

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterType(): void { $this->resetPage(); }
    public function updatingFilterActif(): void { $this->resetPage(); }

    public function create(): void
    {
        $this->resetForm();
        $this->editing = true;
    }

    public function edit(int $id): void
    {
        $s = NewsletterSubscriber::findOrFail($id);

        $this->subscriberId = $s->id;
        $this->email = $s->email;
        $this->nom = $s->nom ?? '';
        $this->type = $s->type;
        $this->actif = (bool) $s->actif;
        $this->subscriberCreatedAt = $s->created_at;

        $this->editing = true;
    }

    public function save(): void
    {
        $this->validate();

        if ($this->subscriberId) {
            NewsletterSubscriber::whereKey($this->subscriberId)->update([
                'email' => strtolower(trim($this->email)),
                'nom'   => $this->nom ?: null,
                'type'  => $this->type,
                'actif' => $this->actif,
            ]);
            session()->flash('success', 'Abonné mis à jour avec succès.');
        } else {
            NewsletterSubscriber::create([
                'email' => strtolower(trim($this->email)),
                'nom'   => $this->nom ?: null,
                'type'  => $this->type,
                'actif' => $this->actif,
                // token + abonne_le sont auto via model (creating)
            ]);
            session()->flash('success', 'Abonné créé avec succès.');
        }

        $this->editing = false;
        $this->resetForm();
    }

    public function toggleActif(int $id): void
    {
        $s = NewsletterSubscriber::find($id);
        if (!$s) return;

        $new = !$s->actif;

        $s->update([
            'actif' => $new,
            'desabonne_le' => $new ? null : now(),
            'abonne_le' => $s->abonne_le ?? now(),
        ]);

        session()->flash('success', 'Statut mis à jour.');
    }

    public function delete(int $id): void
    {
        NewsletterSubscriber::whereKey($id)->delete();
        session()->flash('success', 'Abonné supprimé.');
    }

    // EXPORT
    public function exportExcel()
    {
        $fileName = 'newsletter_subscribers_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(
            new NewsletterSubscribersExport($this->search, $this->filterType, $this->filterActif),
            $fileName
        );
    }

    // IMPORT
    public function importExcel(): void
    {
        $this->validate([
            'importFile' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120'], // 5MB
        ]);

        Excel::import(new NewsletterSubscribersImport(), $this->importFile);

        $this->importFile = null;
        session()->flash('success', 'Import terminé avec succès.');
    }

    private function resetForm(): void
    {
        $this->reset(['subscriberId', 'email', 'nom', 'type', 'actif', 'subscriberCreatedAt']);
        $this->type = 'autre';
        $this->actif = true;
    }


    public function clearImportFile(): void
    {
        $this->importFile = null;
        $this->resetErrorBag('importFile');
    }


    public function updatedImportFile(): void
    {
        $this->validateOnly('importFile', [
            'importFile' => ['nullable', 'file', 'mimes:xlsx,xls,csv', 'max:5120'],
        ]);
    }

    public function render()
    {
        $query = NewsletterSubscriber::query();

        if ($this->search !== '') {
            $s = trim($this->search);
            $query->where(function ($q) use ($s) {
                $q->where('email', 'like', "%{$s}%")
                  ->orWhere('nom', 'like', "%{$s}%");
            });
        }

        if ($this->filterType !== 'all') {
            $query->where('type', $this->filterType);
        }

        if ($this->filterActif === 'actif') {
            $query->where('actif', true);
        } elseif ($this->filterActif === 'inactif') {
            $query->where('actif', false);
        }

        $subscribers = $query->orderByDesc('created_at')->paginate(20);

        $stats = [
            'total'      => NewsletterSubscriber::count(),
            'actifs'     => NewsletterSubscriber::where('actif', true)->count(),
            'inactifs'   => NewsletterSubscriber::where('actif', false)->count(),
            'doctorants' => NewsletterSubscriber::where('type', 'doctorant')->count(),
            'encadrants' => NewsletterSubscriber::where('type', 'encadrant')->count(),
        ];

        return view('livewire.admin.newsletter.subscriber-index', compact('subscribers', 'stats'));
    }
}