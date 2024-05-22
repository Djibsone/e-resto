<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlatRequest;
use App\Models\Plat;
use Illuminate\Http\Request;

class PlatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', []);
    }

    public function index(Request $request)
    {
        try {
            $plats = $this->searchPlat($request);
            return response()->json([
                'status' => 'success',
                'plats' => $plats,
            ]);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

    public function store(PlatRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $plat = Plat::create($validatedData);
            return response()->json([
                'status' => 'success',
                'massage' => 'Le plat a bien été enregistré.',
                'plat' => $plat,
            ]);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

    public function show(Plat $plat)
    {
        try {
            return response()->json([
                'status' => 'success',
                'plat' => $plat,
            ]);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

    public function update(PlatRequest $request, Plat $plat)
    {
        try {
            $validatedData = $request->validated();
            $plat->update($validatedData);
            $updatePlat = plat::find($plat->id);
            return response()->json([
                'status' => 'success',
                'massage' => 'Le plat a bien été modifié.',
                'plat' => $updatePlat,
            ]);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

    public function delete(Plat $plat)
    {
        try {
            $plat->delete();
            return response()->json([
                'status' => 'success',
                'massage' => 'Le plat a bien été supprimé.',
            ]);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

    public function searchPlat(Request $request)
    {
        try {
            $search = $request->input('search');

            if ($search) {
                $data = Plat::query()
                    ->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                $data = Plat::orderBy('created_at', 'desc')->get();
            }

            return $data;
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }
}
