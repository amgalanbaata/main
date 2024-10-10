@include('admin.head')
<html>
    <body class="sb-nav-fixed">
        @include('admin.header')
        <div id="layoutSidenav">
            @include('admin.menu')
            <div id="layoutSidenav_content">
                <div class="container-fluid px-4">
                    <div class="title d-flex flex-row justify-content-between">
                        <h1 class="mt-4">Хэрэглэгчид</h1>
                        <button class="btn btn-primary mt-4 mb-2">
                            <a href="{{ route('users.create') }}" class="btn btn-primary h-5">Хэрэглэгч нэмэх</a>
                        </button>
                    </div>
                    <div class="card mb-4">
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Нэвтрэх нэр</th>
                                        <th>Нууц үг</th>
                                        <th>Төрөл</th>
                                        <th>Шинэчилсэн Огноо</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->password }}</td>
                                        <td>
                                            @php
                                                $typeName = '';
                                                foreach ($types as $type) {
                                                    if ($type['type_code'] == $user->type_code) {
                                                        $typeName = $type['name'];
                                                        break;
                                                    }
                                                }
                                            @endphp
                                            {{ $typeName }}
                                        </td>
                                        <td>{{ $user->created_at }}</td>
                                        <td>
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">Засах</a>
                                            @if ($user->type_code != 0)
                                            <button type="button" class="btn btn-danger" onclick="openConfirmModal('{{ route('users.destroy', $user->id) }}')">Устгах</button>
                                            @endif
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

    <!-- Modal HTML -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Устгахдаа итгэлтэй байна уу ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Буцах</button>
                    <form id="deleteForm" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Устгах</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openConfirmModal(actionUrl) {
            document.getElementById('deleteForm').action = actionUrl;
            var myModal = new bootstrap.Modal(document.getElementById('confirmModal'));
            myModal.show();
        }
    </script>
</html>
