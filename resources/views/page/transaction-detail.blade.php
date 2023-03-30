@extends('layout.layout')
@section('content')

<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="table-responsive">
            <table class="table table-borderless">
                <tr>
                    <td>Order ID</td>
                    <td>:</td>
                    <td>{{$transaction->order_id}}</td>
                </tr>
                <tr>
                    <td>Checkout date</td>
                    <td>:</td>
                    <td>{{$transaction->checkout_date}}</td>
                </tr>
                <tr>
                    <td>Payment status</td>
                    <td>:</td>
                    <td>{{$transaction->payment_status}}</td>
                </tr>
                @if ($transaction->payment_status == 'settlement')
                <tr>
                    <td>Payment date</td>
                    <td>:</td>
                    <td>{{$transaction->payment_date}}</td>
                </tr>
                @endif
                <tr>
                    <td>Total</td>
                    <td>:</td>
                    <td>{{formatRupiah($transaction->total)}}</td>
                </tr>
            </table>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="text-left">product</th>
                        <th class="text-left">price</th>
                        <th class="text-left">qty</th>
                        <th class="text-left">subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaction_detail as $item)
                    <tr>
                        <td>{{$item['product_name']}}</td>
                        <td>{{formatRupiah($item['price'])}}</td>
                        <td>{{$item['qty']}}</td>
                        <td>{{formatRupiah($item['subtotal'])}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <br>
        @if ($transaction->payment_status == 'pending')
        <button type="button" class="btn btn-primary" onclick="pay('{{$transaction->snap_token}}')">pay</button>
        @endif
        <a href="/transaction/index" class="btn btn-info float-end text-light">back</a>
    </div>
</div>
@endsection
@push('script')
<script type="text/javascript" src="{{$jsSnap}}" data-client-key="{{$clientKey}}"></script>
<script>
    function pay(snapToken){
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
    }

    $(function(){
        setInterval(() => {
            checkPayment('{{$transaction->id}}', '{{$transaction->payment_status}}')
        }, 3000);
    })

    function checkPayment(id, payment_status) {
        $.get('/transaction/check-status/'+id, function (status) {
            if (status != 'error' && status != payment_status) {
                location.reload();
            }
        })
    }
</script>
@endpush
