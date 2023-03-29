<?php

namespace App\Traits;


trait Midtrans {
    public function generateSnapToken($params)
    {
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = $this->midtransServerKey();
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = $this->midtransProduction();
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        // get token
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        return $snapToken;
    }

    public function midtransProduction()
    {
        return
            env('MIDTRANS_PRODUCTION') ?? false;
    }
    public function midtransApiUrl()
    {
        return
            $this->midtransProduction() ? 'https://app.midtrans.com/snap/v1/transactions' : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }
    public function midtransServerKey()
    {
        return
            $this->midtransProduction() ? env('MIDTRANS_PRODUCTION_SERVER_KEY') :  env('MIDTRANS_SANDBOX_SERVER_KEY');
    }
    public function midtransClientKey()
    {
        return
            $this->midtransProduction() ? env('MIDTRANS_PRODUCTION_CLIENT_KEY') :  env('MIDTRANS_SANDBOX_CLIENT_KEY');
    }
    public function jsSnap()
    {
        return
            $this->midtransProduction() ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }

}

?>
