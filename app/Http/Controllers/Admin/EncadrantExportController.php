<?php

namespace App\Http\Controllers\Admin;

use App\Exports\EncadrantsExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class EncadrantExportController extends Controller
{
    public function export()
    {
        return Excel::download(new EncadrantsExport, 'encadrants_' . now()->format('Y-m-d_His') . '.xlsx');
    }
}