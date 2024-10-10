@include('admin.head')
<html>
    <body class="sb-nav-fixed">
        @include('admin.header')
        <div id="layoutSidenav">
            @include('admin.menu')
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Edit User</h1>
                        <div class="card mb-4">
                            <div class="card-body">
                                <form method="POST" action="{{ route('users.update', $user->id) }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="username">Нэвтрэх нэр</label>
                                        <input type="text" class="form-control" id="username" name="username" value="{{ $user->username }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="password">Нууц үг</label>
                                        <input type="password" class="form-control" id="password" name="password" value="{{ $user->password }}" required>
                                    </div>
                                    @if ($user->type_code != 0)

                                    <div class="form-group">
                                        <strong class="typeS">Төрөл хуваарилах:</strong>

                                        <div class="form-grout form-group-inline">
                                            <input class="form-check-input" type="radio" id="type2" name="type_code" value="2" {{ $user->type_code == 2 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="type2">Хог хягдал</label>
                                        </div>

                                        <div class="form-grout form-group-inline">
                                            <input class="form-check-input" type="radio" id="type3" name="type_code" value="3" {{ $user->type_code == 3 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="type3">Эвдрэл доройтол</label>
                                        </div>

                                        <div class="form-grout form-group-inline">
                                            <input class="form-check-input" type="radio" id="type4" name="type_code" value="4" {{ $user->type_code == 4 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="type4">Бохир</label>
                                        </div>
                                        <br>
                                    </div>
                                    @endif

                                    <button type="submit" class="btn btn-primary">Шинэчлэх</button>
                                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
