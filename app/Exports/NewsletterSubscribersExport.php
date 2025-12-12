<?php

namespace App\Exports;

use App\Models\NewsletterSubscriber;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class NewsletterSubscribersExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(
        public string $search = '',
        public string $filterType = 'all',
        public string $filterActif = 'all',
    ) {}

    public function query()
    {
        $query = NewsletterSubscriber::query();

        if ($this->search !== '') {
            $s = trim($this->search);
            $query->where(function (Builder $q) use ($s) {
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

        return $query->orderByDesc('created_at');
    }

    public function headings(): array
    {
        return [
            'email',
            'nom',
            'type',
            'actif',
            'abonne_le',
            'desabonne_le',
            'created_at',
            'updated_at',
        ];
    }

    public function map($row): array
    {
        return [
            $row->email,
            $row->nom,
            $row->type,
            $row->actif ? 1 : 0,
            optional($row->abonne_le)->format('Y-m-d H:i:s'),
            optional($row->desabonne_le)->format('Y-m-d H:i:s'),
            optional($row->created_at)->format('Y-m-d H:i:s'),
            optional($row->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
