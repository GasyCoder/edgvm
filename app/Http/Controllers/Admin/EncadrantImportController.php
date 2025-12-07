<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\EncadrantsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EncadrantImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls|max:2048',
        ]);

        try {
            Excel::import(new EncadrantsImport, $request->file('import_file'));

            return redirect()
                ->route('admin.encadrants.index')
                ->with('success', 'Encadrants importés avec succès !');
                
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Ligne {$failure->row()}: " . implode(', ', $failure->errors());
            }
            
            return redirect()
                ->route('admin.encadrants.index')
                ->with('error', 'Erreur d\'importation : ' . implode(' | ', $errorMessages));
                
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.encadrants.index')
                ->with('error', 'Erreur lors de l\'importation : ' . $e->getMessage());
        }
    }
}