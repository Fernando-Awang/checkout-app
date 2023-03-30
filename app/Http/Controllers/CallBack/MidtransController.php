<?php

namespace App\Http\Controllers\CallBack;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class MidtransController extends Controller
{
    public function updatePayment()
    {
        DB::beginTransaction();
        try {

            $transaction_status = request()->transaction_status;
            $order_id = request()->order_id;

            $this->updateTransaction($order_id, $transaction_status);

            DB::commit();
            return 'update success';
        } catch (\Throwable $th) {
            DB::rollback();
            return
                $th;
        }
    }

    private function updateTransaction($order_id, $status)
    {
        $transaksi = Transaction::where('order_id', $order_id)->first();
        $transaksi->payment_date = now();
        $transaksi->payment_status = $status;
        $transaksi->save();
    }
}
