<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    /**
     * @OA\Post(
     *     path="/login",
     *     tags={"Projects"},
     *     summary="Login API",
     *     description="Login to get Access Token API",
     *     operationId="login",
     *     @OA\Parameter(
     *          name="email",
     *          description="email address",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="password",
     *          description="password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *       @OA\Response(
     *           response="200",
     *           description="Ok",
     *           @OA\JsonContent
     *           (example={
     *               "meta": {
     *                     "code":200,
     *                     "status": "success",
     *                     "message": "Authenticated"
     *                },
     *               "data": {
     *                      "acces_token": "01|xxxxACCESSTOKEN",
     *                      "token_type": "Bearer",
     *                       "user": {
     *                              "id":1,
     *                              "name": "Dr Hendra",
     *                              "email": "drhendra@gmail.com",
     *                              "role": "dokter",
     *                      }
     *                }
     *          }),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */



    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['email incorrect']
            ]);
        }

        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['password incorrect']
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;
        return ResponseFormatter::success([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user),
        ], 'Authenticated');
    }

    public function user(Request $request)
    {
        $user = $request->user();
        return ResponseFormatter::success([
            new UserResource($user),
        ], 'Success!');
    }

    /**
     * @OA\Post(
     *     path="/logout",
     *     tags={"Projects"},
     *     summary="Logout API",
     *     description="Logout to remove acces token API",
     *     operationId="logout",
     *       @OA\Response(
     *           response="200",
     *           description="Ok",
     *           @OA\JsonContent
     *           (example={
     *               "message": "Authenticated"
     *          }),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'logout successfully',
        ]);
    }
}
