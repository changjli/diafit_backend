<?php

use App\Http\Controllers\CartApiController;
use App\Http\Controllers\ExerciseApiController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\FoodApiController;
use App\Http\Controllers\GlucoseApiController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NutritionApiController;
use App\Http\Controllers\NutritionController;
use App\Http\Controllers\NutritionReportController;
use App\Http\Controllers\OrderApiController;
use App\Http\Controllers\PasswordResetController;
use Illuminate\Http\Request;
use App\Http\Controllers\UserApi;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserApiController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RegisterController2;
use App\Http\Controllers\TransactionDetailApiController;
use App\Http\Controllers\TransactionHeaderApiController;
use App\Http\Controllers\TransactionHeaderController;
use App\Models\Exercise;
use App\Models\Menu;
use App\Models\NutritionReport;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('/hello', function () {
//     return response()->json(['hello' => 'world']);
// });

// *authorization 
// register
Route::post('/register', [RegisterController::class, 'register']);

// login 
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/hello', function () {
        return response()->json(['hello' => 'world']);
    });

    // logout 
    Route::post('/logout', [LoginController::class, 'logout']);

    // Route::resource('/profile', UserApiController::class);

    // *account 
    // show all users
    Route::get('/profile', [UserApiController::class, 'index']);

    // show profile 
    Route::get('/profile/{user:id}', [UserApiController::class, 'show']);

    // update profile 
    Route::put('/profile/{user:id}', [UserApiController::class, 'update']);

    // delete profile 
    Route::delete('/profile/{user:id}', [UserApiController::class, 'destroy']);

    // Route::resource('/order', OrderApiController::class);

    // berubah 
    // *order
    // show all orders
    // Route::get('/order', [OrderApiController::class, 'index']);

    // // create order
    // Route::post('/order', [OrderApiController::class, 'store']);

    // // show order 
    // Route::get('/order/{order:id}', [OrderApiController::class, 'show']);

    // // update order 
    // // Route::put('/order/{order:id}', [OrderApiController::class, 'update']);

    // // delete order
    // Route::delete('/order/{order:id}', [OrderApiController::class, 'destroy']);

    // order payment 

    // *food and menu
    // show all food grouped by week 
    Route::get('/food', [FoodApiController::class, 'index']);

    // show all food grouped by date
    Route::get('/food/menu', [FoodApiController::class, 'menu']);

    // show food detail 
    Route::get('/food/{food:id}', [FoodApiController::class, 'show']);

    // store food 
    Route::post('/food', [FoodApiController::class, 'store']);

    // update food
    Route::put('/food/{food:id}', [FoodApiController::class, 'update']);

    // delete food 
    Route::delete('/food/{food:id}', [FoodApiController::class, 'destroy']);

    // jadi cara kerja transactionya tuh buat transaction header dulu 
    // tambahin transaction detail 
    // update transaction header 
    // satu objek tapi 2 db 

    ///////////////////////////////////////////////////////////////////////////////////////////

    // cara kerja transaction yang baru

    // check cart 

    // #1
    // buat transaction header 
    // get all cart 
    // convert all cart to transaction_detail (store)
    // update transaction header price

    // roll back 

    # 2
    // update transaction header payment 
    // remove all cart 

    // tapi setelah dipikir2 digabung aja deh transaction di backendnya 

    #1
    Route::get('/transaction/process', [TransactionHeaderApiController
    ::class, 'proccess']);

    #2
    Route::put('/transaction/{id}', [TransactionHeaderApiController::class, 'update']);

    ///////////////////////////////////////////////////////////////////////////////////////////

    // ini yang baru tapi belom digabung 
    // create transaction
    // ini buat framenya dulu 
    // Route::post('/transaction', [TransactionHeaderApiController::class, 'store']);

    // convert cart to transaction_detail
    // Route::post('/transaction/detail', [TransactionDetailApiController::class, 'store']);

    // yang masih pake TH0001 di paling bawah

    ///////////////////////////////////////////////////////////////////////////////////////////

    // *transaction 
    // transaction header 
    // show all transaction by user (order history)
    Route::get('/transaction/history', [TransactionHeaderApiController::class, 'index']);

    Route::get('/transaction/active', [TransactionHeaderApiController::class, 'active']);

    // show transaction detail (pake show all transaction detail)

    // transaction detail
    // show all transaction detail by transaction_id (order detail)
    Route::get('/transaction/detail', [TransactionDetailApiController::class, 'index']);

    // delete transaction 
    Route::delete('/transaction/{transactionHeader:id}', [TransactionHeaderApiController::class, 'destroy']);

    // cart new 
    // show all cart by user 
    Route::get('/cart', [CartApiController::class, 'index']);

    // store cart 
    Route::post('/cart', [CartApiController::class, 'store']);

    Route::get('/cart/{food_id}', [CartApiController::class, 'show']);

    // update cart 
    Route::put('/cart/{food_id}', [CartApiController::class, 'update']);

    Route::delete('/cart/{food_id}', [CartApiController::class, 'destroy']);

    // delete cart 

    // cart old 
    // create transaction
    // ini buat framenya dulu 
    // Route::post('/transaction', [TransactionHeaderApiController::class, 'store']);

    // show all cart by user where transaction_id = 'TH0001' 
    // Route::get('/cart', [TransactionDetailApiController::class, 'cart']);

    // store cart / transaction_detail 
    // kalo mau jadi cart set transaction_id to 'TH0001', kalo mau jadi transaction_detail set transaction_id ke headernya 
    // bisa query pake transaction_id doang atau pake user_id dan food_id
    // migrationya gabisa, jadinya cart transaction_id == 'TH0001' aja (punya admin)
    // jadinya admin harus buat transaction_header TH0001 dulu 

    // store cart 
    // butuh transaction_id sama food_id lewat request 
    // Route::post('/cart', [TransactionDetailApiController::class, 'store']);

    // cart detail
    // show cart by transaction_id = TH0001, food_id and user_id
    // Route::get('/cart/{food_id}', [TransactionDetailApiController::class, 'showCart']);

    // update cart 
    // transaction_detail gabisa diupdate/delete solnya udah beli 
    // Route::put('/cart/{food_id}', [TransactionDetailApiController::class, 'update']);

    // update all (checkout)
    // cuma update transaction_id 
    // Route::put('/cart', [TransactionDetailApiController::class, 'updateAll']);

    // delete cart 

    // total price buat update transaction header
    // Route::get('/transaction/price', [TransactionDetailApiController::class, 'getPrice']);

    // update transaction header
    // kalo detailnya udah dimasukkin ke frame, paymentnya dihitung 
    // Route::put('/transaction/{id}', [TransactionHeaderApiController::class, 'update']);
    // yang diatas pake get ?, yang ini pake slug, sama aja sih seharusnya 



    // show transaction detail gabutuh 

    // *nutrition tracker 

    // nutrition report
    // gaada create update delete karena query dari nutrition record doang 

    // show all nutrition reports pake group by kayaknya 
    // Route::get('/nutrition/report/all', [NutritionApiController::class, 'reports']);
    // jadi kalo ini tunjukkin tanggal mana aja yang ada reportnya 

    // show nutritoin reports by date range
    // ex http://127.0.0.1:8000/api/nutrition/report?start_date=2023-05-10&end_date=2023-06-10
    // Route::get('/nutrition/reports', [NutritionApiController::class, 'reports']);

    // hitung calories consumed by date range group by date 
    // ex http://127.0.0.1:8000/api/nutrition/report?start_date=2023-05-10&end_date=2023-06-10
    Route::get('/nutrition/report/summary', [NutritionApiController::class, 'summary']);

    // show nutrition report by date 
    // ex http://127.0.0.1:8000/api/nutrition/report?date=2023-05-10
    Route::get('/nutrition/report', [NutritionApiController::class, 'report']);
    // kalo ini tunjukkin semua record di tanggal yang ada reportnya 

    // nutrition record
    // show all record 
    Route::get('/nutrition', [NutritionApiController::class, 'index']);

    // create nutrition record
    Route::post('/nutrition', [NutritionApiController::class, 'store']);

    Route::get('/nutrition/interval', [NutritionApiController::class, 'interval']);

    // show nutrition record 

    // update nutrition record

    // delete nutrition record 
    Route::delete('/nutrition/{nutrition:id}', [NutritionApiController::class, 'destroy']);

    // if nutrition record null, delete nutrition report 

    // *exercise tracker 
    Route::get('/exercise', [ExerciseApiController::class, 'index']);

    Route::post('/exercise', [ExerciseApiController::class, 'store']);

    Route::delete('/exercise/{exercise:id}', [ExerciseApiController::class, 'destroy']);

    // Route::get('/exercise/report/all', [ExerciseApiController::class, 'reports']);

    Route::get('/exercise/report/summary', [ExerciseApiController::class, 'summary']);

    Route::get('/exercise/report', [ExerciseApiController::class, 'report']);

    Route::get('/exercise/interval', [ExerciseApiController::class, 'interval']);

    // glucose report 
    // Route::get('/glucose', [GlucoseApiController::class, 'index']);

    Route::post('/glucose', [GlucoseApiController::class, 'store']);

    Route::delete('/glucose/{glucose:id}', [GlucoseApiController::class, 'destroy']);

    Route::get('/glucose/report', [GlucoseApiController::class, 'report']);

    // kalo glucose daily report doang kayaknya 
    Route::get('/glucose/report/summary', [GlucoseApiController::class, 'summary']);
});
