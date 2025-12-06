<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\DoctorantsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DoctorantImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|mimes:xlsx,xls|max:2048',
        ], [
            'import_file.required' => 'Veuillez sélectionner un fichier.',
            'import_file.mimes' => 'Le fichier doit être au format Excel (.xlsx ou .xls).',
            'import_file.max' => 'Le fichier ne doit pas dépasser 2 Mo.',
        ]);

        try {
            Excel::import(new DoctorantsImport, $request->file('import_file'));
            
            return redirect()->route('admin.doctorants.index')
                ->with('success', '✅ Doctorants importés avec succès !');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            
            foreach ($failures as $failure) {
                $errors[] = "Ligne {$failure->row()}: " . implode(', ', $failure->errors());
            }
            
            return redirect()->route('admin.doctorants.index')
                ->with('error', '❌ Erreurs de validation : ' . implode(' | ', array_slice($errors, 0, 3)));
        } catch (\Exception $e) {
            return redirect()->route('admin.doctorants.index')
                ->with('error', '❌ Erreur lors de l\'importation : ' . $e->getMessage());
        }
    }
}