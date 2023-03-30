<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- @TODO: replace SET_YOUR_CLIENT_KEY_HERE with your client key -->
    <script type="text/javascript" src="'https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{env('MIDTRANS_SANDBOX_CLIENT_KEY')}}"></script>
    <!-- Note: replace with src="https://app.midtrans.com/snap/snap.js" for Production environment -->
    <!-- Note: replace with src="https://app.sandbox.midtrans.com/snap/snap.js" for sanbox environment -->
</head>

<body>
    <hr>
    Cek snap token
    <input type="text" id="snap-token" placeholder="masukan snap token">
    <button id="pay-button">Pay!</button>
    <hr>
    <form action="/api/update-payment" method="post">
        update payment
        <input type="text" name="order_id" placeholder="masukan order_id">
        <input type="text" name="payment_type" placeholder="masukan payment_type, ex. bank_transfer"
            value="bank_transfer">
        <select name="transaction_status" id="">
            <option value="settlement">settlement (dibayar)</option>
            <option value="expire">expire (kadaluarsa)</option>
        </select>
        <button type="submit">Update</button>
    </form>
    <script type="text/javascript">
        // For example trigger on button clicked, or any time you need
      var payButton = document.getElementById('pay-button');
      payButton.addEventListener('click', function () {
          // Trigger snap popup. @TODO: Replace TRANSACTION_TOKEN_HERE with your transaction token
        var snapToken = document.getElementById('snap-token').value;
        // console.log(snapToken);
        window.snap.pay(snapToken,
            {
                // Optional
                // onSuccess: function(result) {
                //     console.log('success')
                // },
                // Optional
                onPending: function(result) {
                    // console.log(result)
                    // console.log('pending')
                },
                // Optional
                onError: function(result) {
                    // console.log('error')
                }
            }
        );
        // customer will be redirected after completing payment pop-up
      });
    </script>
</body>

</html>
