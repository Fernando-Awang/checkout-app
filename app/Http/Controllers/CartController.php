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
        return Validator::make(request()->all(), [
            'product_id' => 'required',
            'qty' => 'required|numeric',
        ]);
    }


    public function store()
    {
        try {
            // validation
            $validation = $this->storeValidation();
            if ($validation->fails()) {
                return back()->withInput()->with('error', $validation->errors()->first());
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
        return Validator::make(request()->all(), [
            'qty' => 'required|numeric',
        ]);
    }
    public function update($id)
    {
        try {
            // validation
            $validation = $this->updateValidation();
            if ($validation->fails()) {
                return back()->withInput()->with('error', $validation->errors()->first());
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
