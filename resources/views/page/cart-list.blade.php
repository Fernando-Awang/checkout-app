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
                        <th class="text-left">Qty</th>
                        <th class="text-left"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                    <tr>
                        <td>
                            <input class="form-check-input" name="cart_id" type="checkbox" value="{{$item->id}}">
                        </td>
                        <td>{{$item->product->name}}</td>
                        <td>{{$item->qty}}</td>
                        <td>
                            <a href="/cart/{{$item->id}}/edit">detail</a>
                            |
                            <a href="javascript:void(0)" onclick="confirmDelete('{{$item->id}}')">delete</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <br>
        <button class="btn btn-primary" id="btnCheckout">Checkout</button>
    </div>
</div>
@endsection
@push('script')
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Confirm',
            text: "Delete data?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Delete it',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                return drop(id)
            }
        })
    }
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
                console.log(response);
                ErrorModal('terjadi kesalahan')
            },
        })
    }

    $('#btnCheckout').on('click', function(){
        let id = [];
        $('input[name="cart_id"]:checkbox:checked').each(function(i){
            id[i] = $(this).val();
        });
        if (id.length == 0) {
            ErrorModal('Select Item')
            return
        }

        let url = '/transaction/confirm/?'
        $.each(id, function(i, id){
            url = url + '&cart_id[]=' + id
        })
        return location.href = url;
    })
</script>
@endpush
