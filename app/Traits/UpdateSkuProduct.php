<?php

namespace App\Traits;

use App\Models\Product;

trait UpdateSkuProduct {
    public function generateSnapToken($product_id, $qty, $status='add')
    {
        try {
            $product = new Product();
            $product = $product->find($product_id);

            $stockBefore = $product->stock;
            $stockAfter = $stockBefore;
            switch ($status) {
                case 'minus': $stockAfter = $stockBefore - $qty; break;
                default: $stockAfter = $stockBefore + $qty; break;
            }

            $product->sku = $stockAfter;
            $product->save();
            return true;
        } catch (\Throwable $th) {
            return printError($th);
        }
    }

}

?>
