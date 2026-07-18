<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Bill of Material</title>
    <link href="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}">
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
                                Master Data > Bill of Material > <strong> Bill of Material Detail </strong>
                            </div>
                        </div>
                        <div style="display: flex; justify-content:space-between;" class="card-header">
                            <a class="btn btn-primary"
                                href="{{ route('bill-of-material', $bill_of_material->first()->product_code) }}">Kembali</a>

                            <div class="title">
                                @if ($bill_of_material->isNotEmpty())
                                    @if (!$user_permission_forbidden)
                                        <div class="button-add-product">
                                            <a class="btn-general"
                                                href="{{ route('add_ingredients', $bill_of_material->first()->product_code) }}">Tambah
                                                Bill of Material</a>
                                        </div>
                                    @endif
                                @endif
                            </div>

                        </div>


                        <div class="card-body">
                            @if ($bill_of_material->isNotEmpty())
                                <div class="form-group">
                                    <label for=""><strong>Kode BoM (Bill of Material)</strong></label>
                                    <input type="text" value="{{ $bill_of_material->first()->ingredients_code }}"
                                        class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label for=""><strong>Grand Total</strong></label>
                                    <input type="text"
                                        value="{{ 'Rp.' . number_format($bill_of_material->first()->hpp) }}"
                                        class="form-control" readonly>
                                </div>
                                <hr>
                                <div class="table-responsive">
                                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Bahan Baku</th>
                                                <th>Quantity</th>
                                                <th>Subtotal</th>
                                                <th>Created at</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            ?>
                                            @foreach ($bill_of_material as $bom)
                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    <td>{{ $bom->material_name }}</td>
                                                    <td>{{ $bom->quantity . ' ' . $bom->unit_name }}</td>
                                                    <td> {{ 'Rp.' . number_format($bom->subtotal) ?: '-' }} </td>
                                                    <td>{{ $bom->created_at }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div style="height: 50vh; display:flex; justify-content:center; border:1px solid gray;border-radius:10px;"
                                    class="empty-transaction">

                                    <div style="display: flex;" class="empty-content">
                                        <div style="display: flex; gap:20px;margin:auto;" class="alert-info">
                                            <img width="70" height="70"
                                                src="{{ asset('assets/front_end/assets/img/null.png') }}"
                                                alt="">
                                            <div style="display: block;" class="text-content">
                                                <h3>Belum ada Bill of Material untuk Produk Ini</h3>
                                                @if (!$user_permission_forbidden)
                                                    <a class="btn btn-primary"
                                                        href="{{ route('add_ingredients', $bill_of_material->first()->product_code) }}">Tambah
                                                        Bill of Material</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>


</script>

<script src="{{ asset('assets/front_end/js/button_change.js') }}"></script>
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
