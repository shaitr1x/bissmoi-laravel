<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShippingSettingsController extends Controller
{
    public function index()
    {
        // Récupérer la valeur actuelle des frais de livraison depuis la table settings
        $shippingFee = $this->getShippingFee();
        
        return view('admin.settings.shipping', compact('shippingFee'));
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'shipping_fee' => 'required|numeric|min:0|max:999999'
        ], [
            'shipping_fee.required' => 'Les frais de livraison sont obligatoires.',
            'shipping_fee.numeric' => 'Les frais de livraison doivent être un nombre.',
            'shipping_fee.min' => 'Les frais de livraison ne peuvent pas être négatifs.',
            'shipping_fee.max' => 'Les frais de livraison sont trop élevés.'
        ]);
        
        try {
            // Mettre à jour ou créer l'enregistrement des frais de livraison
            DB::table('settings')->updateOrInsert(
                ['key' => 'shipping_fee'],
                ['value' => $request->shipping_fee, 'updated_at' => now()]
            );
            
            return redirect()->route('admin.settings.shipping')
                ->with('success', 'Frais de livraison mis à jour avec succès.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }
    
    private function getShippingFee()
    {
        $setting = DB::table('settings')->where('key', 'shipping_fee')->first();
        return $setting ? $setting->value : 3000; // Valeur par défaut : 3000 FCFA
    }
    
    public static function getGlobalShippingFee()
    {
        $setting = DB::table('settings')->where('key', 'shipping_fee')->first();
        return $setting ? (float) $setting->value : 3000.0;
    }
}
