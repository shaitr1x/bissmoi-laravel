<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Cart;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API pour la navigation mobile
Route::middleware('auth')->group(function () {
    Route::get('/cart/count', function (Request $request) {
        $user = $request->user();
        if (!$user) {
            return response()->json(['count' => 0]);
        }
        
        $count = Cart::where('user_id', $user->id)->sum('quantity');
        return response()->json(['count' => $count]);
    });
});
