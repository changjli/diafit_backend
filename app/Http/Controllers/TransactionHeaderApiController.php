<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Food;
use App\Models\TransactionDetail;
use Exception;
use Illuminate\Http\Request;
use App\Models\TransactionHeader;
use Illuminate\Support\Facades\Validator;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class TransactionHeaderApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        try {
            $transactions = TransactionHeader::where('user_id', $user->id)->where('status', 'done')->get();

            return response()->json(['success' => true, 'data' => $transactions]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function active()
    {
        $user = auth()->user();
        try {
            $transactions = TransactionHeader::with("User")->where('user_id', $user->id)->whereIn('status', ['pending', 'ready'])->get();

            return response()->json(['success' => true, 'data' => $transactions]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     $user = auth()->user();
    //     try {
    //         // $validator = Validator::make($request->all(), [
    //         //     'total_price' => 'required|integer',
    //         //     'voucher_code' => 'required|string',
    //         //     'payment' => 'required|string',
    //         // ]);

    //         // if ($validator->fails()) {
    //         //     return response()->json(['succcess' => false, 'message' => $validator->errors()]);
    //         // }

    //         $transaction = TransactionHeader::create([
    //             'id' => IdGenerator::generate([
    //                 'table' => 'transaction_headers',
    //                 'field' => 'id',
    //                 'length' => 6,
    //                 'prefix' => 'TH'
    //             ]),
    //             'user_id' => $user->id,
    //             // 'total_price' => $request->totalPrice,
    //             // 'voucher_code' => $request->voucherCode,
    //             // 'payment' => $request->payment,
    //         ]);

    //         return response()->json(['success' => true, 'data' => $transaction]);
    //     } catch (Exception $e) {
    //         return response()->json(['success' => false, 'message' => $e->getMessage(), $e->getCode()]);
    //     }
    // }

    public static function store()
    {
        $user = auth()->user();
        try {
            $transactionHeader = TransactionHeader::create([
                'id' => IdGenerator::generate([
                    'table' => 'transaction_headers',
                    'field' => 'id',
                    'length' => 6,
                    'prefix' => 'TH'
                ]),
                'user_id' => $user->id,
                'status' => 'pending',
            ]);
            return $transactionHeader;
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), $e->getCode()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TransactionHeader  $transactionHeader
     * @return \Illuminate\Http\Response
     */
    public function show(TransactionHeader $transactionHeader)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TransactionHeader  $transactionHeader
     * @return \Illuminate\Http\Response
     */
    public function edit(TransactionHeader $transactionHeader)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TransactionHeader  $transactionHeader
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, String $id)
    {
        try {
            // $validator = Validator::make($request->all(), [
            //     'voucher_code' => 'required|string',
            //     'payment' => 'required|string',
            // ]);

            // if ($validator->fails()) {
            //     return response()->json(['succcess' => false, 'message' => $validator->errors()]);
            // }

            $old = TransactionHeader::where('id', $id)->first();

            TransactionHeader::where('id', $id)->update([
                'voucher_code' => $request->voucher_code,
                'payment' => $request->payment,
                'location' => $request->location,
                'delivery' => $request->delivery,
                'total_price' => $old->total_price + $request->deliveryPrice,
            ]);

            $new = TransactionHeader::where('id', $id)->first();

            CartApiController::removeAllCart();

            return response()->json(['success' => true, 'data' => $new]);

            // return response()->json(['price' => $this::getPrice($id)]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), $e->getCode()]);
        }
    }

    //    public static function update(int $id)
    //     {
    //         try {
    //             TransactionHeader::where('id', $id)->update([
    //                 'total_price' => $this::getPrice($id),
    //                 'voucher_code' => $request->voucher_code,
    //                 'payment' => $request->payment,
    //             ]);

    //             $new = TransactionHeader::where('id', $id)->first();

    //             return response()->json(['success' => true, 'data' => $new]);

    //             // return response()->json(['price' => $this::getPrice($id)]);
    //         } catch (Exception $e) {
    //             return response()->json(['success' => false, 'message' => $e->getMessage(), $e->getCode()]);
    //         }
    //     }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TransactionHeader  $transactionHeader
     * @return \Illuminate\Http\Response
     */
    public function destroy(TransactionHeader $transactionHeader)
    {
        $user = auth()->user();
        try {
            TransactionHeader::where('id', $transactionHeader->id)->delete();

            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), $e->getCode()]);
        }
    }

    public static function getPrice(String $id)
    {
        $price = 0;

        // cari transaction detail dulu 
        $transactionDetails = TransactionDetail::where('transaction_id', $id)->get();

        // buat setiap transaction detail hitung harganya terus tambahin 
        foreach ($transactionDetails as $transactionDetail) {
            $food = Food::where('id', $transactionDetail->food_id)->first();
            $price = $price + $transactionDetail->food_quantity * $food->price;
        }

        // sebenernya bisa pake eloquent tapi gatau gimana 

        return $price;
    }

    public function proccess()
    {
        $user = auth()->user();
        try {
            // store transaction header 
            $th = $this::store();

            // get all cart 
            $carts = CartApiController::getAllCart();

            // convert all cart to transaction_detail
            foreach ($carts as $cart) {
                TransactionDetailApiController::convert($th['id'], $cart['food_id'], $cart['food_quantity']);
            }

            // update price 
            TransactionHeader::where('id', $th['id'])->update([
                'total_price' => $this::getPrice($th['id']),
            ]);

            $transactionHeader = TransactionHeader::where('id', $th['id'])->first();
            $transactionHeader['total_price'] = $this::getPrice($th['id']);

            return response()->json(['success' => true, 'data' => $transactionHeader]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), $e->getCode()]);
        }
    }
}
