<link href="{{ asset('assets/front_end/css/styles.css') }}" rel="stylesheet" />
<script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>


<body>
    @php
        $IT_GUY =
            app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->position_name ==
            'IT Developer';
        $cashier =
            app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->position_name == 'Casheer';
        $main_menu = DB::table('main_menu')->where('location', 'admin')->get();

        if (!$IT_GUY) {
            if ($cashier) {
                $submenu = DB::table('submenu as s')
                    ->whereIn('main_menu', ['1', '2', '3', '5', '9'])
                    ->where('status', 7)
                    ->whereNotIn('id', ['9', '15', '16', '26', '29', '33', '34', '36', '37', '94'])
                    ->orderBy('s.submenu_name', 'ASC')
                    ->get();
            } else {
                $submenu = DB::table('submenu as s')
                    ->where('status', 7)
                    ->where('main_menu', '<>', 10)
                    ->whereNotIn('id', ['15', '16', '94'])
                    ->orderBy('s.submenu_name', 'ASC')
                    ->get();
            }
        } else {
            $submenu = DB::table('submenu as s')
                ->where('status', 7)
                ->where('main_menu', '<>', 10)
                ->orderBy('s.submenu_name', 'ASC')
                ->get();
        }

    @endphp
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link" style="font-size:14px;" href="{{ route('dashboard_main') }}">
                            <div class="sb-nav-link-icon"><i style="color:black;" class="fa fa-tachometer"
                                    aria-hidden="true"></i>
                            </div>
                            Home
                        </a>

                        @foreach ($main_menu as $main)
                            @php
                                $hasSubmenu = $submenu->where('main_menu', $main->id)->count();
                            @endphp
                            @if ($hasSubmenu == 0)
                                @continue
                            @endif
                            <div class="sb-sidenav-menu-heading">{{ $main->menu_name }}</div>
                            @foreach ($submenu as $sub)
                                @if ($main->id == $sub->main_menu)
                                    @if (
                                        $main->menu_name == 'Others' &&
                                            app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->role_name != 'IT Developer')
                                        @continue
                                    @endif

                                    <a class="nav-link {{ request()->is($sub->submenu_link . '*') ? 'active' : '' }}"
                                        style="font-size:14px;" href="{{ url($sub->submenu_link) }}">

                                        <div class="sb-nav-link-icon">
                                            <i class="{{ $sub->icon }}"></i>
                                        </div>

                                        {{ $sub->submenu_name }}
                                    </a>
                                @endif
                            @endforeach
                        @endforeach




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

        /* DEFAULT (belum diklik) */
        .sidebar-link {
            color: #212529;
            border-radius: 6px;
            margin: 2px 8px;
            transition: all 0.2s ease-in-out;
        }

        .sidebar-link .sb-nav-link-icon i {
            color: #000000;
            /* HITAM saat belum active */
        }

        /* HOVER */
        .sidebar-link:hover {
            background-color: #bb0239;
            color: #ffffff;
        }

        .sidebar-link:hover .sb-nav-link-icon i {
            color: #ffffff;
        }

        /* ACTIVE (text + bg) */

        /* ICON DEFAULT (hitam sebelum klik) */
        .sb-sidenav-light .sb-sidenav-menu .nav-link .sb-nav-link-icon,
        .sb-sidenav-light .sb-sidenav-menu .nav-link .sb-nav-link-icon i {
            color: #000000;
        }

        /* ICON ACTIVE (PUTIH SAAT KLIK) */
        .sb-sidenav-light .sb-sidenav-menu .nav-link.active .sb-nav-link-icon,
        .sb-sidenav-light .sb-sidenav-menu .nav-link.active .sb-nav-link-icon i {
            color: #ffffff !important;
        }

        /* BACKGROUND + TEXT ACTIVE */
        .sb-sidenav-light .sb-sidenav-menu .nav-link.active {
            background-color: #bb0239;
            color: #ffffff;
            font-weight: 600;
            border-radius: 5px;
            padding: 5px;
        }
    </style>
</body>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const activeLink = document.querySelector(
            '.sb-sidenav-menu .nav-link.active'
        );

        if (activeLink) {
            activeLink.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }
    });
</script>



{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('assets/front_end/assets/demo/chart-area-demo.js') }}"></script>
<script src="{{ asset('assets/front_end/assets/demo/chart-bar-demo.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
    crossorigin="anonymous"></script>
<script src="{{ asset('assets/front_end/js/datatables-simple-demo.js') }}"></script> --}}
