@extends('layout.layout')
@section('content')

<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="text-left">Order ID</th>
                        <th class="text-left">Date</th>
                        <th class="text-left">Total</th>
                        <th class="text-left"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaction as $item)
                    <tr>
                        <td>{{$item->order_id}}</td>
                        <td>{{$item->checkout_date}}</td>
                        <td>{{formatRupiah($item->total)}}</td>
                        <td>
                            <a href="/transaction/detail/{{$item->id}}">detail</a>
                            @if ($item->payment_status == 'pending')
                            |
                            <a href="javascript:void(0)" onclick="pay('{{$item->snap_token}}')">pay</a>
                            @else
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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
</script>
@endpush
