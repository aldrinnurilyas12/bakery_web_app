<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="{{ asset('assets/front_end/css/styles.css') }}" rel="stylesheet" />
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

                </div>
            </main>
        </div>
    </div>

    @include('layouts.component_admin.footer.footer')


</body>

</html>
