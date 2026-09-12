<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function __construct(protected OrderService $orderService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $customerId = $request->user()->id;

        $orders = Order::with(['items.product'])
            ->where('customer_id', $customerId)
            ->latest()
            ->paginate(10);

        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $storeOrderRequest)
    {
        try {
            $customerId = $storeOrderRequest->user()->id;

            $order = $this->orderService->createOrder(
                $storeOrderRequest->validated(),
                $customerId
            );

            return response()->json([
                'success' => true,
                'message' => 'Pedido criado com sucesso',
                'order' => new OrderResource($order),
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        try {
            $customerId = $request->user()->id;

            $order = Order::with(['items.product'])
                ->where('customer_id', $customerId)
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'order' => new OrderResource($order),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Não encontramos o pedido especificado',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
