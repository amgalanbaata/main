@include('admin.head')
<html>
    <body class="sb-nav-fixed">
        @include('admin.header')
        <div id="layoutSidenav">
            @include('admin.menu')
            <div id="layoutSidenav_content">
                <div class="container mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="mb-0">Байршил</h1>
                        <a href="{{ route('locations.create') }}" class="btn btn-lg btn-primary">Цэг нэмэх</a>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table-Primary">
                                    <tr>
                                        <th>ID</th>
                                        <th>Гарчиг</th>
                                        <th>Тайлбар</th>
                                        <th>Өргөрөг</th>
                                        <th>Уртраг</th>
                                        <th>Өнгө</th>
                                        <th>Үйлдлүүд</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($locations as $location)
                                        <tr>
                                            <td>{{ $location->id }}</td>
                                            <td>{{ $location->title }}</td>
                                            <td>{{ $location->comment }}</td>
                                            <td>{{ $location->latitude }}</td>
                                            <td>{{ $location->longitude }}</td>
                                            <td>
                                                {{-- <span class="badge" style="background-color: {{ $location->color }}">{{ ucfirst($location->color) }}</span> --}}
                                                {{ $location->color }}
                                            </td>
                                            <td>
                                                <a href="{{ route('locations.edit', $location->id) }}" class="btn btn-ls btn-Info">Засах</a>
                                                <form action="{{ route('locations.destroy', $location) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-ls btn-danger" onclick="return confirm('Та энэ байршлыг устгахдаа итгэлтэй байна уу?')">Устгах</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
