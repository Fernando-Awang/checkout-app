@extends('layout.layout')
@section('content')
<div class="row">
    <div class="col-sm-12 col-md-8">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="text-left">#</th>
                        <th class="text-left">Name</th>
                        <th class="text-left">Qty</th>
                        <th class="text-left"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$item->product->name}}</td>
                        <td>{{$item->qty}}</td>
                        <td>
                            <a href="/cart/{{$item->id}}/edit">detail</a>
                            |
                            <a href="javascript:void(0)" onclick="drop('{{$item->id}}')">delete</a>
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
<script>
    function drop(id) {
        $.ajax({
            url : '/cart/' + id,
            type : 'delete',
            data : {
                _token: '{{csrf_token()}}'
            },
            success:function(response){
                if(response.success){
                    SuccessModal(response.message).then(function(){
                        location.reload()
                    })
                    return;
                }
                ErrorModal(response.message)
            },
            error:function(response){

            },
        })
    }
</script>
@endpush
