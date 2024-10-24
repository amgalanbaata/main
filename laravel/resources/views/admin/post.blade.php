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
        .locationTitle textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            height: auto;
        }
        .locationComment textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            height: auto;
        }

        /* Add styles for the map container */
        #map {
            height: 60vh;
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
        .buttons {
            display: flex;
            justify-content: space-between;
        }
        .addLocation {
            margin-top: 20px;
        }
        .addLocationButton {
            margin-bottom: 30px;
        }
        .swal2-confirm {
            background: #007bff !important;
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
                        <div class="alert alert-success">Амжилттай</div>
                        @elseif ($message == 'error')
                        <div class="alert alert-info">Алдаа Гарлаа</div>
                        @endif
                        @if(session('message') == 'success')
                            <div class="alert alert-success">Амжилттай</div>
                            <div class="text-danger">{{ $message }}</div>
                        @elseif (session('message') == 'error')
                            <div class="alert alert-info">Алдаа Гарлаа</div>
                            <div class="text-danger">{{ $message }}</div>
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
                            <h1>{{ $message }}</h1>
                            <input type="hidden" id="action_type" name="action_type" value="update">
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
                            <div class="d-flex justify-content-between">
                                <p><strong>Үүсгэсэн огноо:</strong> {{$post->created_at}}</p>
                                <p><strong>Шинэчлэгдсэн огноо:</strong> {{$post->updated_at}}</p>
                            </div>
                            @if ($post->status != 5 || $post->status != 6 && $post->agreed == null)
                            <button type="submit" class="custom-button" onclick="document.getElementById('action_type').value='update'">Шинэчлэх</button>
                            @endif
                            @if (Session::get('admin_is') == 0 && $post->type != 1)
                            <button type="submit" class="custom-button" onclick="document.getElementById('action_type').value='resolve'">Зөвшөөрөх</button>
                            @endif
                        </form>
                    </div>
                </div>
                {{-- map --}}
                <div class="card mb-4">
                    <div class="map-body">
                        <div id="map"></div>
                    </div>
                    <form id="locationForm" action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="action_type" name="action_type" value="locationAdd">

                        @if ($post->status == 5 || $post->status == 6 && Session::get('admin_is') != 0 && $post->agreed != null)

                            @if(isset($location))
                            <h1 class="p-8 text-center bg-yellow-50">Цэг нэмэгдсэн</h1>
                                <!-- Show location information if it exists -->
                                <div class="locationInfo">
                                    <p>Гарчиг: {{ $location->title }}</p>
                                    <p>Тайлбар: {{ $location->comment }}</p>
                                    <p>Бохирдлын түвшин:
                                        @if ($location->color == 'red')
                                            Их
                                        @elseif ($location->color == 'yellow')
                                            Дунд
                                        @elseif ($location->color == 'green')
                                            Бага
                                        @else
                                            Тодорхойгүй
                                        @endif
                                    </p>
                                    <p>Өргөрөг: {{ $location->latitude }}</p>
                                    <p>Уртраг: {{ $location->longitude }}</p>
                                    <a href="{{ asset($location->pdf_path) }}" target="_blank">View PDF</a>
                                    {{-- <a href="{{ asset('uploads/dummy-pdf_2.pdf') }}" target="_blank">View PDF</a> --}}
                                </div>
                            @else
                            <h1 class="p-8 text-center bg-yellow-50">Цэг нэмэх</h1>
                                <!-- Display the form to add a new location -->
                                <div class="addLocation">
                                    <div class="locationTitle">
                                        <strong>Гарчиг</strong>
                                        <textarea name="title" required>{{ old('title') }}</textarea>
                                    </div>
                                    <div class="locationComment">
                                        <strong>Тайлбар</strong>
                                        <textarea name="comment" required>{{ old('comment') }}</textarea>
                                    </div>
                                    <input type="hidden" name="latitude" value="{{ $post->latitude }}">
                                    <input type="hidden" name="longitude" value="{{ $post->longitude }}">

                                    <div class="form-group mb-4">
                                        <label class="form-label">Бохирдлын түвшин</label>
                                        <div class="">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="color" id="red" value="red" {{ old('color') == 'red' ? 'checked' : '' }} required>
                                                <label class="form-check-label" for="red">Их</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="color" id="yellow" value="yellow" {{ old('color') == 'yellow' ? 'checked' : '' }} required>
                                                <label class="form-check-label" for="yellow">Дунд</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="color" id="green" value="green" {{ old('color') == 'green' ? 'checked' : '' }} required>
                                                <label class="form-check-label" for="green">Бага</label>
                                            </div>
                                        </div>
                                        @error('color')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    {{-- <h1>{{ $post->status }}</h1> --}}

                                    <div>
                                        <label for="pdfUpload" class="form-label">Шинжилгээ хавсаргах</label>
                                        @if ($post->status == 5)
                                        <input class="form-control" type="file" name="pdfUpload" id="pdfUpload" accept=".pdf" required>
                                        @else
                                        <input class="form-control" type="file" name="pdfUpload" id="pdfUpload" accept=".pdf">
                                        @endif
                                        @error('pdfUpload')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <button type="button" class="custom-button addLocationButton" onclick="confirmAddLocation()">Цэг нэмэх</button>
                                {{-- <button type="submit" class="custom-button addLocationButton" onclick="document.getElementById('action_type').value='locationAdd'">Цэг нэмэх</button> --}}
                            @endif
                        @endif
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmAddLocation() {
        // Get the form element
        var form = document.getElementById('locationForm');

        // Check if the title is filled
        var title = form.querySelector('textarea[name="title"]');
        if (!title.value.trim()) {
            alert("Гарчиг заавал оруулна уу."); // "Title is required"
            title.focus();
            return;
        }

        // Check if the comment is filled (optional, since it's nullable)
        var comment = form.querySelector('textarea[name="comment"]');
        if (!comment.value.trim()) {
            alert("Тайлбар заавал оруулна уу."); // "Comment is required"
            comment.focus();
            return;
        }

        // Check if color is selected
        var colorSelected = form.querySelector('input[name="color"]:checked');
        if (!colorSelected) {
            alert("Бохирдлын түвшинг сонгоно уу."); // "Please select pollution level"
            return;
        }

        // Check if PDF is uploaded based on post status
        var pdfUpload = form.querySelector('input[name="pdfUpload"]');
        var postStatus = {{ $post->status }}; // Assuming you are passing the status as a variable

        if (postStatus == 5 && pdfUpload.required && !pdfUpload.files.length) {
            alert("Шинжилгээ хавсаргах заавал оруулна уу.");
            return;
        }

        Swal.fire({
        title: "Итгэлтэй байна уу?",
        text: "Та цэг нэмэх гэж байна.",
        // icon: "warning",
        showCancelButton: true,
        confirmButtonText: 'Тийм',
        cancelButtonText: 'Үгүй',
        dangerMode: true,
        }).then((result) => {
            if (result.isConfirmed) {
                console.log("Form is being submitted!");
                form.submit();
            } else {
                console.log('Form submission canceled.');
            }
        });
    }
</script>
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
