<?php

namespace App\Services;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderService
{
    public function createOrder(array $data, int $customerId): Order
    {
        return DB::transaction(function () use ($data, $customerId) {
            $totalAmount = 0;
            $itemsToCreate = [];

            foreach ($data['items'] as $itemData) {
                $product = Product::query()->findOrFail($itemData['product_id']);

                if ($product->stock < $itemData['quantity']) {
                    throw new Exception("Estoque insuficiente para o produto: {$product->name}");
                }

                $itemPrice = $product->price;
                $subtotal = $itemPrice * $itemData['quantity'];
                $totalAmount += $subtotal;

                $product->decrement('stock', $itemData['quantity']);

                $itemsToCreate[] = [
                    'product_id' => $product->id,
                    'quantity' => $itemData['quantity'],
                    'price' => $itemPrice,
                ];
            }

            $order = Order::query()->create([
                'customer_id' => $customerId,
                'amount' => $totalAmount,
                'status' => OrderStatusEnum::PENDING,
                'shipping_address' => $data['shipping_address'] ?? null,
            ]);

            foreach ($itemsToCreate as $item) {
                $order->items()->create($item);
            }

            return $order->load('items.product');
        });
    }
}
