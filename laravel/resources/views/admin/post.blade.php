@include('admin.head')
<html>
<head>
    <style>
        /* Custom styles for better layout */
        .card-body {
            /* line-height: 1.8; */
        }

        .custom-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .custom-button {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            border-radius: 10px;
            color: white;
            cursor: pointer;
        }

        .custom-button:hover {
            background-color: #0056b3;
        }

        .adminComment textarea{
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            height: auto;
        }

        /* Add styles for the map container */
        #map {
            height: 100vh;
            width: auto;
        }

        .card {
            width: 50%;
            padding: 10px;
            height: auto;
        }
        .title {
            margin-left: 25px;
        }
        .dflex {
            display: flex;
            margin-top: 20px;
            justify-content: start;
            align-items: center !important;
         }
        ul li{
            list-style-type: none;
        }
        ul li img{
            width: 110px;
            height: 120px;
            border: 1px solid gray;
        }
        .imageUl {
            padding: 0;
        }
        .big-image{
            width: 350px; height: 400px;
            border: 1px solid gray;
            margin-left: 20px;
            margin-bottom: 16px;
        }
        .typeS {
            margin-right: 20px;
        }
        .statusS {
            margin-right: 20px;
        }

    </style>
</head>
<body class="sb-nav-fixed" onload="initMap()">
@include('admin.header')
<div id="layoutSidenav">
    @include('admin.menu')
    <div id="layoutSidenav_content">
        <main>
            <h1 class="title">Санал хүсэлт</h1>
            <div class="container-fluid px-4 d-flex">
                <div class="card">
                    <div class="card-body">
                        @if($message == 'success')
                        <div class="alert alert-success">success</div>
                        @elseif ($message == 'error')
                        <div class="alert alert-info">error</div>
                        @endif
                        <h5 class="card-title">Санал хүсэлтийн дэлгэрэнгүй мэдээлэл</h5>
                        <div class="image-container dflex">
                            <div>
                                @foreach ($image_path as $image)
                                <ul class="imageUl">
                                    <li>
                                        @if ($image->image_name)
                                            <img class="small-image" src="{{ asset('images/posts/'.$image->image_name) }}" alt="Post Image">
                                            @else
                                            <h1>Зураг хоосон</h1>
                                        @endif
                                    </li>
                                </ul>
                                @endforeach
                            </div>
                            <div>
                                <img class="big-image" src="{{ asset('images/posts/'.$image_path->first()->image_name) }}" alt="Post Image">
                            </div>
                        </div>
                        <div>
                            {{-- <p><strong>ID:</strong> {{$post->id}}</p> --}}
                            <div class="d-flex justify-content-between">
                                <p><strong>Нэр:</strong> {{$post->name}}</p>
                                <p><strong>Утасны дугаар:</strong> {{$post->number}}</p>
                            </div>
                            <hr>
                            <p><strong>Тайлбар:</strong> {{$post->comment}}</p>
                            <p><strong>Байршил:</strong>
                                <a type="button" class="btn btn-link" href="https://www.google.com/maps/search/?api=1&query={{ $post->latitude}}, {{$post->longitude}}" target="blank">
                                    <button class="btn btn-info text-white">Байршил</button>
                                </a>
                            </p>
                        </div>

                        <form action="" method="POST">
                            @csrf
                            <div class="status">
                                <strong class="statusS">Статус:</strong>
                                <input type="text" name="id" value="{{ $post->id }}" style="display: none">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="status1" name="status" value="1" {{ $post->status == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status1">Шинээр ирсэн</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="status2" name="status" value="2" {{ $post->status == 2 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status2">Давхардсан</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="status3" name="status" value="3" {{ $post->status == 3 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status3">Нэмэлт мэдээлэл шаардлагатай</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="status4" name="status" value="4" {{ $post->status == 4 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status4">Татгалзсан</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="status5" name="status" value="5" {{ $post->status == 5 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status5">Хөрсний шинжилгээ хийх</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="status6" name="status" value="6" {{ $post->status == 6 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status6">Байршилд шууд бүртгэх</label>
                                </div>
                                <br>
                            </div>
                            <div class="type mt-2 mb-2">
                                <strong class="typeS mt-10 mr-10">Төрөл:</strong>
                                <div class="form-check form-check-inline ml-10">
                                    <input class="form-check-input" type="radio" id="type1" name="type" value="1" {{ $post->type == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type1">Бусад</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="type2" name="type" value="2" {{ $post->type == 2 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type2">Хог Хаягдал</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="type3" name="type" value="3" {{ $post->type == 3 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type3">эвдрэл доройтол</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="type4" name="type" value="4" {{ $post->type == 4 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type4">Бохир</label>
                                </div>
                            </div>
                            <div class="adminComment">
                                <strong>Сэтгэгдэл</strong>
                                <textarea type="text" name="admin_comment" value="text">{{ $post->admin_comment }}</textarea>
                            </div>
                        </p>
                        <div class="d-flex justify-content-between">
                            <p><strong>Үүсгэсэн огноо:</strong> {{$post->created_at}}</p>
                            <p><strong>Шинэчлэгдсэн огноо:</strong> {{$post->updated_at}}</p>
                        </div>
                        <button type="submit" class="custom-button">Шинэчлэх</button>
                    </form>
                    </div>
                </div>

                {{-- map --}}
                <div class="card mb-4">
                    <div class="map-body">
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
<script type="text/javascript">
    $(document).ready(function() {
        $(".small-image").on('click', function() {
            console.log('clicked image');
            var newSrc = $(this).attr('src');
            $(".big-image").attr('src', newSrc);
            $(".big-image").imagezoomsl({
                zoomrange: [3, 3]
            });
        });
        $(".big-image").imagezoomsl({
            zoomrange: [3, 3]
        });
    });
</script>
<script>
    $(document).ready(function() {
        $(".small-image").on('click', function() {
            $(".big-image").attr('src', $(this).attr('src'));
        });
    });

    window.addEventListener('DOMContentLoaded', event => {
        const datatablesSimple = document.getElementById('datatablesSimple');
        if (datatablesSimple) {
            new simpleDatatables.DataTable(datatablesSimple);
        }
    });

    // Initialize and add the map
    function initMap() {
        // The location
        var location = {lat: parseFloat("{{ $post->latitude }}"), lng: parseFloat("{{ $post->longitude }}")};
        // The map, centered at location
        var map = new google.maps.Map(document.getElementById("map"), {
            zoom: 15,
            center: location
        });
        // The marker, positioned at location
        var marker = new google.maps.Marker({
            position: location,
            map: map
        });
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
</html>
