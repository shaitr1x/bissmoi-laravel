<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Remerciement;

class RemerciementController extends Controller
{
    // Affiche la page publique des remerciements
    public function index()
    {
        $remerciements = Remerciement::all();
        $fondateurs = [];
        $path = storage_path('fondateurs.json');
        if (file_exists($path)) {
            $json = file_get_contents($path);
            $fondateurs = json_decode($json, true) ?: [];
        }
        return view('remerciements.index', compact('remerciements', 'fondateurs'));
    }

    // Affiche le panel admin
    public function admin()
    {
        $remerciements = Remerciement::all();
        $fondateurs = [];
        $path = storage_path('fondateurs.json');
        if (file_exists($path)) {
            $json = file_get_contents($path);
            $fondateurs = json_decode($json, true) ?: [];
        }
        return view('remerciements.admin', compact('remerciements', 'fondateurs'));
    }
    // Met à jour la liste des fondateurs
    public function updateFondateurs(Request $request)
    {
        $request->validate(['fondateurs' => 'required|string']);
        $fondateurs = array_filter(array_map('trim', explode("\n", $request->fondateurs)));
        file_put_contents(storage_path('fondateurs.json'), json_encode($fondateurs, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        return redirect()->route('remerciements.admin')->with('success', 'Fondateurs mis à jour !');
    }

    // Ajoute un nom
    public function store(Request $request)
    {
        $request->validate(['nom' => 'required|string|max:255']);
        Remerciement::create(['nom' => $request->nom]);
        return redirect()->route('remerciements.admin')->with('success', 'Ajouté !');
    }

    // Supprime un nom
    public function destroy($id)
    {
        Remerciement::destroy($id);
        return redirect()->route('remerciements.admin')->with('success', 'Supprimé !');
    }
}
