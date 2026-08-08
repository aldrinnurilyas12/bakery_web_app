<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Noto+Serif:ital,wght@0,100..900;1,100..900&family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap"
        rel="stylesheet">
    <link href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\front_end\assets\logo\kencanabakery_logo2.png') }}">
    <style>
        * {
            box-sizing: border-box;
            font-family: "Noto Serif", serif;

        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f4f6f8;
        }

        .auth-container {
            width: 50%;
            background: #fff;
            padding: 30px;
            margin-top: auto;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        textarea {
            resize: vertical;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            background: #bb0239;
            ;
            color: #fff;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .group-terms {
            display: flex;
            gap: 10px;
            justify-content: center;
            color: rgb(45, 45, 45);
            margin-top: 20px;
            text-decoration: underline;
        }

        .group-terms a.terms {
            color: rgb(2, 2, 2);
        }

        .link {
            text-align: center;
            margin-top: 15px;
        }

        .link a {
            color: #bb0239;
            ;
            text-decoration: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .auth-container {
                width: 80%;
            }
        }

        @media (max-width: 480px) {
            .auth-container {
                width: 95%;
                padding: 20px;
            }
        }
    </style>
</head>

<body>

    <div class="auth-container">
        @yield('content')

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


    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/js/js/demo/datatables-demo.js') }}"></script>
</body>

</html>
