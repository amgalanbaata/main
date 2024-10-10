@include('admin.head')
<html>
    <style>
        .addButton {
            text-decoration: none;
        }
        .phone {
            background-color: blue;
        }
        #datatablesSimple tbody tr td:nth-child(2) {
            color: royalblue !important;
        }
    </style>
    <body class="sb-nav-fixed">
    @include('admin.header')
        <div id="layoutSidenav">
            @include('admin.menu')
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <div class="title d-flex flex-row justify-content-between">
                            <h1 class="mt-4">Санал хүсэлт</h1>
                            <button class="addPost btn btn-primary mt-4 mb-2"><a class="addButton text-white" href="/admin/addpost">Санал хүсэлт нэмэх</a></button>
                        </div>
                        <div class="card mb-4">
                            <div class="card-body">
                                <form action="" method="POST">
                                    @csrf
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="check1" value="true" name="check1"
                                            @if($condition['check1']) checked @endif
                                            onchange="javascript:this.form.submit()">
                                        <label class="form-check-label" for="check1">шинээр ирсэн
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="check2" value="true" name="check2"
                                            @if($condition['check2']) checked @endif
                                            onchange="javascript:this.form.submit()">
                                        <label class="form-check-label" for="check2">хүлээн авсан</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="check3" value="true" name="check3"
                                            @if($condition['check3']) checked @endif
                                            onchange="javascript:this.form.submit()">
                                        <label class="form-check-label" for="check3">шийдвэрлэсэн</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="check4" value="true" name="check4"
                                            @if($condition['check4']) checked @endif
                                            onchange="javascript:this.form.submit()">
                                        <label class="form-check-label" for="check4">татгалзсан</label>
                                    </div>
                                </form>
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>Нэр</th>
                                            <th>Утас</th>
                                            <th>Сэтгэгдэл</th>
                                            <th>Статус</th>
                                            <th>Төрөл</th>
                                            <th>Огноо</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Нэр</th>
                                            <th>Утас</th>
                                            <th>Сэтгэгдэл</th>
                                            <th>Статус</th>
                                            <th>Төрөл</th>
                                            <th>Огноо</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach($posts as $key => $data)
                                        <tr>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->number }}</td>
                                            <td>
                                                @if (strlen($data->comment) > 20)
                                                    <span class="comment-text" data-toggle="tooltip" title="{{ $data->comment }}">
                                                        {{ substr($data->comment, 0, 20) }}...
                                                    </span>
                                                @else
                                                {{ $data->comment }}
                                                @endif
                                            </td>
                                            <td>
                                                @switch($data->status)
                                                    @case(1)
                                                        <i class="fas fa-inbox text-primary"></i> {{ $data->status_name }}
                                                        @break
                                                    @case(2)
                                                        <i class="fas fa-envelope-open-text text-warning"></i> {{ $data->status_name }}
                                                        @break
                                                    @case(3)
                                                        <i class="fas fa-check-circle text-success"></i> {{ $data->status_name }}
                                                    @break
                                                    @case(4)
                                                        <i class="fas fa-times-circle text-danger"></i> {{ $data->status_name }}
                                                        @break
                                                    @default
                                                        {{ $data->status_name }}
                                                @endswitch
                                            </td>
                                            <td>{{ $data->type_name }}</td>
                                            <td>{{ $data->created_at }}</td>
                                            <td>
                                                <a type="button" class="btn btn-link" href="https://www.google.com/maps/search/?api=1&query={{ $data->latitude}}, {{$data->longitude}}" target="blank">
                                                    <button class="btn btn-info text-white">Байршил</button>
                                                </a>
                                            </td>
                                            <td>
                                                <a type="button" class="btn btn-link" href="/admin/post?id={{$data->id}}">
                                                    <button class="btn btn-primary">Дэлгэрэнгүй</button>
                                                </a>
                                            </td>
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
    <script>
        window.addEventListener('DOMContentLoaded', event => {
            const datatablesSimple = document.getElementById('datatablesSimple');
            if (datatablesSimple) {
                new simpleDatatables.DataTable(datatablesSimple);
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
</html>
