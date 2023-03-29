@extends('layout.layout')
@section('content')

<div class="row">
    <div class="col-sm-12 col-md-4">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="text-left">#</th>
                        <th class="text-left">Name</th>
                        <th class="text-left"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (\App\Models\Product::all() as $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$item->name}}</td>
                        <td><a href="/product/{{$item->id}}">detail</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
