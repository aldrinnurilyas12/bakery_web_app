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
    <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-matrix"></script>
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
                    <div class="card mb-4">
                        <div style="display: flex; justify-content:space-between;" class="card-header">
                            <div class="title">
                                Business Intelligence > <strong>Data Analitik</strong>
                            </div>
                        </div>
                        <div style="display: flex; justify-content:space-between;" class="card-header">
                            <div class="container mt-4">
                                <div style="display: flex; justify-content: space-between;margin-bottom:20px;"
                                    class="filter-contnent">
                                    <div class="title-content-filter">
                                        <h3 class="mb-4">Dashboard Statistik</h3>
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

                                            <div class="store-filter">
                                                <label for=""><strong>Store</strong></label>
                                                <select name="store" class="form-control" id="" required>
                                                    <option value="">=== Pilih Store ===</option>
                                                    @foreach ($stores as $st)
                                                        <option value="{{ $st->store_code }}"
                                                            {{ old('store', request('store')) == $st->store_code ? 'selected' : '' }}>
                                                            {{ $st->store_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

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
                                                    Rp. {{ number_format($total_revenue) }}
                                                </div>

                                                <div class="title-content">
                                                    Pendapatan
                                                </div>

                                                @if ($mom_revenue > 0)
                                                    <div class="mt-2">
                                                        @if ($mom_revenue > 0)
                                                            <div style="display:flex;justify-content:center;gap:20px;"
                                                                class="flex-content">
                                                                <span class="text-success fw-bold">
                                                                    <i class="fas fa-arrow-up"></i>
                                                                    {{ number_format($mom_revenue, 1) }}%
                                                                </span>
                                                                <span>+{{ 'Rp.' . number_format(abs($total_revenue_diff)) }}</span>
                                                            </div>
                                                        @elseif($mom_revenue < 0)
                                                            <span class="text-danger fw-bold">
                                                                <i class="fas fa-arrow-down"></i>
                                                                {{ number_format(abs($mom_revenue), 1) }}%
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
                                                    {{ $total_transaction }}
                                                </div>

                                                <div class="title-content">
                                                    Transaksi
                                                </div>

                                                @if ($mom_transaction > 0)
                                                    <div class="mt-2">
                                                        @if ($mom_transaction > 0)
                                                            <div style="display:flex;justify-content:center;gap:20px;"
                                                                class="flex-content">
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
                                                @endif

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-6">
                                        <div class="card bg-white text-black mb-4">
                                            <div style="display: block;align-items: center;background:white; gap:10px;text-align: center;"
                                                class="card-body">
                                                <div style="font-size: 30px;font-weight:bold;"
                                                    class="text-content-main">
                                                    {{ $total_customer }}
                                                </div>

                                                <div class="title-content">
                                                    Pelanggan
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

                                                                <span>+{{ $total_customer_diff }}</span>
                                                            </div>
                                                        @elseif($mom_customer < 0)
                                                            <span class="text-danger fw-bold">
                                                                <i class="fas fa-arrow-down"></i>
                                                                {{ number_format(abs($mom_customer), 1) }}%
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
                                            <div style="display: block;align-items: center;background:white; gap:10px;text-align: center;"
                                                class="card-body">
                                                <div style="font-size: 30px;font-weight:bold;"
                                                    class="text-content-main">
                                                    {{ $total_product }}
                                                </div>

                                                <div class="title-content">
                                                    Produk
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- End --}}

                                {{-- ROW ATAS --}}
                                <div class="row">
                                    <!-- Line Chart -->
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">Total Transaksi</div>
                                            <div class="card-body">
                                                <canvas id="lineChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="card">
                                            <div class="card-header">Total Pendapatan by Month</div>
                                            <div class="card-body">
                                                <canvas id="chartRevenueMonth"></canvas>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header">Transaksi Member vs Non Member</div>
                                            <div class="card-body">
                                                <div style="height:300px;">
                                                    <canvas id="chartTransactionMemberNonMember"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <br>
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="card">
                                            <div class="card-header">Total Pendapatan by Produk</div>
                                            <div class="card-body">
                                                <canvas id="barChart1"></canvas>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-header">Total Penjualan by Kategori</div>
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

                                    <div class="col-md-8">
                                        <div class="card">
                                            <div class="card-header">Heatmap Transaksi per Jam</div>
                                            <div class="card-body">
                                                <canvas id="heatMapChart"></canvas>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header">Total Pendapatan by Metode Pembayaran</div>
                                            <div class="card-body">
                                                <canvas id="horizontalbarChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">Penjualan Produk</div>
                                            <div class="card-body">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Nama Produk</th>
                                                            <th>Total Terjual</th>
                                                            <th>Pendapatan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        @foreach ($top_sales_products as $sales)
                                                            <tr>
                                                                <td>{{ $sales->product_name }}</td>
                                                                <td>{{ $sales->total_sales }}</td>
                                                                <td>{{ 'Rp' . number_format($sales->total_revenue) }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
<script>
    // LINE CHART
    new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Penjualan',
                data: @json($data),
                borderColor: 'blue',
                backgroundColor: 'rgba(0, 0, 255, 0.2)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1, // interval 1
                        callback: function(value) {
                            return Number.isInteger(value) ? value : '';
                        }
                    }
                }
            }
        }
    });

    // PIE CHART
    const data = @json($category_total);

    new Chart(document.getElementById('pieChart'), {
        type: 'doughnut',
        data: {
            labels: @json($labels_category),
            datasets: [{
                data: data,
                backgroundColor: [
                    '#bb0239',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF',
                    '#795548',
                    '#607D8B',
                    '#FFC107',
                    '#673AB7'
                ]
            }]
        },
        plugins: [ChartDataLabels],
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 20,
                        padding: 15
                    }
                },
                datalabels: {
                    color: 'black',
                    font: {
                        weight: 'bold',
                        size: 14
                    },
                    formatter: (value, context) => {
                        const total = context.dataset.data.reduce(
                            (sum, val) => sum + Number(val),
                            0
                        );

                        const percentage = ((value / total) * 100).toFixed(1);

                        return percentage + '%';
                    }
                }
            }
        }
    });

    // BAR CHART 1
    new Chart(document.getElementById('barChart1'), {
        type: 'bar',
        data: {
            labels: @json($labels_products),
            datasets: [{
                label: 'Pendapatan by Produk',
                data: @json($products_revenue),
                backgroundColor: '#bb0239'
            }]
        }
    });

    // Revenue by Month
    new Chart(document.getElementById('chartRevenueMonth'), {
        type: 'bar',
        data: {
            labels: @json($labels_revenue),
            datasets: [{
                label: 'Pendapatan by Bulan',
                data: @json($revenue_data),
                backgroundColor: 'rgba(0, 0, 255)'
            }]
        }
    });


    // total transaction member vs nonmember:
    new Chart(document.getElementById('chartTransactionMemberNonMember'), {
        type: 'pie',
        data: {
            labels: @json($labels_member),
            datasets: [{
                data: @json($transaction_member),
                backgroundColor: [
                    '#bb0239',
                    '#36A2EB'
                ]
            }]
        },
        plugins: [ChartDataLabels],
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 20,
                        padding: 15
                    }
                },
                datalabels: {
                    color: 'black',
                    font: {
                        weight: 'bold',
                        size: 14
                    },
                    formatter: (value, context) => {
                        const total = context.dataset.data.reduce(
                            (sum, val) => sum + Number(val),
                            0
                        );

                        const percentage = ((value / total) * 100).toFixed(1);

                        return percentage + '%';
                    }
                }
            }
        }
    });

    // Horizontal Bar:
    new Chart(document.getElementById('horizontalbarChart'), {
        type: 'bar',
        data: {
            labels: @json($labels_paymethod),
            datasets: [{
                data: @json($paycategory_total),
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

    // HEATMAP:
    const dayLabels = [
        'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'
    ];

    const heatmapData = @json($data_heatmap);

    const ctx = document.getElementById('heatMapChart');

    new Chart(ctx, {
        type: 'matrix',

        data: {
            datasets: [{
                data: heatmapData,

                borderWidth: 1,
                borderColor: '#fff',

                // ❗ WAJIB pakai FIXED dulu biar pasti muncul
                width: 25,
                height: 25,

                backgroundColor: (ctx) => {
                    const v = ctx.raw.v;

                    if (v === 0) return '#f5f5f5';
                    if (v < 5) return '#d6eaf8';
                    if (v < 10) return '#85c1e9';
                    if (v < 20) return '#3498db';
                    if (v > 20) return '#bb0239';
                    return '#bb0239';
                }
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
                        title: (items) =>
                            dayLabels[items[0].raw.y] + ' ' + items[0].raw.x + ':00',

                        label: (item) =>
                            item.raw.v + ' transaksi'
                    }
                }
            },

            scales: {
                x: {
                    type: 'linear',
                    min: 8,
                    max: 21,
                    ticks: {
                        stepSize: 1,
                        callback: v => v + ':00'
                    }
                },

                y: {
                    type: 'linear',
                    min: 0,
                    max: 6,
                    ticks: {
                        stepSize: 1,
                        callback: v => dayLabels[v]
                    }
                }
            }
        }
    });
</script>


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
