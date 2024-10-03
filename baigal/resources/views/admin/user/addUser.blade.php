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
                                        <label for="username">Нэвтрэх нэр</label>
                                        <input type="text" class="form-control" id="username" name="username" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="password">Нууц үг</label>
                                        <input type="text" class="form-control" id="password" name="password" required>
                                    </div>

                                    <div class="form-group">
                                        <strong>Төрөл хуваарилах:</strong>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="type2" name="type_code" value="2" required>
                                            <label class="form-check-label" for="type2">Хог хягдал</label>
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
</html>
