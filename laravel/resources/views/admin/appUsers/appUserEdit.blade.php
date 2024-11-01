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
                        <h1 class="mt-4">АПП хэрэглэгч засах</h1>
                        <div class="card mb-4">
                            <div class="card-body">
                                <form method="POST" action="{{ route('app-users.update', $user->id) }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="email">Имэйл</label>
                                        <input type="text" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Нууц үг</label>
                                        <input type="password" class="form-control" id="password" name="password" value="{{ $user->password }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="district">Дүүрэг</label>
                                        <input type="district" class="form-control" id="district" name="district" value="{{ $user->district }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="committee">Хороо</label>
                                        <input type="committee" class="form-control" id="committee" name="committee" value="{{ $user->committee }}" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-4">Шинэчлэх</button>
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
