<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Haruncpi\LaravelIdGenerator\IdGenerator;

use function PHPUnit\Framework\isEmpty;

class OrderApiController extends Controller
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
            $orders = Order::where('user_id', $user->id)->get();

            if ($orders->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'there are no orders']);
            }

            return response()->json(['successs' => true, 'data' => $orders]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), $e->getCode()]);
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
    public function store(Request $request)
    {
        $user = auth()->user();
        try {
            $validator = Validator::make($request->all(), [
                'address' => 'required|string',
                'detail' => 'required|string',
                'price' => 'required|integer',
                'voucher_code' => 'string',
                'payment' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['succcess' => false, 'message' => $validator->errors()]);
            }

            $order = Order::create([
                'id' => IdGenerator::generate([
                    'table' => 'orders',
                    'field' => 'id',
                    'length' => 6,
                    'prefix' => 'OR'
                ]),
                'user_id' => $user->id,
                'address' => $request->address,
                'detail' => $request->detail,
                'price' => $request->price,
                'voucher_code' => $request->voucher_code,
                'payment' => $request->payment,
            ]);

            return response()->json(['success' => true, 'data' => $order]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage(), $e->getCode()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $user = auth()->user();
        try {
            $order = Order::where('id', $order->id)->first();

            if (!$order) {
                return response()->json(['success' => false, 'message' => 'order not found']);
            }

            return response()->json(['successs' => true, 'data' => $order]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), $e->getCode()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        try {
            if (Order::where('id', $order->id)->delete());

            return response()->json(['successs' => true, 'message' => 'order has been deleted']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), $e->getCode()]);
        }
    }
}
