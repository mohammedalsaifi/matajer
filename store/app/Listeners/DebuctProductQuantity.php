<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Facades\Cart;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Throwable;

class DebuctProductQuantity
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($order, $user = null) // if listener dealing with more one event you must not specify event
    {
        try {
            foreach ($order->products as $product) {
                // $product->decrement('quantity', $product->order_item->quantity);
                Product::where('id', '=', $product->product_id)
                    ->update([
                        'quantity' => DB::raw('quantity - ' . $product->quantity),
                    ]);
            }
        } catch (Throwable $e) {
        }
    }
}
