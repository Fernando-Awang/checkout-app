<?php

namespace App\Traits;

use App\Models\Product;

trait UpdateSkuProduct {
    public function updateSkuProduct($product_id, $qty, $status='add')
    {
        try {
            $product = new Product();
            $product = $product->find($product_id);

            $stockBefore = $product->sku;   
            switch ($status) {
                case 'minus': $stockAfter = (int)($stockBefore) - (int)($qty); break;
                default: $stockAfter = (int)($stockBefore) + (int)($qty); break;
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
