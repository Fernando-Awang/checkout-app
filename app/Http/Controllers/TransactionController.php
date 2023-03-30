<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionDetails;
use App\Traits\Midtrans;
use App\Traits\UpdateSkuProduct;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    use Midtrans, UpdateSkuProduct;

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
            // transaction details
            $transaction_details = [];
            $transaction_details['order_id'] = (string)($this->dataTransaction['order_id']);
            $transaction_details['gross_amount'] = (int)($this->dataTransaction['total']);

            //customer details
            $customer_details = [];
            $customer_details['first_name'] = (string)(auth()->user()->name);
            $customer_details['email'] = (string)(auth()->user()->email);

            // item details
            $item_details = [];


            $idItem = 1;
            // detail transaksi
            foreach ($this->dataTransactionDetail as $item) {
                // items
                $items = [];
                $items['id'] = (string)$idItem;
                $items['name'] = (string)$item['product_name'];
                $items['price'] = (int)($item['price']);
                $items['quantity'] = (int)($item['qty']);
                $item_details[] = $items;

                $idItem = $idItem + 1;
            }


            $params = [];
            $params['transaction_details'] = $transaction_details;
            $params['customer_details'] = $customer_details;
            $params['item_details'] = $item_details;

            $this->midtransParam = $params;
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

    // validate isset transaction
    private function checkPendingTransaction()
    {
        try {
            $transaction = Transaction::where([
                'user_id' => userId(),
                'payment_stats' => 'pending',
            ]);
            if ($transaction->exists()) {
                return redirect('/transaction/index')->with('error', 'please complete your unpaid transaction');
            }
            return true;
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
    public function store()
    {
        DB::beginTransaction();
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

            $snap_token = $this->generateSnapToken($this->midtransParam);
            $this->dataTransaction['snap_token'] = $snap_token;

            $transaction = Transaction::create($this->dataTransaction);
            foreach ($this->dataTransactionDetail as $item) {

                $item['transaction_id'] = $transaction->id;

                TransactionDetails::create($item);

                $product_id = $item['product_id'];
                $qty = $item['qty'];

                $this->updateSkuProduct($product_id, $qty, 'minus');

                Cart::whereIn('id', $cart_id)->delete();
            }

            DB::commit();
            return redirect('/transaction/index')->with('success', 'create transaction success');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', $th->getMessage());
        }
    }

    public function index()
    {
        try {
            $transaction = Transaction::where('user_id', userId())->get();
            return
            view('page.transaction-list', [
                'transaction' => $transaction,
                'jsSnap' => $this->jsSnap(),
                'clientKey' => $this->midtransClientKey(),
            ]);
        } catch (\Throwable $th) {
            return
            back()->with('error', $th->getMessage());
            ;
        }
    }
    public function detail($id)
    {
        try {
            $transaction = Transaction::where([
                'user_id'=> userId(),
                'id'=> $id,
            ])->first();
            $transaction_detail = TransactionDetails::where('transaction_id', $id)->get();

            return
            view('page.transaction-detail', [
                'transaction' => $transaction,
                'transaction_detail' => $transaction_detail,
                'jsSnap' => $this->jsSnap(),
                'clientKey' => $this->midtransClientKey(),
            ]);
        } catch (\Throwable $th) {
            return
            back()->with('error', $th->getMessage());
            ;
        }
    }
}
