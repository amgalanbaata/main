@include('admin.head')
<html>
    <body class="sb-nav-fixed">
        @include('admin.header')
        <div id="layoutSidenav">
            @include('admin.menu')
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Хэрэглэгч нэмэх</h1>
                        <div class="card mb-4">
                            <div class="card-body">
                                <form method="POST" action="{{ route('users.store') }}">
                                    @csrf

                                    <div class="form-group">
                                        <label for="username">Нэр</label>
                                        <input type="text" class="form-control" id="username" name="username" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="phone">Утас</label>
                                        <input type="text" class="form-control" id="phone" name="phone" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Имэйл</label>
                                        <input type="text" class="form-control" id="email" name="email" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="password">Нууц үг</label>
                                        <input type="text" class="form-control" id="password" name="password" required>
                                    </div>

                                    <div class="form-group">
                                        <strong>Төрөл хуваарилах:</strong>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="type2" name="type_code" value="2" required>
                                            <label class="form-check-label" for="type2">Хог хаягдал</label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="type3" name="type_code" value="3" required>
                                            <label class="form-check-label" for="type3">Эвдрэл доройтол</label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="type4" name="type_code" value="4" required>
                                            <label class="form-check-label" for="type4">Бохир</label>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Хэрэглэгч нэмэх</button>
                                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Буцах</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(Session::has('message') && Session::get('message') == 'success')
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Амжилттай!',
                text: 'Админ үүсгэлт амжилттай боллоо',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('users.index') }}";
                }
            });
        </script>
    @elseif(Session::has('message') && Session::get('message') == 'error')
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Алдаа!',
                text: 'Шинэчлэх явцад алдаа гарлаа.',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
</html>
