<?php

namespace App\Http\Controllers;

use App\Models\Cart;

class TransactionController extends Controller
{
    private $cartData;
    private $dataTransactionDetail;
    private $dataTransaction;
    private $midtransParam;
    private function restructureData($cart_id = null)
    {
        try {
            $listCartId = $cart_id ?? request('cart_id') ?? [];
            if (count($listCartId) == 0) {
                return redirect('/cart')->with('error', 'select item');
            }
            $listCartId;


            $cartData = [];
            foreach ($listCartId as $cartId) {
                $cart = Cart::where('id', $cartId)->with('product')->first();
                if (!isset($cart->id)) {
                    return redirect('/cart')->with('error', 'cart id ' . $cartId . ' not found');
                }
                $cartData[] = $cart;
            }
            $this->cartData = $cartData;

            return
            true;
        } catch (\Throwable $th) {
            return
            redirect('/cart')->with('error', $th->getMessage());
        }
    }
    private function setDetailTransaction()
    {
        try {
            $dataTransactionDetail = [];
            foreach ($this->cartData as $item) {
                $detailTransaction = [];
                $detailTransaction['product_id'] = $item->product_id;
                $detailTransaction['product_name'] = $item->product->name;
                $detailTransaction['price'] = $item->product->price;
                $detailTransaction['qty'] = $item->qty;
                $detailTransaction['subtotal'] = $detailTransaction['qty'] * $detailTransaction['price'];

                $dataTransactionDetail[] = $detailTransaction;
            }

            $this->dataTransactionDetail = $dataTransactionDetail;
            return
            true;
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
    private function setTransaction()
    {
        try {
            $dataTransaction = [];
            $dataTransaction['order_id'] = 'PAY' . date('ymd') . generateRandomString(4);
            $dataTransaction['user_id'] = userId();
            $dataTransaction['total'] = collect($this->dataTransactionDetail)->sum('subtotal');
            $dataTransaction['checkout_date'] = now();
            $dataTransaction['payment_date'] = NULL;
            $dataTransaction['payment_status'] = 'pending';

            $this->dataTransaction = $dataTransaction;
            return
            true;
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
    private function setMidtransParam()
    {
        try {
            $midtransParam = [];

            $this->midtransParam = $midtransParam;
            return
            true;
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
    public function confirmCheckout()
    {
        try {
            // restructure data cart
            $setData = $this->restructureData();
            if ($setData !== true) {
                return $setData;
            }
            // set data detail transaction
            $setData = $this->setDetailTransaction();
            if ($setData !== true) {
                return $setData;
            }
            // set data transaction
            $setData = $this->setTransaction();
            if ($setData !== true) {
                return $setData;
            }
            return view('page.transaction-confirm', [
                'transaction' => $this->dataTransaction,
                'transaction_detail' => $this->dataTransactionDetail,
                'cart_id' => request('cart_id'),
            ]);
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function store()
    {
        try {
            $cart_id = json_decode(request('cart_id'));
            // restructure data cart
            $setData = $this->restructureData($cart_id);
            if ($setData !== true) {
                return $setData;
            }
            // set data detail transaction
            $setData = $this->setDetailTransaction();
            if ($setData !== true) {
                return $setData;
            }
            // set data transaction
            $setData = $this->setTransaction();
            if ($setData !== true) {
                return $setData;
            }
            // set data transaction
            $setData = $this->setMidtransParam();
            if ($setData !== true) {
                return $setData;
            }
            return ([
                'transaction' => $this->dataTransaction,
                'transaction_detail' => $this->dataTransactionDetail,
                'modtrans_param' => $this->midtransParam,
            ]);
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
