<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\AccountActiveMail;
use App\Mail\ForgotPasswordMail;
use App\Models\Commande;
use App\Models\Restaurant;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', [
            'except' => ['login', 'register', 'verifyEmail', 'forgotPassword', 'resetPassword'],
        ]);
    }

    protected function getAuthenticatedUser()
    {
        return Auth::user();
    }

    public function index()
    {
        try {
            $users = User::with('restaurants', 'commandes')->get();
            return response()->json([
                'status' => 'success',
                'users' => $users,
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }

    public function typeOfUser($type_user)
    {
        if ($type_user) {
            $restaurants = Restaurant::whereHas('user', function ($query) {
                $query->where('id', $this->getAuthenticatedUser()->id);
            })
                ->with('plats')
                ->get();
            $plats = collect();

            foreach ($restaurants as $restaurant) {
                $plats = $plats->merge($restaurant->plats);
            }
            return $plats->isEmpty() ? 'Restaurant' : $plats;
        } else {
            $userCmdes = Commande::where('user_id', $this->getAuthenticatedUser()->id)
                ->with('user')
                ->get();
            return $userCmdes->isEmpty() ? 'User simple' : $userCmdes;
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->validated();
            $user = User::where('email', $credentials['email'])->first();

            if ($user) {
                if (is_null($user->email_verified_at)) {
                    return response()->json(
                        [
                            'status' => 'error',
                            'message' => 'Votre compte n\'est pas activé. Veuillez l\'activer en confirmant l\'e-mail qui vous a été envoyé.',
                        ],
                        401,
                    );
                }

                if ($token = Auth::attempt($credentials)) {
                    $user = Auth::user();
                    $userType = $this->typeOfUser($user->type_user);

                    return response()->json([
                        'status' => 'success',
                        'user' => $user,
                        'type_data' => $userType,
                        'authorisation' => [
                            'token' => $token,
                            'type' => 'Bearer',
                        ],
                    ]);
                } else {
                    return response()->json([
                            'status' => 'error',
                            'message' => 'L\'e-mail ou le mot de passe est incorrect.',
                        ],
                        401,
                    );
                }
            } else {
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => 'Aucun utilisateur trouvé avec cet e-mail.',
                    ],
                    404,
                );
            }
        } catch (\Exception $e) {
            return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['type_user'] = isset($validatedData['type_user']) ? 1 : 0;
            $validatedData['password'] = Hash::make($request->password);
            $token = Str::random(30);

            $user = User::create($validatedData);

            if ($user) {
                DB::table('password_reset_tokens')->insert([
                    'email' => $user->email,
                    'token' => $token,
                    'created_at' => now(),
                ]);

                $activationUrl = url('http://127.0.0.1:8000/api/v1/users/' . $token);
                $data = ['name' => $user->name, 'url' => $activationUrl];

                Mail::to($user->email)->send(new AccountActiveMail($data));

                return response()->json([
                    'status' => 'success',
                    'message' => 'Un e-mail de confirmation a été envoyé à votre adresse e-mail. Veuillez vérifier votre boîte de réception pour confirmer votre compte.',
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Erreur d\'inscription.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function logout()
    {
        try {
            Auth::logout();
            return response()->json([
                'status' => 'success',
                'message' => 'L\'utilisateur déconnecté avec succès.',
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }

    public function refresh()
    {
        try {
            return response()->json([
                'status' => 'success',
                'user' => $this->getAuthenticatedUser(),
                'authorisation' => [
                    'token' => Auth::refresh(),
                    'type' => 'Bearer',
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

    public function profile()
    {
        try {
            return response()->json([
                'user' => $this->getAuthenticatedUser(),
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }

    public function update(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'email' => 'email|unique:users,email',
            ]);
            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => 'Erreur de validation.',
                        'errors' => $validator->errors(),
                    ],
                    401,
                );
            }
            $request->user()->update($input);
            return response()->json([
                'status' => 'success',
                'message' => 'L\'utilisateur a bien été modifié.',
                'user' => $request->user(),
            ]);
        } catch (Exception $e) {
            return Response()->json($e);
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $validatedData = $request->validated();

            if (!Hash::check($this->getAuthenticatedUser()->password, $validatedData['old_password'])) {
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => 'L\'ancien mot de passe est incorrect.',
                    ],
                    401,
                );
            }
            User::whereId($this->getAuthenticatedUser()->id)->update([
                'password' => Hash::make($validatedData['new_password']),
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Le mot de passe a bien été modifié.',
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }

    public function verifyEmail($token)
    {
        try {
            $record = DB::table('password_reset_tokens')->where('token', $token)->first();

            if (!$record) {
                return response()->json(['status' => 'error', 'message' => 'Token invalide.'], 404);
            }

            $user = User::where('email', $record->email)->first();
            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'Utilisateur non trouvé.'], 404);
            }

            $user->email_verified_at = now();
            $user->save();

            DB::table('password_reset_tokens')->where('token', $token)->delete();

            return response()->json(['status' => 'success', 'message' => 'Compte activé avec succès.']);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $email = $validatedData['email'];
            $token = Str::random(30);

            $existingEmail = DB::table('password_reset_tokens')->where('email', $email)->first();

            if ($existingEmail) {
                DB::table('password_reset_tokens')
                    ->where('email', $email)
                    ->update([
                        'token' => $token,
                        'created_at' => now(),
                    ]);
            } else {
                DB::table('password_reset_tokens')->insert([
                    'email' => $email,
                    'token' => $token,
                    'created_at' => now(),
                ]);
            }
            $user = User::where('email', $email)->first();
            Mail::to($email)->send(new ForgotPasswordMail($user->name, $token));

            return response()->json([
                'status' => 'success',
                'message' => 'Un e-mail de réinitialisation a été envoyé à votre adresse e-mail.',
            ]);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

    public function resetPassword(ResetPasswordRequest $request, $token)
    {
        try {
            // Valider les données de la requête
            $validatedData = $request->validated();
            $npassword = $validatedData['password'];

            // Rechercher le token dans la table password_reset_tokens
            $reset = DB::table('password_reset_tokens')->where('token', $token)->first();

            // Si le token n'existe pas, retourner une erreur
            if (!$reset) {
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => 'Token est invalide.',
                    ],
                    401,
                );
            }

            // Rechercher l'utilisateur par email
            $user = User::where('email', $reset->email)->first();

            // Si l'utilisateur n'existe pas, retourner une erreur
            if (!$user) {
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => 'Utilisateur non trouvé.',
                    ],
                    404,
                );
            }

            // Mettre à jour le mot de passe de l'utilisateur
            $user->update(['password' => Hash::make($npassword)]);

            // Supprimer le token de la table password_reset_tokens
            DB::table('password_reset_tokens')
                ->where('email', $reset->email)
                ->delete();

            // Retourner une réponse de succès
            return response()->json([
                'status' => 'success',
                'message' => 'Réinitialisation du mot de passe avec succès.',
            ]);
        } catch (\Exception $e) {
            // Gérer les exceptions et retourner une réponse d'erreur
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function getResto()
    {
        $data = Restaurant::where('user_id', $this->getAuthenticatedUser()->id)->with('plats')->get();
        if($data->isNotEmpty())
        {
            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        } else
        {
            return response()->json([
                'message' => 'Pas de restaurant.'
            ]);
        }  
    }

    public function countCmdesUser(User $user){
        // $user = User::findOrFail($this->getAuthenticatedUser());
        $count = $user->commandes()->count();

        if ($count > 0) {
            return response()->json([
                'status' => 'success',
                'nbrCmd' => $count
            ]);
        } else {
            return response()->json(['message' => 'L\'utilisateur n\'a pas de commande en cours']);
        }
        
    }
}
