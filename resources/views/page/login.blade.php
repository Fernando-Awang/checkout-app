<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{env('APP_NAME')}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
  </head>
  <body>

    <div class="container m-5 p-5">
        <div class="d-flex justify-content-center">
            <div class="card">
                <div class="card-body">
                    <form action="/login" method="post">
                        @csrf
                        <h4 class="text-center">Login</h4>
                        <hr>
                        <div class="mb-3">
                            <input type="email" name="email" placeholder="email" value="{{old('email')}}" class="form-control">
                        </div>
                        <div class="mb-3">
                            <input type="password" name="password" placeholder="password" value="{{old('password')}}" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        function ShowToast(iconToast = 'success', msg = 'Toast Success') {
            return Toast.fire({
                icon: iconToast,
                title: msg
            })
        }
        function ShowModal(iconModal = 'success', msg = 'Modal Success', title=  '') {
            return Swal.fire(
                title,
                msg,
                iconModal
            )
        }
        function SuccessToast(msg = 'Berhasil') {
            return ShowToast('success', msg)
        }
        function ErrorToast(msg = 'Terjadi Error') {
            return ShowToast('error', msg)
        }
        function SuccessModal(msg = 'Berhasil') {
            return ShowModal('success', msg)
        }
        function ErrorModal(msg = 'Terjadi Error') {
            return ShowModal('error', msg)
        }
    </script>

    @if (session('error'))
    <script>
        ErrorModal('{{session()->get("error")}}')
    </script>
    @endif

    @if (session('success'))
    <script>
        SuccessModal('{{session()->get("success")}}')
    </script>
    @endif
</body>
</html>
