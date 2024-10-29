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
                            <h2>Нэмэх</h2>
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

                            <form action="{{ route('locations.store') }}" method="POST">
                                @csrf

                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">Гарчиг</label>
                                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="comment" class="form-label">Тайлбар</label>
                                    <textarea name="comment" id="comment" class="form-control" rows="3">{{ old('comment') }}</textarea>
                                    @error('comment')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="latitude" class="form-label">Өргөрөг</label>
                                            <input type="text" name="latitude" id="latitude" class="form-control" value="{{ old('latitude') }}" required>
                                            @error('latitude')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="longitude" class="form-label">Уртраг</label>
                                            <input type="text" name="longitude" id="longitude" class="form-control" value="{{ old('longitude') }}" required>
                                            @error('longitude')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="form-label">Бохирдлын түвшин</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="color" id="yellow" value="yellow" {{ old('color') == 'yellow' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="yellow">Их</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="color" id="green" value="green" {{ old('color') == 'green' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="green">Дунд</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="color" id="red" value="red" {{ old('color') == 'red' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="red">Бага</label>
                                        </div>
                                    </div>
                                    @error('color')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-primary">Нэмэх</button>
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
