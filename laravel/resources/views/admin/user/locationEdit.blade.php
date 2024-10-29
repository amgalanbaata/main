@include('admin.head')
<html>
    <body class="sb-nav-fixed">
        @include('admin.header')
        <div id="layoutSidenav">
            @include('admin.menu')
            <div id="layoutSidenav_content">
                <div class="container mt-4">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h2>Засварлах</h2>
                            <a href="{{ route('locations.index') }}" class="btn btn-outline-secondary">Буцах</a>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('locations.update', $location->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">Гарчиг</label>
                                    <input type="text" name="title" class="form-control" value="{{ $location->title }}" required>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="comment" class="form-label">Тайлбар</label>
                                    <textarea name="comment" class="form-control" rows="3">{{ $location->comment }}</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="latitude" class="form-label">Өргөрөг</label>
                                            <input type="text" name="latitude" class="form-control" value="{{ $location->latitude }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="longitude" class="form-label">Уртраг</label>
                                            <input type="text" name="longitude" class="form-control" value="{{ $location->longitude }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label">Бохирдлын түвшин</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="color" id="colorRed" value="red" {{ $location->color == 'red' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="colorRed">Их</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="color" id="colorYellow" value="yellow" {{ $location->color == 'yellow' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="colorYellow">Дунд</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="color" id="colorGreen" value="green" {{ $location->color == 'green' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="colorGreen">Бага</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-success">Шинэчлэх</button>
                                    <a href="{{ route('locations.index') }}" class="btn btn-outline-secondary">Буцах</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
