@extends('layouts.main_views.auth.auth')

@section('title', 'Kencana Bakery | Daftar Akun')

@section('content')
    <div style="display: flex; justify-content: center;" class="d-flex-center">
        <div class="img-logo">
            <img src="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}" alt="Kencana Bakery Logo"
                class="logo" />
        </div>
    </div>
    <div style="display: flex; justify-content: left;" class="d-flex-center">
        <h2 style="font-weight: bold;font-size: 20px;">Register Account</h2>
    </div>

    <form method="POST" action="{{ route('register_member_account') }}">
        @csrf

        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="name" value="{{ old('name') }}" autocomplete="off"
                placeholder="Masukan Nama anda">
            <x-input-error :messages="$errors->get('name')" class="text-danger" />
        </div>

        <div class="form-group">
            <label>Alamat</label>
            <input type="text" name="address" value="{{ old('address') }}" autocomplete="off"
                placeholder="Masukan Alamat anda">
            <x-input-error :messages="$errors->get('address')" class="text-danger" />
        </div>

        <div class="form-group">
            <label>No. Telepon</label>
            <input type="number" name="phone_number" value="{{ old('phone_number') }}" autocomplete="off"
                placeholder="Masukan No.Hp anda awali dengan (62) contoh 6289678998789">
            <x-input-error :messages="$errors->get('phone_number')" class="text-danger" />
        </div>

        <div class="form-group">
            <label>Tanggal lahir</label>
            <input type="date" name="birth_date">
            <x-input-error :messages="$errors->get('birth_date')" class="text-danger" />
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" autocomplete="off"
                placeholder="Masukan alamat email anda">
            <x-input-error :messages="$errors->get('email')" class="text-danger" />
        </div>


        <div class="form-group">
            <label>Kata Sandi</label>
            <input type="password" name="password" autocomplete="off" placeholder="Buat kata sandi anda">
            <x-input-error :messages="$errors->get('password')" class="text-danger" />
        </div>


        <button type="submit">Daftar Akun</button>
    </form>

    <div class="link">
        <a href="{{ route('login_app') }}">Login</a>
        <br>
        <div class="group-terms">
            <a class="terms" href="#" data-toggle="modal" data-target="#privacy">Privacy Policy</a>
        </div>
    </div>


    <div class="modal fade" id="privacy" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div style="display: flex; justify-content: space-between;" class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Kebijakan Privasi Kami</h5>
                    <button style="width: 10px; height:10px;justify-items: center;background:none;" type="button"
                        data-dismiss="modal" aria-label="Close">
                        <span style="color: black;" class="x-btn" aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" style="max-height: 400px; overflow-y: auto; font-size: 14px; line-height: 1.6;">
                    <p>
                        Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, menyimpan,
                        dan melindungi data pribadi pengguna saat melakukan pendaftaran dan menggunakan layanan kami.
                    </p>

                    <h6><strong>1. Informasi yang Kami Kumpulkan</strong></h6>
                    <p>
                        Saat Anda mendaftar akun, kami dapat mengumpulkan data pribadi seperti:
                        nama, alamat, alamat email, dan nomor telepon.
                    </p>

                    <h6><strong>2. Penggunaan Informasi</strong></h6>
                    <p>
                        Informasi yang dikumpulkan digunakan untuk:
                    </p>
                    <ul>
                        <li>Membuat dan mengelola akun pengguna</li>
                        <li>Memverifikasi identitas pengguna</li>
                        <li>Menyediakan dan meningkatkan layanan</li>
                        <li>Menghubungi pengguna terkait akun atau layanan</li>
                        <li>Menjaga keamanan sistem</li>
                    </ul>

                    <h6><strong>3. Perlindungan Data</strong></h6>
                    <p>
                        Kami berkomitmen untuk menjaga keamanan data pribadi Anda dengan langkah-langkah
                        pengamanan teknis dan organisasi yang wajar.
                    </p>

                    <h6><strong>4. Penyimpanan Data</strong></h6>
                    <p>
                        Data pribadi akan disimpan selama akun Anda aktif atau selama diperlukan sesuai
                        dengan ketentuan hukum yang berlaku.
                    </p>

                    <h6><strong>5. Pembagian Data</strong></h6>
                    <p>
                        Kami tidak menjual atau menyebarkan data pribadi Anda kepada pihak ketiga,
                        kecuali diwajibkan oleh hukum atau untuk keperluan operasional layanan.
                    </p>

                    <h6><strong>6. Hak Pengguna</strong></h6>
                    <p>
                        Anda berhak mengakses, memperbarui, atau menghapus data pribadi Anda melalui akun
                        atau dengan menghubungi kami.
                    </p>

                    <h6><strong>7. Perubahan Kebijakan</strong></h6>
                    <p>
                        Kebijakan Privasi ini dapat diperbarui dari waktu ke waktu. Perubahan akan
                        diinformasikan melalui layanan kami.
                    </p>

                    <p style="margin-top: 15px;">
                        Dengan menggunakan layanan ini, Anda menyetujui Kebijakan Privasi kami.
                    </p>
                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" aria-label="Close">Tutup</button>
                </div>
            </div>
        </div>
    </div>

@endsection
