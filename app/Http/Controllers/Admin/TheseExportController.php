<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ThesesExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class TheseExportController extends Controller
{
    public function export()
    {
        return Excel::download(new ThesesExport, 'theses_' . now()->format('Y-m-d_His') . '.xlsx');
    }
}