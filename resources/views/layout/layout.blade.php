<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{env('APP_NAME')}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">{{env('APP_NAME')}}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link" aria-current="page" href="/product">Product</a>
                    @if (isset(auth()->user()->id))
                    <a class="nav-link" href="/cart">Cart</a>
                    <a class="nav-link" href="#">Transaction</a>
                    <a class="nav-link" href="/logout">Logout</a>
                    @else
                    <a class="nav-link" href="/login">Login</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
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

    @stack('script')
  </body>
</html>
