<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        try {
            return view('page.product-list');
        } catch (\Throwable $th) {
            return printError($th);
        }
    }
    public function show($id)
    {
        try {
            $product = Product::find($id);
            return
            view('page.product-detail', [
                'data' => $product
            ]);
        } catch (\Throwable $th) {
            return printError($th);
        }
    }
}
