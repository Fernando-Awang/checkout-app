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
                            |
                            <a href="javascript:void(0)" onclick="confirmDelete('{{$item->id}}')">delete</a>
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
            url : '/transaction/delete/' + id,
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
</script>
@endpush
