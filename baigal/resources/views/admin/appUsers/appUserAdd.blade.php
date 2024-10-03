@include('admin.head')
<html>
    <body class="sb-nav-fixed">
        @include('admin.header')
        <div id="layoutSidenav">
            @include('admin.menu')
            <div id="layoutSidenav_content">
                <style>
                    .form-group {
                        margin-top: 10px;
                    }
                </style>
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Хэрэглэгч нэмэх</h1>
                        <div class="card mb-4">
                            <div class="card-body">
                                <form method="POST" action="{{ route('users.store') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="username">иМэйл</label>
                                        <input type="text" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Нууц үг</label>
                                        <input type="text" class="form-control" id="password" name="password" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="Дүүрэг">Дүүрэг</label>
                                        <input type="text" class="form-control" id="distruct" name="distruct" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="Хороо">Хороо</label>
                                        <input type="text" class="form-control" id="committee" name="committee" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-4">Хэрэглэгч нэмэх</button>
                                    <a href="{{ route('users.index') }}" class="btn btn-secondary mt-4">Буцах</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
