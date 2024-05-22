<?php

namespace App\Http\Controllers\Api;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RestaurantRequest;

class RestaurantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index']]);
    }

    public function index(Request $request)
    {
        try {
            $restaurants = $this->searchRestaurant($request);
            return response()->json([
                'status' => 'success',
                'restaurants' => $restaurants,
            ]);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

    public function store(RestaurantRequest $request)
    // public function store(Request $request)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['user_id'] = auth()->user()->id;
            $restaurant = Restaurant::create($validatedData);
            return response()->json([
                'status' => 'success',
                'massage' => 'Le restaurant a bien été enregistré.',
                'restaurant' => $restaurant,
            ]);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

    public function show(Restaurant $restaurant)
    {
        try {
            return response()->json([
                'status' => 'success',
                'restaurant' => $restaurant,
            ]);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

    public function update(RestaurantRequest $request, Restaurant $restaurant)
    {
        try {
            $validatedData = $request->validated();
            if ($restaurant->user_id !== Auth::user()->id) {
                return response()->json([
                    'status' => 'error',
                    'massage' => 'Vous n\'êtes pas l\'auteur de ce restaurant.',
                ]);
            }
            $restaurant->update($validatedData);
            $updateRestaurant = Restaurant::find($restaurant->id);
            return response()->json([
                'status' => 'success',
                'massage' => 'Le restaurant a bien été modifié.',
                'restaurant' => $updateRestaurant,
            ]);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

    public function delete(Restaurant $restaurant)
    {
        try {
            if ($restaurant->user_id !== Auth::user()->id) {
                return response()->json([
                    'status' => 'error',
                    'massage' => 'Vous n\'êtes pas l\'auteur de ce restaurant.',
                ]);
            }
            $restaurant->delete();
            return response()->json([
                'status' => 'success',
                'massage' => 'Le restaurant a bien été supprimé.',
            ]);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

    public function searchRestaurant(Request $request)
    {
        try {
            $search = $request->input('search');

            if ($search) {
                $data = Restaurant::query()
                    ->where('name_restaut', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->with('plats', 'ligneCommandes')
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                $data = Restaurant::with('user', 'plats', 'ligneCommandes')->orderBy('created_at', 'desc')->get();
            }

            return $data;
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }
}
