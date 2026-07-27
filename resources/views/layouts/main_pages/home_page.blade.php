<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="{{ asset('assets/front_end/css/styles.css') }}" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}">
    <title>Dashboard</title>
</head>

<body class="sb-nav-fixed">
    @include('layouts.component_admin.navbar.navbar')

    @include('layouts.component_admin.sidebar.sidebar')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Welcome back, &nbsp;</li>
                        {{ app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username }}
                        <span>&nbsp; | &nbsp; </span>
                        <li>{{ app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->position_name }}
                        </li>
                    </ol>
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-light text-black mb-4">
                                <div style="display: flex;align-items: center; gap:10px;font-weight: bold;"
                                    class="card-body"><i
                                        style="width: 30px; height:30px; border-radius: 50%; background: rgb(227, 241, 255); padding:10px;"
                                        class="fa fa-cubes" aria-hidden="true"></i> Master Produk</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-black stretched-link" href="{{ route('products_data') }}">View
                                        Details</a>
                                    <div class="small text-black"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-light text-black mb-4">
                                <div style="display: flex;align-items: center; gap:10px;font-weight: bold;"
                                    class="card-body"><i
                                        style="width: 30px; height:30px; border-radius: 50%; background: rgb(227, 241, 255); padding:10px;"
                                        class="fa fa-cube" aria-hidden="true"></i> Daily Produk</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-black stretched-link"
                                        href="{{ route('dailyproducts_data') }}">View Details</a>
                                    <div class="small text-black"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-light text-black mb-4">
                                <div style="display: flex;align-items: center; gap:10px;font-weight: bold;"
                                    class="card-body"><i
                                        style="width: 30px; height:30px; border-radius: 50%; background: rgb(227, 241, 255); padding:10px;"
                                        class="fa fa-exchange" aria-hidden="true"></i> Transaksi</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-black stretched-link" href="#">View Details</a>
                                    <div class="small text-black"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-light text-black mb-4">
                                <div style="display: flex;align-items: center; gap:10px;font-weight: bold;"
                                    class="card-body"><i
                                        style="width: 30px; height:30px; border-radius: 50%; background: rgb(227, 241, 255); padding:10px;"
                                        class="fa fa-handshake" aria-hidden="true"></i> CRM</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-black stretched-link" href="#">View Details</a>
                                    <div class="small text-black"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($maintenance_info)
                        <div class="alert alert-danger">
                            <div style="height: 50vh; display:flex; justify-content:center; border:1px solid gray;border-radius:10px;"
                                class="empty-transaction">

                                <div style="display: flex;" class="empty-content">
                                    <div style="display: flex; gap:20px;margin:auto;" class="alert-info">
                                        <img width="70" height="70"
                                            src="{{ asset('assets/front_end/assets/img/null.png') }}" alt="">
                                        <div style="display: block;" class="text-content">
                                            <h3>{{ $maintenance_info->maintenance_information }}</h3>
                                            <p>{{ $maintenance_info->message }}</p>

                                            <div class="date-info">
                                                Tanggal Berlaku:
                                                {{ \Carbon\carbon::parse($maintenance_info->start_date)->format('d M Y') }}
                                                {{ \Carbon\carbon::parse($maintenance_info->hour_start)->format('H:i') }}
                                                &nbsp;
                                                <span>S/d</span>
                                                &nbsp;
                                                {{ \Carbon\carbon::parse($maintenance_info->end_date)->format('d M Y') }}
                                                {{ \Carbon\carbon::parse($maintenance_info->hour_end)->format('H:i') }}
                                                <br>
                                                <strong>Sisa Waktu:
                                                    <span id="countdowntime"></span>
                                                </strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endif

                </div>
            </main>
        </div>
    </div>

    @include('layouts.component_admin.footer.footer')


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
@elseif(Session::has('failed_message'))
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
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Ambil tanggal dari end_date
        const endDate =
            "{{ $maintenance_info ? \Carbon\Carbon::parse($maintenance_info->end_date)->format('Y-m-d') : '' }}";
        const endTime =
            "{{ $maintenance_info ? \Carbon\Carbon::parse($maintenance_info->hour_end)->format('H:i:s') : '' }}";

        // Bentuk datetime lengkap
        const endDateTime = new Date(endDate + "T" + endTime);

        function updateCountdown() {

            const now = new Date();
            const distance = endDateTime.getTime() - now.getTime();

            if (distance <= 0) {
                document.getElementById("countdowntime").innerHTML = "Maintenance telah berakhir";
                clearInterval(interval);
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("countdowntime").innerHTML =
                `${days} Hari ${hours} Jam ${minutes} Menit ${seconds} Detik`;
        }

        updateCountdown();
        const interval = setInterval(updateCountdown, 1000);

    });
</script>


</html>
