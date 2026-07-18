<title>@yield('title', 'Kencana Bakery - Master Data Promo Campaign')</title>

<link rel="stylesheet" href="{{ asset('assets/front_end/css/admin_css.css') }}">
<div>
    <main>
        @php
            $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
            $user_permission_forbidden = in_array($session_user->role_name, ['Supervisor', 'Manager']);
            $forbidden_access = in_array($session_user->role_name, ['Casheer']);
        @endphp
        <div class="container-fluid px-4">
            <br>

            <div class="card mb-4">
                <div style="display: flex; justify-content:space-between;" class="card-header">
                    <div class="title">
                        CRM > <strong>Promo Campaign</strong>
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

                        @if ($promo_campaign->isNotEmpty())
                            @if (!$user_permission_forbidden)
                                <div class="button-add-product">
                                    <a class="btn-general" href="{{ route('promo_create') }}">Tambah Promo</a>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <div wire:poll.keep.alive.2s>

                        @if ($promo_campaign->isNotEmpty())
                            <div style="display: flex; flex-wrap: wrap; gap:10px;">
                                @foreach ($promo_campaign as $promo)
                                    <div class="card bg-light text-black mb-4">
                                        <div style="display: flex;align-items: center; gap:10px;font-weight: bold;"
                                            class="card-body">
                                            <div style="display: flex; gap:10px;" class="image-content">
                                                <img width="90" height="90"
                                                    src="{{ url('storage/' . $promo->images) }}" alt="">
                                                <div class="content-text">
                                                    <div style="width: 200px;" class="title-text">
                                                        <h5>{{ $promo->promo_name }}</h5>
                                                        <h6 style="font-size: 14px;">Kode: #{{ $promo->promo_code }}
                                                        </h6>
                                                    </div>
                                                    <p
                                                        style="font-size: 13px;color:gray; font-weight: normal;margin-bottom:5px;">

                                                        <span>Kuota:
                                                            {{ $promo->quota }}</span>
                                                        &nbsp;
                                                        <span>
                                                            Min Transaksi:
                                                            Rp.{{ number_format($promo->min_transaction) }}
                                                        </span>
                                                    </p>
                                                    <div style="font-size: 13px; font-weight: 500;margin-bottom: 0;"
                                                        class="status">
                                                        @if ($promo->status == 'Active')
                                                            <p style="margin-bottom: 0;">Status: <span
                                                                    class="text-success">Aktif</span></p>
                                                        @else
                                                            <p style="margin-bottom: 0;">Status: <span
                                                                    class="text-danger">Tidak aktif</span></p>
                                                        @endif
                                                    </div>

                                                    <div style="font-size: 13px; font-weight: 500;margin-bottom: 15px;"
                                                        class="date">
                                                        <label for="">Tanggal Berlaku</label>
                                                        <br>
                                                        <small>{{ \Carbon\Carbon::parse($promo->start_date)->format('Y-m-d') }}</small>
                                                        <span>s.d</span>
                                                        <small>
                                                            {{ \Carbon\Carbon::parse($promo->end_date)->format('Y-m-d') }}</small>
                                                    </div>


                                                    <label style="font-size: 13px; font-weight: 500;"
                                                        for="">Deskripsi</label>
                                                    <div class="description-text" style="height:90px; overflow-y:auto;">
                                                        <p style="width:200px;font-weight:400;font-size:13px;">
                                                            {{ $promo->description ?: 'Tidak ada deskripsi' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if (!$forbidden_access)
                                            <div class="card-footer d-flex align-items-center justify-content-between">
                                                @if ($promo->status == 'Active')
                                                    <a class="btn btn-warning" data-toggle="modal"
                                                        data-target="#deleteModalRewards{{ $promo->promo_code }}">
                                                        Nonaktifkan
                                                    </a>
                                                @else
                                                    <a class="btn btn-info" data-toggle="modal"
                                                        data-target="#deleteModalRewards{{ $promo->promo_code }}">
                                                        Aktifkan
                                                    </a>
                                                @endif

                                                <div style="display: flex; gap:10px;" class="flex-btn">
                                                    <a class="btn btn-primary"
                                                        href="{{ route('promo_update', $promo->promo_code) }}">Edit</a>
                                                    <a class="btn btn-danger" data-toggle="modal"
                                                        data-target="#deleteModal{{ $promo->promo_code }}">
                                                        Hapus
                                                    </a>
                                                </div>

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
                                        <h3>Belum ada Rewards</h3>
                                        @if (!$user_permission_forbidden)
                                            <p class="text-secondary">Tambah data Rewards</p>
                                            <a class="btn btn-primary" href="{{ 'rewards_create' }}">Tambah Rewards</a>
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

    @foreach ($promo_campaign as $promo)
        <div wire:ignore class="modal fade" id="deleteModal{{ $promo->promo_code }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel{{ $promo->promo_code }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Hapus data daily Promo Campaign</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Apakah anda yakin ingin menghapus Promo {{ $promo->promo_name }} ?</div>
                    <div class="modal-footer">
                        <form method="POST" action="{{ route('promo_delete', $promo->promo_code) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- update status Promo --}}

    @foreach ($promo_campaign as $promo)
        <div wire:ignore class="modal fade" id="deleteModalRewards{{ $promo->promo_code }}" tabindex="-1"
            role="dialog" aria-labelledby="exampleModalLabel{{ $promo->promo_code }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Status Promo</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        @if ($promo->status == 'Active')
                            Apakah anda yakin ingin Nonaktifkan
                            {{ $promo->promo_name }} - {{ $promo->promo_code }}
                            ?
                        @else
                            Apakah anda yakin ingin Aktifkan kembali
                            {{ $promo->promo_name }} - {{ $promo->promo_code }}
                            ?
                        @endif
                        <br>
                        <br>
                        <form method="POST"
                            action="{{ route('promo_campaign_update_status', $promo->promo_code) }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <input type="text" name="promo_code" value="{{ $promo->promo_code }}" hidden>
                                @if ($promo->status == 'Active')
                                    <input type="checkbox" name="status" value="8" required>
                                    <x-input-error :messages="$errors->get('status')" class="text-danger" />
                                    <label for="">Nonaktifkan</label>
                                @else
                                    <input type="checkbox" name="status" value="7" required>
                                    <x-input-error :messages="$errors->get('status')" class="text-danger" />
                                    <label for="">Aktifkan</label>
                                @endif
                            </div>
                            <br>

                            @if ($promo->status == 'Active')
                                <button class="btn btn-danger" type="submit">Nonaktifkan</button>
                            @else
                                <button class="btn btn-primary" type="submit">Aktifkan</button>
                            @endif

                        </form>
                    </div>

                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
    @endforeach

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
