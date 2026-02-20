<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Master Data Rewards</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}">
</head>

<div>
    <main>
        @php
            $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
            $user_permission_forbidden = in_array($session_user->role_name, ['Supervisor', 'Manager']);
            $filter_forbidden_access = in_array($session_user->role_name, ['Staff', 'Casheer']);
        @endphp
        <div class="container-fluid px-4">
            <br>

            <div class="card mb-4">
                <div style="display: flex; justify-content:space-between;" class="card-header">
                    <div class="title">
                        Master Data / <a href="{{ route('master_products.index') }}">Rewards Data Store</a>
                    </div>

                    @if ($rewards->isNotEmpty())
                        @if (!$user_permission_forbidden)
                            <div style="display: flex;gap:10px;" class="button-add-product">
                                <a class="btn btn-info" href="{{ route('master_rewards.index') }}">Master Data
                                    Rewards</a>
                                <a class="btn btn-primary" href="{{ route('rewards_create') }}">Tambah Rewards</a>
                            </div>
                        @endif
                    @endif
                </div>
                @if (!$filter_forbidden_access)
                    <div class="card-header">
                        <div class="title">
                            <div class="filter-data">
                                <label for=""><strong>Pilih Store</strong></label>
                                <br>
                                <div style="display: flex; gap:10px;margin-top:5px;" class="d-flex-container-filter">
                                    <form action="{{ route('filter_rewards') }}" method="GET">
                                        <div style="display:flex;gap:20px;" class="d-flex-content">
                                            <select style="width:max-content;" name="filter" id=""
                                                class="form-control">
                                                <option value="">=== Pilih Data ===</option>
                                                <option value="all"
                                                    {{ request('filter') == 'all' ? 'selected' : '' }}>
                                                    Semua Store
                                                </option>
                                                @foreach ($store as $shop)
                                                    <option value="{{ $shop->id }}"
                                                        {{ request('filter') == $shop->id ? 'selected' : '' }}>
                                                        {{ $shop->store_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button class="btn btn-primary">Pilih</button>
                                        </div>
                                    </form>

                                    {{-- <div class="excel-file">
                                     <form action="{{ route('export_transaction_excel') }}" method="POST">
                                         @csrf
                                         <input type="hidden" name="filter_transaction"
                                             value="{{ request('filter_transaction') }}">
                                         <button type="submit" class="btn btn-success">
                                             <i class="fas fa-file-excel"></i>
                                             &nbsp; Excel
                                         </button>
                                     </form>
                                 </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="card-body">
                    <div wire:poll.keep.alive.2s>

                        @if ($rewards->isNotEmpty())
                            <div style="display: flex; flex-wrap: wrap; gap:10px;">
                                @foreach ($rewards as $reward)
                                    <div class="card bg-light text-black mb-4">
                                        <div style="display: flex;align-items: center; gap:10px;font-weight: bold;"
                                            class="card-body">
                                            <div style="display: flex; gap:10px;" class="image-content">
                                                <img width="90" height="90"
                                                    src="{{ url('storage/' . $reward->images) }}" alt="">
                                                <div class="content-text">
                                                    <div style="width: 200px;" class="title-text">
                                                        <h5 style="font-size:15px;">{{ $reward->rewards_name }}</h5>
                                                    </div>
                                                    <p
                                                        style="font-size: 13px;color:gray; font-weight: normal;margin-bottom:5px;">
                                                        Point:
                                                        {{ $reward->point }} &nbsp;
                                                        <span>Kuota:
                                                            {{ $reward->stock }}</span>
                                                    </p>
                                                    <div style="font-size: 13px; font-weight: 500;margin-bottom: 0;"
                                                        class="status">
                                                        @if ($reward->status_name == 'Active')
                                                            <p style="margin-bottom: 0;">Status: <span
                                                                    class="text-success">Aktif</span></p>
                                                        @else
                                                            <p style="margin-bottom: 0;">Status: <span
                                                                    class="text-danger">Tidak aktif</span></p>
                                                        @endif
                                                    </div>

                                                    <div style="font-size: 13px; font-weight: 500;margin-bottom: 0;"
                                                        class="status">
                                                        @if ($reward->store_name)
                                                            <p style="margin-bottom: 0;">Store: <span
                                                                    class="text">{{ $reward->store_name }}</span>
                                                            </p>
                                                        @else
                                                            <p style="margin-bottom: 0;">Store: <span
                                                                    class="text-danger">-</span></p>
                                                        @endif
                                                    </div>
                                                    <div style="font-size: 13px; font-weight: 500;" class="date">
                                                        <label for="">Tanggal Berlaku</label>
                                                        <br>
                                                        <small>{{ \Carbon\Carbon::parse($reward->start_date)->format('Y-m-d') }}</small>
                                                        <span>s.d</span>
                                                        <small>
                                                            {{ \Carbon\Carbon::parse($reward->end_date)->format('Y-m-d') }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if (!$user_permission_forbidden)
                                            <div class="card-footer d-flex align-items-center justify-content-between">
                                                <a class="small text-black"
                                                    href="{{ route('rewards_update', $reward->reward_store_code) }}">Edit
                                                    in store</a>

                                                @if ($reward->status_name == 'Active')
                                                    <a class="btn btn-danger" data-toggle="modal"
                                                        data-target="#deleteModalRewards-{{ $reward->rewards_code }}-{{ $reward->store_code }}">
                                                        Nonaktifkan
                                                    </a>
                                                @else
                                                    <a class="btn btn-primary" data-toggle="modal"
                                                        data-target="#deleteModalRewards-{{ $reward->rewards_code }}-{{ $reward->store_code }}">
                                                        Aktifkan
                                                    </a>
                                                @endif
                                            </div>

                                            <div class="card-footer d-flex align-items-center justify-content-between">
                                                <a class="small text-black"
                                                    href="{{ route('rewards_master_update', $reward->rewards_code) }}">Edit
                                                    Master Data</a>
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

    @foreach ($rewards as $reward)
        <div wire:ignore class="modal fade"
            id="deleteModalRewards-{{ $reward->rewards_code }}-{{ $reward->store_code }}" tabindex="-1"
            role="dialog" aria-labelledby="exampleModalLabel-{{ $reward->rewards_code }}-{{ $reward->store_code }}"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Rewards</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        @if ($reward->status_name == 'Active')
                            Apakah anda yakin ingin men-Nonaktif Reward
                            {{ $reward->rewards_name }} - {{ $reward->store_name }}
                            ?
                        @else
                            Apakah anda yakin ingin aktifkan Reward
                            {{ $reward->rewards_name }} - {{ $reward->store_name }}
                            ?
                        @endif
                        <br>
                        <br>
                        <form method="POST"
                            action="{{ route('rewards_nonactive', [
                                'reward' => $reward->rewards_code,
                                'store' => $reward->store_code,
                            ]) }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                @if ($reward->status_name == 'Active')
                                    <input type="checkbox" name="status" value="8">
                                    <x-input-error :messages="$errors->get('status')" class="text-danger" />
                                    <input type="text" name="store" value="{{ $reward->store_code }}" hidden>
                                    <label for="">Nonaktifkan</label>
                                @else
                                    <input type="checkbox" name="status" value="7">
                                    <x-input-error :messages="$errors->get('status')" class="text-danger" />
                                    <input type="text" name="store" value="{{ $reward->store_code }}" hidden>
                                    <label for="">Aktifkan</label>
                                @endif
                            </div>
                            <br>

                            @if ($reward->status_name == 'Active')
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
