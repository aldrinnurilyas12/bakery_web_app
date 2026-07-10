<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome to Kencana Bakery</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ asset('assets/front_end/css/admin_css.css') }}">
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}">
</head>

<body>
    <div class="container">
    <div style="margin-top:40px;overflow-y:auto;" class="video-content">
        <div class="text-content">
            <h4 class="title">Web Admin Kencana Bakery</h4>

            <div class="empty-transaction">
                <div class="empty-content">
                    <div class="center-content">

                        <div class="module-card operasional-modules">
                            <img style="width: 200px; height: 200px;" src="{{ asset('assets/front_end/images/module_admin.png') }}" alt="Operasional">
                            <h4>Operasional</h4>
                            <a class="btn btn-general" href="{{ route('dashboard_main') }}">
                                Operasional
                            </a>
                        </div>

                        <div class="module-card transaction-module">
                            <img style="width: 200px; height: 200px;" src="{{ asset('assets/front_end/images/pos_image.jpg') }}" alt="Transaksi">
                            <h4>Transaksi</h4>
                            <a class="btn btn-general" href="{{ route('transaction_create') }}">
                                Transaksi
                            </a>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</body>
@if (Session::has('message_success'))
    <script>
        Swal.fire({
            title: 'Berhasil',
            text: "{{ Session::get('message_success') }}",
            icon: 'success',
            timer: 2000,
            confirmButtonText: 'OK'
        });
    </script>
@elseif (Session::has('failed_message'))
    <script>
        Swal.fire({
            title: 'Gagal',
            text: "{{ Session::get('failed_message') }}",
            icon: 'error',
            timer: 2000,
            confirmButtonText: 'OK'
        });
    </script>
@endif



<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

    .container {
    min-height: 100vh;
}

.video-content {
    min-height: calc(100vh - 40px);
    display: flex;
    justify-content: center;
    align-items: center;
    overflow-y: auto;
}

.text-content {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.title {
    text-align: center;
    margin-bottom: 30px;
}

.empty-transaction,
.empty-content {
    width: 100%;
}

.center-content {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    gap: 30px;
}

.module-card {
    width: 250px;
    text-align: center;
    padding: 20px;
    border-radius: 12px;
    background: #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,.1);
}

.module-card img {
    width: 150px;
    height: 200px;
    object-fit: cover;
}

/* Mobile */
@media (max-width: 768px) {
    .center-content {
        flex-direction: column;
    }

    .module-card {
        width: 90%;
        max-width: 300px;
    }
}



</style>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
    integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
</script>

</html>
