<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kencana Bakery - Tambah Item</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/front_end/css/admin_css.css') }}">
</head>

<body class="sb-nav-fixed">
    @include('layouts.component_admin.navbar.navbar')
    @include('layouts.component_admin.sidebar.sidebar')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <br>
                <div class="container-fluid px-4">
                    <h4>Ubah Data Item</h4>
                    <hr>
                    <form id="formGeneralMaster" action="{{ route('edit_item', $items->item_code) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label><strong>Nama Item</strong></label>
                            <input type="text" name="name" class="form-control" value="{{ $items->name }}"
                                placeholder="Masukan nama item" autocomplete="off" required>
                            <x-input-error :messages="$errors->get('name')" class="text-danger" />
                        </div>

                        <div class="form-group">
                            <label><strong>Kategori Item</strong></label>
                            <input type="text" class="form-control" value="{{ $items->category_name }}" readonly>
                        </div>

                        @if ($items->item_category == 1)
                            <div class="form-group">
                                <label><strong>Kategori Item</strong></label>
                                <select name="item_category" class="form-control" id="selectItemCategory" required>
                                    <option value="">=== Pilih Kategori Bahan Baku ===</option>
                                    @foreach ($category_item as $cat)
                                        <option value="{{ $cat->id }}">
                                            {{ $cat->category_name . ' => ' . $cat->description }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('item_category')" class="text-danger" />
                            </div>
                        @endif

                        <div id="showMassa" class="form-group">
                            <label><strong>Massa</strong></label>
                            <select name="weight_type" class="form-control">
                                <option value="">=== Pilih Massa Bahan Baku ===</option>
                                <option value="pcs">Pcs</option>
                                <option value="miligram">Miligram</option>
                                <option value="gram">Gram</option>
                                <option value="kilogram">Kilogram</option>
                                <option value="quintal">Quintal</option>
                                <option value="ton">Ton</option>
                                <option value="sachet">Sachet</option>
                                <option value="pack">Pack</option>
                                <option value="liter">Liter</option>
                                <option value="mililiter">Mililiter</option>
                            </select>
                            <x-input-error :messages="$errors->get('weight_type')" class="text-danger" />
                        </div>

                        <button id="btnMaster" type="submit" class="btn-general"><span class="btn-text">Simpan
                                Data</span>
                            <span class="spinner"></span></button>
                    </form>
                    <br>
                    <br>
                </div>
            </main>
</body>
<script src="{{ asset('assets/front_end/js/button_change.js') }}"></script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

    body {
        font-family: "DM Sans", serif;
    }

    #showMassa {
        display: none;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const selectCategory = document.getElementById("selectItemCategory");
        const showMassa = document.getElementById("showMassa");

        // Pastikan elemen ada sebelum lanjut
        if (!selectCategory || !showMassa) return;


        function toggleMassa() {
            const value = selectCategory.value;

            // Gunakan == agar aman (string "2" atau number 2 tetap match)
            if (value === "1") {
                showMassa.style.display = "block";
            } else {
                // Kosongkan agar kembali ke default CSS (lebih aman daripada "block")
                showMassa.style.display = "";
            }
        }

        // Initial check
        toggleMassa();

        // Event listener
        selectCategory.addEventListener("change", toggleMassa);
    });
</script>




</html>
