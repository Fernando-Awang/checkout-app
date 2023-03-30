<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function index()
    {
        try {
            $data = Cart::where('user_id', userId())->with('product')->get();
            return
            view('page.cart-list',[
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return printError($th);
        }
    }


    private function storeValidation()
    {
        try {
            $validator = Validator::make(request()->all(), [
                'product_id' => 'required',
                'qty' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return back()->withInput()->with('error', $validator->errors()->first());
            }

            // check product on cart
            $product = Cart::where('product_id', request('product_id'));
            if ($product->exists()) {
                return back()->withInput()->with('error', 'produk telah ditambahkan');
            }

            return true;
        } catch (\Throwable $th) {
            return back()->withInput()->with('error', $th->getMessage());
        }
    }


    public function store()
    {
        try {
            // validation
            $validation = $this->storeValidation();
            if ($validation!==true) {
                return $validation;
            }
            Cart::create([
                'user_id' => userId(),
                'product_id' => request('product_id'),
                'qty' => request('qty'),
            ]);

            return
            back()->with('success', 'item ditambahkan ke keranjang');
        } catch (\Throwable $th) {
            return
            $th;
            back()->with('error', 'terjadi error')->withInput();
        }
    }


    public function edit($id)
    {
        try {
            $data = Cart::with('product')->find($id);
            return
            view('page.cart-detail',[
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return printError($th);
        }
    }


    private function updateValidation()
    {
        try {
            $validator = Validator::make(request()->all(), [
                'qty' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return back()->withInput()->with('error', $validator->errors()->first());
            }
            return true;
        } catch (\Throwable $th) {
            return back()->withInput()->with('error', $th->getMessage());
        }
    }
    public function update($id)
    {
        try {
            // validation
            $validation = $this->updateValidation();
            if ($validation !== true) {
                return $validation;
            }

            $data = Cart::find($id);
            $data->qty = request('qty');
            $data->save();

            return
            back()->with('success', 'Qty diupdate');
        } catch (\Throwable $th) {
            return
            back()->with('error', 'terjadi error')->withInput();
        }
    }
    public function destroy($id)
    {
        try {

            $data = Cart::find($id);
            $data->qty = request('qty');
            $data->delete();

            return
            sendResponseJson(true, 'item dihapus');
        } catch (\Throwable $th) {
            return
            sendResponseJson(false, 'item gagal dihapus');
        }
    }
}
