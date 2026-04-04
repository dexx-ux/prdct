<?php

namespace App\Http\Controllers;

use App\Models\GuestOrder;
use App\Models\GuestOrderItem;
use App\Models\UserOrder;
use App\Models\UserOrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class GuestOrderController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'required|string|max:20',
                'customer_address' => 'required|string|max:500',
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'total_price' => 'required|numeric|min:0',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            DB::beginTransaction();
            
            $product = Product::where('id', $request->product_id)->lockForUpdate()->first();
            
            if (!$product) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }
            
            if ($product->quantity < $request->quantity) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock. Only ' . $product->quantity . ' items available.'
                ], 400);
            }
            
            // Create the guest order (or user order if logged in)
            $orderData = [
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'session_id' => Session::getId(),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'total_amount' => $request->total_price,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => 'unspecified',
            ];

            if (Auth::check()) {
                // Create a user order instead of guest order for logged in user
                $order = UserOrder::create(array_merge($orderData, [
                    'user_id' => Auth::id(),
                    'shipping_address' => $request->customer_address
                ]));

                UserOrderItem::create([
                    'user_order_id' => $order->id,
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                    'price' => $request->total_price / $request->quantity,
                    'subtotal' => $request->total_price,
                ]);
            } else {
                $order = GuestOrder::create($orderData);

                GuestOrderItem::create([
                    'guest_order_id' => $order->id,
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                    'price' => $request->total_price / $request->quantity,
                    'subtotal' => $request->total_price,
                ]);
            }

            $product->decrement('quantity', $request->quantity);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order' => [
                    'id' => $order->id,
                    'customer_name' => $order->customer_name,
                    'total_price' => $order->total_price,
                    'user_id' => $order->user_id
                ]
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order creation error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}