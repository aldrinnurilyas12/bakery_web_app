<title>@yield('title', 'Kencana Bakery - Master Data E-Voucher')</title>
<div>
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
                        CRM > <strong>E-Voucher</strong>
                    </div>
                    <div style="display: flex;gap:10px;" class="flex-content">
                        @if ($module_documentation)
                            <div style="align-self: center; background: rgb(222, 222, 255);padding:8px; border-radius: 5px;"
                                class="documentation-module">
                                <a title="Dokumentasi Modul"
                                    href="{{ route('show_module_documentation', $module_documentation->url_path) }}">
                                    <i aria-label="Module Documentation" class="fa fa-file"></i>
                                </a>
                            </div>
                        @endif
                        @if (!$user_permission_forbidden)
                            <div class="button-add-product">
                                <a class="btn-general" href="{{ route('customer_voucher_birthday') }}">Bagikan E-Voucher
                                    Ulang
                                    Tahun</a>
                            </div>
                        @endif
                        @if ($vouchers->isNotEmpty())
                            @if (!$user_permission_forbidden)
                                <div class="button-add-product">
                                    <a class="btn-general" href="{{ route('voucher_create') }}">Tambah Voucher</a>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <div wire:poll.keep.alive.2s>

                        @if ($vouchers->isNotEmpty())
                            <div style="display: flex; flex-wrap: wrap; gap:10px;">
                                @foreach ($vouchers as $voucher)
                                    <div class="card bg-light text-black mb-4">
                                        <div style="display: flex;align-items: center; gap:10px;font-weight: bold;"
                                            class="card-body">
                                            <div style="display: flex; gap:10px;" class="image-content">
                                                <div style="display:block;" class="content-image">
                                                    <img style="margin-bottom: 10px;" width="90" height="90"
                                                        src="{{ url('storage/' . $voucher->qr_code) }}" alt="">
                                                    <br>
                                                    @if ($voucher->total_available == 0)
                                                        <div style="background: none; border: 1px solid rgb(255, 0, 0); border-radius: 2px; padding:2px;width:max-content;font-size:14px;"
                                                            class="expired-status">
                                                            <small class="text-danger">Kuota Habis</small>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="content-text">
                                                    <div style="width:100%; display:flex; justify-content: space-between;"
                                                        class="title-text">
                                                        <h5 style="font-size:15px;width:200px;">
                                                            {{ $voucher->voucher_name }}</h5>
                                                        @if ($voucher->voucher_type == 'regular')
                                                            <div style="background: blue;color:white;  border-radius: 2px; padding:2px;width:max-content;font-size:12px;"
                                                                class="expired-status">
                                                                <span>Regular</span>
                                                            </div>
                                                        @else
                                                            <div style="background:red;color:white;  border-radius: 2px; padding:2px;width:max-content;font-size:12px;"
                                                                class="expired-status">
                                                                <span>Ulang Tahun</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <p
                                                        style="font-size: 13px;color:gray; font-weight: normal;margin-bottom:8px;">
                                                        @if ($voucher->min_transaction > 0)
                                                            <span>Min Transaksi:
                                                                {{ 'Rp.' . number_format($voucher->min_transaction) }}</span>
                                                            &nbsp;

                                                            <br>
                                                        @endif
                                                    <div
                                                        style="display: flex; gap:20px; font-size: 13px;color:gray; font-weight: normal;margin-bottom:8px;">
                                                        @if ($voucher->discount)
                                                            <span>Diskon:
                                                                {{ $voucher->discount . '%' }}</span>
                                                        @elseif($voucher->nominal)
                                                            <span>Potongan:
                                                                {{ 'Rp.' . number_format($voucher->nominal) }}
                                                            @else
                                                        @endif
                                                        <span>Kuota:
                                                            {{ $voucher->quota }}</span>
                                                    </div>
                                                    <div style="font-size: 13px;color:gray; font-weight: normal;margin-bottom:8px;"
                                                        class="info-detail">
                                                        <span>Dibagikan:
                                                            {{ $voucher->total_voucher_shared ?: 0 }}</span> &nbsp;
                                                        <span>Tersedia:
                                                            {{ $voucher->total_available ?: 0 }}</span> &nbsp;
                                                        <span>Redeem:
                                                            {{ $voucher->total_redeem ?: '-' }}</span>
                                                    </div>
                                                    </p>

                                                    <div style="font-size: 13px; font-weight: 500;margin-bottom: 0;"
                                                        class="status">
                                                        @if ($voucher->status_name == 'Inactive')
                                                            <div style="display: flex; gap:10px; margin-bottom: 5px;"
                                                                class="text-info-status">
                                                                <p style="margin-bottom: 0;">Status: <span
                                                                        class="text-danger">Tidak aktif</span>

                                                                </p>
                                                                <p style="margin-bottom: 0;">Kategori : <span>
                                                                        {{ $voucher->voucher_type }}</span>
                                                                </p>
                                                            </div>
                                                        @else
                                                            <div style="display: flex; gap:10px;margin-bottom: 5px;"
                                                                class="text-info-status">
                                                                <p style="margin-bottom: 0;">Status: <span
                                                                        class="text-success">Aktif</span>
                                                                    </span>
                                                                </p>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div style="font-size: 13px; font-weight: 500;" class="date">
                                                        <label for="">Tanggal Berlaku</label>
                                                        <br>
                                                        <small>{{ \Carbon\Carbon::parse($voucher->start_date)->format('Y-m-d') }}</small>
                                                        <span>s.d</span>
                                                        <small>
                                                            {{ \Carbon\Carbon::parse($voucher->end_date)->format('Y-m-d') }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if (!$user_permission_forbidden)
                                            <div class="card-footer d-flex align-items-center justify-content-between">


                                                @if ($voucher->status_name == 'Inactive')
                                                    {{-- <a class="btn btn-success" href="#" data-toggle="modal"
                                                        data-target="#deleteModalVoucher{{ $voucher->voucher_code }}">Aktifkan
                                                        Kembali
                                                    </a> --}}
                                                @else
                                                    @if (!$cek_redeem_voucher)
                                                        <a class="small text-black"
                                                            href="{{ route('voucher_update', $voucher->voucher_code) }}">Edit</a>
                                                        <a class="btn btn-primary" href="#" data-toggle="modal"
                                                            data-target="#deleteModalVoucher{{ $voucher->voucher_code }}">Nonaktif
                                                        </a>
                                                    @endif
                                                @endif

                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div
                                style="height: 50vh; display:flex; justify-content:center; border:1px solid gray; border-radius:10px;">
                                <div style="display: flex; gap:20px; margin:auto;" class="alert-info">
                                    <img width="70" height="70"
                                        src="{{ asset('assets/front_end/assets/img/null.png') }}" alt="">
                                    <div>
                                        <h3>Belum ada Voucher</h3>
                                        @if (!$user_permission_forbidden)
                                            <p class="text-secondary">Tambah data Voucher</p>
                                            <a class="btn btn-primary" href="{{ 'voucher_create' }}">Tambah Voucher</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </main>

    @foreach ($vouchers as $voucher)
        <div wire:ignore class="modal fade" id="deleteModalVoucher{{ $voucher->voucher_code }}" tabindex="-1"
            role="dialog" aria-labelledby="exampleModalLabel{{ $voucher->voucher_code }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update E-Voucher</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        @if ($voucher->status_name == 'Inactive')
                            Apakah anda yakin ingin aktifkan E-Voucher
                            {{ $voucher->voucher_code . ' - ' . $voucher->voucher_name }}
                            ?
                        @else
                            Apakah anda yakin ingin men-Nonaktif E-Voucher
                            {{ $voucher->voucher_code . ' - ' . $voucher->voucher_name }}
                            ?
                        @endif
                        <br>
                        <br>
                        <form id="formGeneralMaster" method="POST"
                            action="{{ route('nonactive_voucher', $voucher->voucher_code) }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                @if ($voucher->status_name == 'Inactive')
                                    <input type="checkbox" name="status" value="7">
                                    <label for="">Aktifkan</label>
                                @else
                                    <input type="checkbox" name="status" value="8">
                                    <label for="">Nonaktifkan</label>
                                @endif
                            </div>
                            <br>

                            @if ($voucher->status_name == 'Inactive')
                                <button id="btnMaster" type="submit" class="btn-general"><span
                                        class="btn-text">Aktifkan</span>
                                    <span class="spinner"></span></button>
                            @else
                                <button id="btnMaster" type="submit" class="btn-general"><span
                                        class="btn-text">Nonaktifkan</span>
                                    <span class="spinner"></span></button>
                            @endif

                        </form>
                    </div>


                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <script src="{{ asset('assets/front_end/js/button_change.js') }}"></script>

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



</div>
