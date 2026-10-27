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
        <div class="video-content">
            <div class="text-content">
                <h4 style="text-align: center;margin-top: 20px;" class="title">Web Admin Kencana Bakery</h4>

                <div class="empty-transaction">
                    <div class="empty-content">
                        <div class="center-content">

                            <form method="POST" action="{{ route('set_login_role', $nik) }}"
                                enctype="multipart/form-data">

                                @csrf
                                @method('PUT')

                                <div class="content-mode">

                                    <label class="module-card operasional-modules">
                                        <img src="{{ asset('assets/front_end/images/module_admin.png') }}"
                                            alt="Operasional">

                                        <h4>Operasional</h4>

                                        <span>
                                            <input type="radio" name="select_mode" value="OPR" required>
                                            Menu Operasional
                                        </span>
                                    </label>

                                    <label class="module-card transaction-module">
                                        <img src="{{ asset('assets/front_end/images/pos_image.jpg') }}" alt="Transaksi">

                                        <h4>Transaksi</h4>

                                        <span>
                                            <input type="radio" name="select_mode" value="TRX" required>
                                            Menu Transaksi
                                        </span>
                                    </label>

                                </div>

                                <button class="btn btn-general" type="submit">
                                    Pilih Mode
                                </button>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>


<style>
    /* Container card */
    .content-mode {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
        width: 100%;
        max-width: 700px;
        margin: 0 auto 24px;
        box-sizing: border-box;
    }

    /* Card mode */
    .module-card {
        width: 100%;
        min-width: 0;
        padding: 24px 20px;
        box-sizing: border-box;

        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;

        text-align: center;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
    }

    /* Gambar dalam card */
    .module-card img {
        width: 100%;
        max-width: 200px;
        height: auto;
        aspect-ratio: 1 / 1;
        object-fit: contain;
        margin-bottom: 15px;
    }

    /* Judul card */
    .module-card h4 {
        margin-bottom: 12px;
        font-size: 18px;
        text-align: center;
    }

    /* Tombol */
    .btn-general {
        display: block;
        margin: 0 auto;
        min-height: 44px;
        width: 30%;
        padding: 12px 28px;
    }

    /* Mobile dan tablet kecil: card ke bawah */
    @media (max-width: 576px) {
        .content-mode {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .module-card {
            padding: 20px 16px;
        }

        .module-card img {
            max-width: 150px;
        }

        .text-content .title {
            font-size: 20px;
            text-align: center;
        }

        .btn-general {
            width: 100%;
        }
    }
</style>

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
