<div>
    <main>
        <div class="container-fluid px-4">
            <br>

            <div class="card mb-4">
                <div style="display: flex; justify-content:space-between;" class="card-header">
                    <div class="title">
                        Master Data / <a href="{{ route('master_products.index') }}">Rewards Data</a>
                    </div>

                    @if ($rewards->isNotEmpty())
                        <div class="button-add-product">
                            <a class="btn btn-primary" href="{{ route('rewards_create') }}">Tambah Rewards</a>
                        </div>
                    @endif
                </div>

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
                                                    <p style="font-size: 13px;color:gray; font-weight: normal;">
                                                        Point:
                                                        {{ $reward->point }} &nbsp; <span>Kuota:
                                                            {{ $reward->quota }}</span>
                                                    </p>
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
                                        <div class="card-footer d-flex align-items-center justify-content-between">
                                            <a class="small text-black"
                                                href="{{ route('rewards_update', $reward->rewards_code) }}">Edit</a>

                                            <a class="btn btn-danger" href="#" data-toggle="modal"
                                                data-target="#deleteModal{{ $reward->rewards_code }}">Hapus</a>
                                        </div>
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
                                        <p class="text-secondary">Tambah data Rewards</p>
                                        <a class="btn btn-primary" href="{{ 'rewards_create' }}">Tambah Rewards</a>
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
        <div wire:ignore class="modal fade" id="deleteModal{{ $reward->rewards_code }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel{{ $reward->rewards_code }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Hapus data rewards</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Apakah anda yakin ingin menghapus data Rewards
                        {{ $reward->rewards_code . '  - ' . $reward->rewards_name }}?
                    </div>
                    <div class="modal-footer">
                        <form method="POST" action="{{ route('rewards_delete', $reward->rewards_code) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit">Hapus</button>
                        </form>
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
    @endif



</div>
