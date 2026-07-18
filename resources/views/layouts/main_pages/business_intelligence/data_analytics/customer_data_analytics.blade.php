<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Data Analytics Pelanggan</title>
    <link href="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}">
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <link rel="stylesheet" href="{{ asset('assets/front_end/css/admin_css.css') }}">
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
                            <div class="title">
                                Business Intelligence > <strong>Data Analitik Pelanggan</strong>
                            </div>
                        </div>
                        <div style="display: flex; justify-content:space-between;" class="card-header">
                            <div class="container mt-4">
                                <div style="display: flex; justify-content: space-between;margin-bottom:20px;"
                                    class="filter-contnent">
                                    <div class="title-content-filter">
                                        <h3 class="mb-4">Dashboard Statistik Segementasi Pelanggan</h3>
                                        <div style="display:flex;gap:20px;flex-wrap:wrap;font-size:14px;"
                                            class="link-to-another-page">
                                            <a style="width:150px;" class="btn-general"
                                                href="{{ route('data_analytics') }}">Segmen Transaksi</a>
                                            <a style="width:150px;" class="btn-general"
                                                href="{{ route('data_analytics_customer') }}">Segmen Pelanggan</a>
                                        </div>
                                    </div>

                                    <form action="{{ route('filter_dashboard') }}" method="GET">
                                        <div style="display:flex; gap:20px;" class="filter-content">

                                            <div class="date-filter">
                                                <label for=""><strong>Tanggal awal</strong></label>
                                                <input type="date" name="start_date"
                                                    value="{{ old('start_date', request('start_date')) }}"
                                                    class="form-control">
                                            </div>

                                            <div class="date-filter">
                                                <label for=""><strong>Tanggal akhir</strong></label>
                                                <input type="date" name="end_date"
                                                    value="{{ old('end_date', request('end_date')) }}"
                                                    class="form-control">
                                            </div>

                                            {{-- <div class="store-filter">
                                                <label for=""><strong>Store</strong></label>
                                                <select name="store" class="form-control" id="" required>
                                                    <option value="">=== Pilih Store ===</option>
                                                    @foreach ($stores as $st)
                                                        <option value="{{ $st->store_code }}"
                                                            {{ old('store', request('store')) == $st->store_code ? 'selected' : '' }}>{{ $st->store_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div> --}}

                                            <button style="height: 40px;align-self: end;" type="submit"
                                                class="btn btn-primary">Filter</button>
                                            <a href="{{ route('data_analytics') }}"
                                                style="height:40px; align-self:end;" class="btn btn-warning">
                                                Reset
                                            </a>
                                        </div>
                                    </form>
                                </div>

                                {{-- Row Card --}}

                                <div class="row">

                                    <div class="col-xl-3 col-md-6">
                                        <div class="card bg-white text-black mb-4">
                                            <div class="card-body text-center">

                                                <div class="text-content-main" style="font-size:30px;font-weight:bold;">
                                                    {{ $total_customer }}
                                                </div>

                                                <div class="title-content">
                                                    Total Pelanggan
                                                </div>

                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-xl-3 col-md-6">
                                        <div class="card bg-white text-black mb-4">
                                            <div class="card-body text-center">

                                                <div class="text-content-main" style="font-size:30px;font-weight:bold;">
                                                    {{ $total_customer }}
                                                </div>

                                                <div class="title-content">
                                                    Pelanggan Baru
                                                </div>

                                                @if ($mom_customer > 0)
                                                    <div class="mt-2">
                                                        @if ($mom_customer > 0)
                                                            <div style="display:flex;justify-content:center;gap:20px;"
                                                                class="flex-content">
                                                                <span class="text-success fw-bold">
                                                                    <i class="fas fa-arrow-up"></i>
                                                                    {{ number_format($mom_customer, 1) }}%
                                                                </span>
                                                                <span>+{{ number_format(abs($new_customer)) }}</span>
                                                            </div>
                                                        @elseif($mom_customer < 0)
                                                            <span class="text-danger fw-bold">
                                                                <i class="fas fa-arrow-down"></i>
                                                                {{ number_format(abs($new_customer), 1) }}%
                                                            </span>
                                                        @endif

                                                        <small class="text-muted d-block">
                                                            dibanding bulan lalu
                                                        </small>
                                                    </div>
                                                @endif

                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-xl-3 col-md-6">
                                        <div class="card bg-white text-black mb-4">
                                            <div class="card-body text-center">

                                                <div class="text-content-main" style="font-size:30px;font-weight:bold;">
                                                    {{ $active_customer }}
                                                </div>

                                                <div class="title-content">
                                                    Pelanggan Aktif
                                                </div>

                                                {{-- @if ($mom_transaction > 0)
                                                <div class="mt-2">
                                                        @if ($mom_transaction > 0)
                                                        <div style="display:flex;justify-content:center;gap:20px;" class="flex-content">
                                                            <span class="text-success fw-bold">
                                                                <i class="fas fa-arrow-up"></i>
                                                                {{ number_format($mom_transaction, 1) }}%
                                                            </span>

                                                            <span>+{{ $total_transaction_diff }}</span>
                                                        </div>
                                                        @elseif($mom_transaction < 0)
                                                            <span class="text-danger fw-bold">
                                                                <i class="fas fa-arrow-down"></i>
                                                                {{ number_format(abs($mom_transaction), 1) }}%
                                                            </span>
                                                        @endif

                                                        <small class="text-muted d-block">
                                                            dibanding bulan lalu
                                                        </small>

                                                </div>
                                                @endif --}}

                                            </div>
                                        </div>
                                    </div>




                                    <div class="col-xl-3 col-md-6">
                                        <div class="card bg-white text-black mb-4">
                                            <div style="display: block;align-items: center;background:white; gap:10px;text-align: center;"
                                                class="card-body">
                                                <div style="font-size: 30px;font-weight:bold;"
                                                    class="text-content-main">
                                                    {{ $nonactive_customer }}
                                                </div>

                                                <div class="title-content">
                                                    Pelanggan Nonaktif
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- End --}}
                                <div class="row">
                                    <!-- Line Chart -->
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">Total Transaksi Belanja</div>
                                            <div class="card-body">
                                                <canvas id="horizontalbarChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">Kategori Segmentasi Pelanggan</div>
                                            <div class="card-body">
                                                <canvas id="segmentChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                {{-- ROW ATAS --}}
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">RFM Analysis</div>
                                        <div class="card-body">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr style="text-align: center;">
                                                        <th>Pelanggan</th>
                                                        <th>Total Transaksi</th>
                                                        <th>Total Pengeluaran</th>
                                                        <th>Status</th>
                                                        <th>Status Member</th>
                                                        <th>Kriteria</th>
                                                    </tr>
                                                </thead>
                                                <tbody>



                                                    @foreach ($rfm_data as $sales)
                                                        <tr style="text-align: center;">
                                                            <td>{{ $sales->name }}</td>
                                                            <td>{{ $sales->total_transaction }}</td>
                                                            <td>{{ 'Rp.' . number_format($sales->total_spent) }}</td>
                                                            <td>{{ $sales->status }}</td>
                                                            <td>{{ $sales->member_status }}</td>
                                                            <td>
                                                                @if ($sales->transaction_segment == 'Champions')
                                                                    <span
                                                                        class="text-success">{{ $sales->transaction_segment }}</span>
                                                                @elseif($sales->transaction_segment == 'Loyal Customers')
                                                                    <span
                                                                        class="text-info">{{ $sales->transaction_segment }}</span>
                                                                @else
                                                                    <span
                                                                        class="text-danger">{{ $sales->transaction_segment }}</span>
                                                                @endif

                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <br>

                                {{-- ROW BAWAH --}}
                                <div class="row mt-4">

                                    {{-- <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header">Total Pendapatan by Metode Pembayaran</div>
                                            <div class="card-body">
                                                <canvas id="horizontalbarChart"></canvas>
                                            </div>
                                        </div>
                                    </div> --}}

                                </div>
                            </div>


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


<script>
    // Horizontal Bar:
    new Chart(document.getElementById('horizontalbarChart'), {
        type: 'bar',
        data: {
            labels: @json($labels_customer),
            datasets: [{
                data: @json($revenue_customer),
                backgroundColor: [
                    '#bb0239',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF'
                ],
                barThickness: 10
            }]
        },
        options: {
            indexAxis: 'y', // <-- ini yang bikin horizontal
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true
                }
            }
        }
    });




    const labels = @json($segment_labels);
    const values = @json($segment_values);

    const ctx = document.getElementById('segmentChart');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Customer per Segment',
                data: values,

                backgroundColor: [
                    '#f1c40f', // Champions
                    '#3498db', // Loyal
                    '#2ecc71', // Potential
                    '#e74c3c' // Risk Churn
                ],

                borderRadius: 6
            }]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    display: false
                },

                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.raw + ' customer';
                        }
                    }
                }
            },

            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
</script>

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
