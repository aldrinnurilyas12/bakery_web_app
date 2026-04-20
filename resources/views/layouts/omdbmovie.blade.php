<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OmdbServices</title>
    <link href="{{ asset('assets/front_end/assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/front_end/assets/vendor/jquery/jquery.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container">
        <div class="movie">
            <div class="card">
                <div class="search-movie">
                    <div class="form-group">
                        <form action="{{ route('search-movies') }}" method="GET" enctype="multipart/form-data">
                            <input name="title" type="text" class="form-control">
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </form>
                    </div>
                </div>

                <div class="result">
                    @if (isset($movies['Search']))
                        <h3>Hasil penacarian film</h3>
                        @foreach ($movies['Search'] as $movie)
                            <div class="card-movies">
                                <h4>{{ $movie['Title'] }}</h4>
                                <p>{{ $movie['Year'] }}</p>

                                <img src="{{ $movie['Poster'] }}" width="100">
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>

</html>
