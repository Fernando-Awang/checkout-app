@extends('layout.layout')
@section('content')

<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="table-responsive">
            <table class="table table-borderless">
                @foreach ($transaction as $key => $value)
                    @if ($key == 'order_id' || $key == 'checkout_date')
                    <tr>
                        <td>{{$key}}</td>
                        <td>:</td>
                        <td>{{$value}}</td>
                    </tr>
                    @elseif ($key == 'total')
                    <tr>
                        <td>{{$key}}</td>
                        <td>:</td>
                        <td>{{formatRupiah($value)}}</td>
                    </tr>
                    @endif
                @endforeach
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
        <form action="/transaction/create" method="post">
            @csrf
            <input type="hidden" name="cart_id" value="{{json_encode($cart_id)}}">
            <button type="submit" class="btn btn-primary">confirm</button>
            <a href="/cart" class="btn btn-info float-end text-light">back</a>
        </form>
    </div>
</div>
@endsection
