<link href="{{ asset('assets/front_end/css/styles.css') }}" rel="stylesheet" />
<script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>


<body>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link" style="font-size:14px;" href="{{ route('dashboard_main') }}">
                            <div class="sb-nav-link-icon"><i class="fa fa-tachometer" aria-hidden="true"></i>
                            </div>
                            Home
                        </a>

                        <div class="sb-sidenav-menu-heading">MASTER DATA</div>
                        <a class="nav-link" style="font-size:14px;" href="{{ route('products_data') }}">
                            <div class="sb-nav-link-icon"><i class="fa fa-cube" aria-hidden="true"></i>
                            </div>
                            Products
                        </a>

                        <a class="nav-link" style="font-size:14px;" href="{{ route('dailyproducts_data') }}">
                            <div class="sb-nav-link-icon"><i class="fa fa-list-alt" aria-hidden="true"></i>
                            </div>
                            Daily Products
                        </a>

                        <a class="nav-link" style="font-size:14px;" href="{{ route('master_category.index') }}">
                            <div class="sb-nav-link-icon"><i class="fa fa-cubes" aria-hidden="true"></i>
                            </div>
                            Category
                        </a>

                        <a class="nav-link" style="font-size:14px;" href="{{ route('raw_material') }}">
                            <div class="sb-nav-link-icon"><i class="fa fa-shopping-basket" aria-hidden="true"></i>
                            </div>
                            Raw Materials
                        </a>

                        <a class="nav-link" style="font-size:14px;" href="{{ route('master_category.index') }}">
                            <div class="sb-nav-link-icon"><i class="fa fa-random" aria-hidden="true"></i>
                            </div>
                            Production Product
                        </a>

                        <a class="nav-link" style="font-size:14px;" href="{{ route('rewards') }}">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-gift"></i>
                            </div>
                            Rewards
                        </a>

                        <a class="nav-link" style="font-size:14px;" href="{{ route('master_customers.index') }}">
                            <div class="sb-nav-link-icon"><i class="fa fa-id-card" aria-hidden="true"></i>
                            </div>
                            Customers
                        </a>

                        <a class="nav-link" style="font-size:14px;" href="{{ route('master_employee.index') }}">
                            <div class="sb-nav-link-icon"><i class="fa fa-user" aria-hidden="true"></i>
                            </div>
                            Karyawan
                        </a>

                        <a class="nav-link" style="font-size:14px;" href="{{ route('master_products.index') }}">
                            <div class="sb-nav-link-icon"><i class="fa fa-line-chart" aria-hidden="true"></i>
                            </div>
                            Reports
                        </a>

                        <div class="sb-sidenav-menu-heading">TRANSACTION</div>
                        <a class="nav-link" style="font-size:14px;" href="{{ route('transaction.index') }}">
                            <div class="sb-nav-link-icon"><i class="fa fa-exchange" aria-hidden="true"></i>
                            </div>
                            Transactions
                        </a>

                        <a class="nav-link" style="font-size:14px;" href="{{ route('transaction.index') }}">
                            <div class="sb-nav-link-icon"><i class="fa fa-shopping-basket" aria-hidden="true"></i>
                            </div>
                            Transactions Pick-up
                        </a>


                        <div class="sb-sidenav-menu-heading">CRM</div>
                        <a class="nav-link" style="font-size:14px;" href="{{ route('promo_campaign') }}">
                            <div class="sb-nav-link-icon"><i class="fa fa-tags" aria-hidden="true"></i>
                            </div>
                            Promo Campaign
                        </a>

                        <a class="nav-link" style="font-size:14px;" href="{{ route('voucher') }}">
                            <div class="sb-nav-link-icon"><i class="fa fa-ticket" aria-hidden="true"></i>
                            </div>
                            E-Voucher
                        </a>

                        <a class="nav-link" style="font-size:14px;" href="{{ route('discount.index') }}">
                            <div class="sb-nav-link-icon"><i class="fa fa-handshake"></i>
                            </div>
                            Redeem Rewards
                        </a>

                        <div class="sb-sidenav-menu-heading">LAINNYA</div>
                        <a class="nav-link" style="font-size:14px;" href="{{ route('users_data') }}">
                            <div class="sb-nav-link-icon"><i class="fa fa-users" aria-hidden="true"></i>
                            </div>
                            Akun Pengguna
                        </a>

                        <div class="sb-sidenav-menu-heading">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="btn btn-danger" type="submit">Log Out</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    {{ app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->username }}
                </div>
            </nav>
        </div>

    </div>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

        body {
            font-family: "DM Sans", serif;
        }
    </style>
</body>


{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('assets/front_end/assets/demo/chart-area-demo.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/demo/chart-bar-demo.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
    crossorigin="anonymous"></script>
<script src="{{ asset('assets/front_end/js/datatables-simple-demo.js') }}"></script> --}}
