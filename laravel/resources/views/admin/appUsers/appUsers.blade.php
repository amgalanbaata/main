@include('admin.head')
<html>
    <style>
        th {text-align: center !important;}
        td {text-align: center !important;}
    </style>
    <body class="sb-nav-fixed">
        @include('admin.header')
        <div id="layoutSidenav">
            @include('admin.menu')
            <div id="layoutSidenav_content">
                <main>
                <div class="container-fluid px-4">
                    <div class="title d-flex flex-row justify-content-between">
                        <h1 class="mt-4">АПП Хэрэглэгчид</h1>
                        <button class="btn btn-primary mt-4 mb-2">
                            <a href="{{ route('app-users.create') }}" class="btn btn-primary h-5">Хэрэглэгч нэмэх</a>
                        </button>
                    </div>
                    {{-- <h1>{{ $email }}</h1> --}}
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Имэйл</th>
                                        <th>Нууц үг</th>
                                        <th>Дүүрэг</th>
                                        <th>Хороо</th>
                                        <th>Үүсгэсэн огноо</th>
                                        <th>Шинэчилсэн огноо</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Имэйл</th>
                                        <th>Нууц үг</th>
                                        <th>Дүүрэг</th>
                                        <th>Хороо</th>
                                        <th>Үүсгэсэн огноо</th>
                                        <th>Шинэчилсэн огноо</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->password }}</td>
                                        <td>{{ $user->district }}</td>
                                        <td>{{ $user->committee }}</td>
                                        <td>{{ $user->created_at }}</td>
                                        <td>{{ $user->updated_at }}</td>
                                        <td>
                                            <a href="{{ route('app-users.edit', $user->id) }}" class="btn btn-primary">Засах</a>
                                            <button type="button" class="btn btn-danger" onclick="openConfirmModal('{{ route('app-users.destroy', $user->id) }}')">Устгах</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
            </div>
        </div>
    </body>
    <!-- Modal HTML -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Хэрэглэгч устгах</h5>
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
    {{-- paginate --}}
    <script>
        window.addEventListener('DOMContentLoaded', event => {
            const datatablesSimple = document.getElementById('datatablesSimple');
            if (datatablesSimple) {
                new simpleDatatables.DataTable(datatablesSimple, {
                    labels: {
                        placeholder: "Хайх...",
                        perPage: "Хуудас",
                        noRows: "Өгөгдөл алга.",
                        info: "Нийт {rows}-с {start}-ээс {end}-ийг харуулж байна.",
                        noResults: "Таны хайлтын асуулгад тохирох илэрц олдсонгүй.",
                    }
                });
            }
        });
    </script>
</html>
