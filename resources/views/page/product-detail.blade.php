@extends('layout.layout')
@section('content')
<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="card">
            <div class="card-body">
                <form action="/cart" method="post">
                    @csrf
                    <p>
                        Product : {{$data->name}}
                        <br>
                        Price : {{formatRupiah($data->price)}}
                        <br>
                        Sku : {{$data->sku}}
                        <br>
                        Desc : {{$data->description}}
                    </p>
                    <hr>
                    <span>Add to Cart</span>
                    <hr>
                    <div class="mb-3">
                        <input type="hidden" name="product_id" value="{{$data->id}}">
                        <label for="" class="form-label">Qty</label>
                        <input type="number" name="qty" id="" class="form-control" min="1" max="{{$data->sku}}" value="1">
                    </div>
                    <button type="submit" class="btn btn-primary">Add</button>
                    <a href="/product" class="btn btn-info float-end text-white">back</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
