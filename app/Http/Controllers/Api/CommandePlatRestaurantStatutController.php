<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommandePlatRestaurantStatutRequest;
use App\Models\CommandePlatRestaurantStatut;
use Illuminate\Http\Request;

class CommandePlatRestaurantStatutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', []);
    }

    public function index()
    {
        try {
            $lignecommandes = CommandePlatRestaurantStatut::with('commande', 'plat', 'restaurant', 'statut')->get();
            return response()->json([
                'status' => 'success',
                'lignecommandes' => $lignecommandes,
            ]);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

    public function store(Request $request)
    {
        try {
            $lignecommandes = [];

            foreach ($request->all() as $lignecommandeData) {
                $lignecommande = CommandePlatRestaurantStatut::create($lignecommandeData);
                $lignecommandes[] = $lignecommande;
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Les lignes de commande ont bien été enregistrées.',
                'lignecommandes' => $lignecommandes,
            ]);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

    public function show(CommandePlatRestaurantStatut $lignecommande)
    {
        try {
            return response()->json([
                'status' => 'success',
                'lignecommande' => $lignecommande,
            ]);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

    public function update(CommandePlatRestaurantStatutRequest $request, CommandePlatRestaurantStatut $lignecommande)
    {
        try {
            $validatedData = $request->validated();
            $lignecommande->update($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'La ligne de commande a bien été modifiée.',
                'lignecommandes' => $lignecommande,
            ]);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

    public function delete(CommandePlatRestaurantStatut $lignecommande)
    {
        try {
            $lignecommande->delete();
            return response()->json([
                'status' => 'success',
                'massage' => 'La ligne de commande a bien été supprimée.',
            ]);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }
}
