<?php

namespace App\Livewire\Admin\Theses;

use App\Models\These;
use App\Models\Jury;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TheseJuryEdit extends Component
{
    public These $these;

    /** @var array<int, array{jury_id: int|null, role: string|null, ordre: int|null}> */
    public $juryRows = [];

    public $allJurys;

    protected function rules()
    {
        return [
            'juryRows.*.jury_id' => 'nullable|exists:jurys,id',
            'juryRows.*.role'    => 'nullable|string|max:150',
            'juryRows.*.ordre'   => 'nullable|integer|min:1|max:50',
        ];
    }

    protected $messages = [
        'juryRows.*.jury_id.exists' => 'Le membre sélectionné n’est pas valide.',
        'juryRows.*.ordre.integer'  => 'L’ordre doit être un nombre.',
    ];

    public function mount(These $these)
    {
        $this->these = $these->load('jurys', 'doctorant.user');

        $this->allJurys = Jury::orderBy('nom')->get();

        $this->juryRows = $this->these->jurys
            ->sortBy('pivot.ordre')
            ->map(fn ($membre) => [
                'jury_id' => $membre->id,
                'role'    => $membre->pivot->role,
                'ordre'   => $membre->pivot->ordre,
            ])
            ->values()
            ->all();

        if (count($this->juryRows) === 0) {
            $this->addRow();
        }
    }

    public function addRow()
    {
        $this->juryRows[] = [
            'jury_id' => null,
            'role'    => null,
            'ordre'   => null,
        ];
    }

    public function removeRow($index)
    {
        unset($this->juryRows[$index]);
        $this->juryRows = array_values($this->juryRows);

        if (count($this->juryRows) === 0) {
            $this->addRow();
        }
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            $syncData = collect($this->juryRows)
                ->filter(fn ($row) => !empty($row['jury_id']))
                ->mapWithKeys(fn ($row) => [
                    $row['jury_id'] => [
                        'role'  => $row['role'] ?: null,
                        'ordre' => $row['ordre'] ?: null,
                    ],
                ])
                ->toArray();

            $this->these->jurys()->sync($syncData);
        });

        session()->flash('success', 'Jury de la thèse mis à jour avec succès.');
        return redirect()->route('admin.theses.show', $this->these);
    }

    public function render()
    {
        return view('livewire.admin.theses.these-jury-edit');
    }
}