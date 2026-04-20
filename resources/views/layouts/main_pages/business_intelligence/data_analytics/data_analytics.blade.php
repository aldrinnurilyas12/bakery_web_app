<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Data Analytics</title>
    <link href="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}">
</head>

<body class="sb-nav-fixed">
    @include('layouts.component_admin.navbar.navbar')
    @include('layouts.component_admin.sidebar.sidebar')


    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                @php
                    $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
                    $user_permission_forbidden = in_array($session_user->role_name, ['Supervisor', 'Manager']);
                @endphp
                <div class="container-fluid px-4">
                    <br>
                    <div class="card mb-4">
                        <div style="display: flex; justify-content:space-between;" class="card-header">
                            <div class="container mt-4">
                                <h3 class="mb-4">Dashboard Statistik</h3>

                                {{-- ROW ATAS --}}
                                <div class="row">
                                    <!-- Line Chart -->
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">Line Chart</div>
                                            <div class="card-body">
                                                <canvas id="lineChart"></canvas>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pie Chart -->
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">Pie Chart</div>
                                            <div class="card-body">
                                                <div style="height:300px;">
                                                    <canvas id="pieChart"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- ROW BAWAH --}}
                                <div class="row mt-4">
                                    <!-- Bar Chart 1 -->
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header">Bar Chart 1</div>
                                            <div class="card-body">
                                                <canvas id="barChart1"></canvas>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bar Chart 2 -->
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header">Bar Chart 2</div>
                                            <div class="card-body">
                                                <canvas id="barChart2"></canvas>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Table -->
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header">Tabel Data</div>
                                            <div class="card-body">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Nama</th>
                                                            <th>Jumlah</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Produk A</td>
                                                            <td>120</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Produk B</td>
                                                            <td>90</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Produk C</td>
                                                            <td>150</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script>
                                // LINE CHART
                                new Chart(document.getElementById('lineChart'), {
                                    type: 'line',
                                    data: {
                                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                                        datasets: [{
                                            label: 'Penjualan',
                                            data: [10, 20, 15, 30, 25, 40],
                                            borderColor: 'blue',
                                            backgroundColor: 'rgba(0, 0, 255, 0.2)',
                                            tension: 0.4,
                                            fill: true
                                        }]
                                    }
                                });

                                // PIE CHART
                                new Chart(document.getElementById('pieChart'), {
                                    type: 'pie',
                                    data: {
                                        labels: ['A', 'B', 'C'],
                                        datasets: [{
                                            data: [30, 50, 20],
                                            backgroundColor: ['red', 'green', 'blue']
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: {
                                                position: 'right', // posisi legend di kanan
                                                labels: {
                                                    boxWidth: 20,
                                                    padding: 15
                                                }
                                            }
                                        }
                                    }
                                });

                                // BAR CHART 1
                                new Chart(document.getElementById('barChart1'), {
                                    type: 'bar',
                                    data: {
                                        labels: ['Jan', 'Feb', 'Mar'],
                                        datasets: [{
                                            label: 'Pendapatan',
                                            data: [100, 200, 150],
                                            backgroundColor: 'orange'
                                        }]
                                    }
                                });

                                // BAR CHART 2
                                new Chart(document.getElementById('barChart2'), {
                                    type: 'bar',
                                    data: {
                                        labels: ['Produk A', 'Produk B', 'Produk C'],
                                        datasets: [{
                                            label: 'Stok',
                                            data: [50, 75, 40],
                                            backgroundColor: 'purple'
                                        }]
                                    }
                                });
                            </script>
                        </div>
                        <div class="card-body">
                            <h1>Data Analytics sedang dalam pengembangan</h1>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>


</body>
<script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/front_end/js/js/demo/datatables-demo.js') }}"></script>

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

    body {
        font-family: "DM Sans", serif;
    }
</style>

</html>
