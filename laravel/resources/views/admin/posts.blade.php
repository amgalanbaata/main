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
        th {text-align: center !important;}
        td {text-align: center !important;}
        .datatable-active a {
            background: #0d6efd;
            color: #FFFFFF;
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
                            <h1 class="mt-4">Мэдэгдлүүд</h1>
                            <button class="addPost btn btn-primary mt-4 mb-2"><a class="addButton text-white" href="/admin/addpost">Мэдэгдэл нэмэх</a></button>
                        </div>
                        <div class="card mb-4">
                            <div class="card-body">
                                <form action="" method="POST">
                                    @csrf
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="check1" value="true" name="check1"
                                            @if($condition['check1']) checked @endif
                                            onchange="javascript:this.form.submit()">
                                        <label class="form-check-label" for="check1">Шинээр ирсэн
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="check2" value="true" name="check2"
                                            @if($condition['check2']) checked @endif
                                            onchange="javascript:this.form.submit()">
                                        <label class="form-check-label" for="check2">Давхардсан</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="check3" value="true" name="check3"
                                            @if($condition['check3']) checked @endif
                                            onchange="javascript:this.form.submit()">
                                        <label class="form-check-label" for="check3">Нэмэлт мэдээлэл шаардлагатай</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="check4" value="true" name="check4"
                                            @if($condition['check4']) checked @endif
                                            onchange="javascript:this.form.submit()">
                                        <label class="form-check-label" for="check4">Татгалзсан</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="check5" value="true" name="check5"
                                            @if($condition['check5']) checked @endif
                                            onchange="javascript:this.form.submit()">
                                        <label class="form-check-label" for="check5">Хөрсний шинжилгээ хийх</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="check6" value="true" name="check6"
                                            @if($condition['check6']) checked @endif
                                            onchange="javascript:this.form.submit()">
                                        <label class="form-check-label" for="check6">Байршилд шууд бүртгэх</label>
                                    </div>
                                </form>
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>Нэр</th>
                                            <th>Утас/Имэйл</th>
                                            <th>Сэтгэгдэл</th>
                                            <th>Статус</th>
                                            <th>Төрөл</th>
                                            <th>Зөвшөөрсөн</th>
                                            <th>Огноо</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Нэр</th>
                                            <th>Утас/Имэйл</th>
                                            <th>Сэтгэгдэл</th>
                                            <th>Статус</th>
                                            <th>Төрөл</th>
                                            <th>Зөвшөөрсөн</th>
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
                                                    @case(5)
                                                        <i class="fas fa-leaf text-warning"></i> {{ $data->status_name }}
                                                        @break
                                                    @case(6)
                                                        <i class="fas fa-map-marker text-success"></i> {{ $data->status_name }}
                                                        @break
                                                    @default
                                                        {{ $data->status_name }}
                                                @endswitch
                                            </td>
                                            <td>{{ $data->type_name }}</td>
                                            <td>{{ $data->agreed }}</td>
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
            var table = document.getElementsByTagName("table")[0];
            var rows = table.rows;
            var posts = [];
            var i = 0;
            @foreach($posts as $key => $data)
                @if ($data->color != '')
                    if(rows[i+1]) {
                        rows[i+1].style.background = "#7dcf7d";
                        rows[i+1].style.color = "#ffffff";
                    }
                @endif
                posts.push('{{$data->color}}');
                i++;
            @endforeach

            $(".datatable-pagination-list-item-link").on('click', function(event){
                var table = document.getElementsByTagName("table")[0];
                var rows2 = table.rows;
                var i = 0;
                for(var j = 0; j < posts.length; j++) {
                    if(rows2[i+1]) {
                        rows2[i+1].style.background = "#ffffff";
                        rows2[i+1].style.color = "unset";
                    }
                    i++;
                }
                setTimeout(() => {
                    var ii = 0;
                    if(document.querySelector(".datatable-active a").innerHTML + 1 == event.target.dataset.page || 
                    document.querySelector(".datatable-active a").innerHTML - 1 == event.target.dataset.page) {
                        for(var j = (document.querySelector(".datatable-active a").innerHTML - 1)*document.querySelector(".datatable-selector").value; j < posts.length; j++) {
                            if (posts[j] != '') {
                                if(rows2[ii+1]) {
                                    rows2[ii+1].style.background = "#7dcf7d";
                                    rows2[ii+1].style.color = "#ffffff";
                                }
                            } else {
                                if(rows2[ii+1]) {
                                    rows2[ii+1].style.background = "#ffffff";
                                    rows2[ii+1].style.color = "unset";
                                }
                            }
                            ii++;
                        }
                    } else if(document.querySelector(".datatable-active a").innerHTML < event.target.dataset.page) {
                        for(var j = (document.querySelector(".datatable-active a").innerHTML - 1)*document.querySelector(".datatable-selector").value; j < posts.length; j++) {
                            if (posts[j] != '') {
                                if(rows2[ii+1]) {
                                    rows2[ii+1].style.background = "#7dcf7d";
                                    rows2[ii+1].style.color = "#ffffff";
                                }
                            } else {
                                if(rows2[ii+1]) {
                                    rows2[ii+1].style.background = "#ffffff";
                                    rows2[ii+1].style.color = "unset";
                                }
                            }
                            ii++;
                        }
                    } else {
                        for(var j = (document.querySelector(".datatable-active a").innerHTML - 1)*document.querySelector(".datatable-selector").value; j < posts.length; j++) {
                            if (posts[j] != '') {
                                if(rows2[ii+1]) {
                                    rows2[ii+1].style.background = "#7dcf7d";
                                    rows2[ii+1].style.color = "#ffffff";
                                }
                            } else {
                                if(rows2[ii+1]) {
                                    rows2[ii+1].style.background = "#ffffff";
                                    rows2[ii+1].style.color = "unset";
                                }
                            }
                            ii++;
                        }
                    }
                }, 500);
            });            
        });
    </script>
</html>
