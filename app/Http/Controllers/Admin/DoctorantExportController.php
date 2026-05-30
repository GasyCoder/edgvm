<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DoctorantsExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class DoctorantExportController extends Controller
{
    public function export()
    {
        return Excel::download(new DoctorantsExport, 'doctorants_'.now()->format('Y-m-d').'.xlsx');
    }
}
