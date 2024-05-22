<?php

namespace App\Http\Controllers\Api;

use App\Models\Commande;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommandeRequest;

class CommandeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', []);
    }

    public function index()
    {
        try {
            $commandes = Commande::with('ligneCommandes')->get();
            return response()->json([
                'status' => 'success',
                'commandes' => $commandes,
            ]);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

    public function store(CommandeRequest $request)
    {
        try {
            $validatedData = $request->validated();
            // $validatedData['ref_cmde'] = 'ref_' . Str::random(8);
            $commande = Commande::create($validatedData);

            $ref_cmde = 'ref_' . str_pad($commande->id, 3, '0', STR_PAD_LEFT);
            $commande->update(['ref_cmde' => $ref_cmde]);

            return response()->json([
                'status' => 'success',
                'massage' => 'La commande a bien été enregistrée.',
                'commande' => $commande,
            ]);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

    public function show($commande)
    {
        try {
            $commande = Commande::where('user_id', $commande)->with('ligneCommandes')->get();
            return response()->json([
                'status' => 'success',
                'commande' => $commande,
            ]);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

    public function update(CommandeRequest $request, Commande $commande)
    {
        try {
            $validatedData = $request->validated();
            $commande->update($validatedData);
            $updateCommande = Commande::find($commande->id);
            return response()->json([
                'status' => 'success',
                'massage' => 'La commande a bien été modifiée.',
                'commande' => $updateCommande,
            ]);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

    public function delete(Commande $commande)
    {
        try {
            $commande->delete();
            return response()->json([
                'status' => 'success',
                'massage' => 'La commande a bien été supprimée.',
            ]);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }
}
