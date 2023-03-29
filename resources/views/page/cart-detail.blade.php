@extends('layout.layout')
@section('content')
<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="card">
            <div class="card-body">
                <form action="/cart/{{$data->id}}" method="post">
                    @method('put')
                    @csrf
                    <p>
                        Product : {{$data->product->name}}
                        <br>
                        Price : {{formatRupiah($data->product->price)}}
                        <br>
                        Sku : {{$data->product->sku}}
                        <br>
                        Desc : {{$data->product->description}}
                    </p>
                    <hr>
                    <span>Update Qty</span>
                    <hr>
                    <div class="mb-3">
                        <label for="" class="form-label">Qty</label>
                        <input type="number" name="qty" id="" class="form-control" min="1" max="{{$data->product->sku}}" value="{{$data->qty}}">
                    </div>
                    <button type="submit" class="btn btn-primary">update</button>
                    <a href="/cart" class="btn btn-info float-end text-white">back</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
