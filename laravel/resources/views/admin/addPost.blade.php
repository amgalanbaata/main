@include('admin.head')
<html>
    <style>
        .adminComment textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            height: auto;
        }
        #map {
            height: 800px;
            width: 100%;
        }
        .card {
            width: 50%;
        }

        #fileInput {
            display: none;
        }

        /* Custom button styles */
        .custom-file-input {
            display: inline-block;
            margin-right: 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .file-names {
            font-size: 14px;
        }

        .title {
            margin-left: 25px;
        }

    </style>
    <body class="sb-nav-fixed" onload="initMap()">
        @include('admin.header')
        <div id="layoutSidenav">
            @include('admin.menu')
            <div id="layoutSidenav_content">
                <main>
                    <div class="title d-flex justify-content-between">
                        <h1 class="mt-4">Мэдэгдэл оруулах</h1>
                    </div>
                    <div class="container-fluid px-4 d-flex">
                        <div class="card mb-4">
                            <div class="card-body">
                                <!-- Form to add a new post -->
                            <div>
                                <label for="fileInput" class="custom-file-input">Зураг оруулах</label>
                                <span class="file-names" id="fileNames"></span>
                            </div>
                            <input type="file" id="fileInput" accept="image/*" multiple onchange="handleFileSelect(event)">
                                <form method="POST" action="" id="medegdel">
                                    @csrf
                                    <input type="hidden" id="image1" name="image1" value="">
                                    <input type="hidden" id="image2" name="image2" value="">
                                    <input type="hidden" id="image3" name="image3" value="">
                                    <input type="hidden" id="latitude" name="latitude" value="">
                                    <input type="hidden" id="longitude" name="longitude" value="">
                                    <!-- Add your form fields here -->
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Нэр</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="number" class="form-label">Утас эсвэл имэйл</label>
                                        <input type="text" class="form-control" id="number" name="number" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="comment" class="form-label">Тайлбар</label>
                                        <textarea class="form-control" id="comment" name="comment" required></textarea>
                                    </div>

                                    <div style="display: none;">
                                        <div class="mb-3">
                                            <label for="latitude_display" class="form-label">Latitude</label>
                                            <input type="text" class="form-control" id="latitude_display" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label for="longitude_display" class="form-label">Longitude</label>
                                            <input type="text" class="form-control" id="longitude_display" disabled>
                                        </div>
                                    </div>

                                    {{-- Type selection --}}
                                    <p><strong>Төрөл:</strong></p>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="type1" name="type" value="1" checked>
                                        <label class="form-check-label" for="type1">Бусад</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="type2" name="type" value="2">
                                        <label class="form-check-label" for="type2">Хог Хаягдал</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="type3" name="type" value="3">
                                        <label class="form-check-label" for="type3">Эвдрэл доройтол</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="type4" name="type" value="4">
                                        <label class="form-check-label" for="type4">Бохир</label>
                                    </div>

                                    <div class="adminComment mt-5">
                                        <p><strong>Хаяг</strong></p>
                                        <textarea name="address" value="address" class="form-control" required></textarea>
                                    </div>

                                    <br>
                                    <button type="button" class="btn btn-primary mt-5" onclick="confirmAddLocation()">Мэдэгдэл илгээх</button>
                                </form>
                            </div>
                        </div>
                        <div class="card mb-4">
                            {{-- <h6 class="mt-4 mr-4">Байршилийг газрын зураг дээр хатгаж оруул</h6> --}}
                            <div class="card-body">
                                <div id="map"></div>
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

    // Initialize and add the map
    function initMap() {
        // Map options
        var options = {
            zoom: 12,
            center: { lat: 47.918836, lng: 106.917622 }
        };

        // New map
        var map = new google.maps.Map(document.getElementById('map'), options);

        // Marker variable to hold the marker
        var marker;

        // Listen for map click event
        google.maps.event.addListener(map, 'click', function(event) {
            // Get latitude and longitude
            var latitude = event.latLng.lat();
            var longitude = event.latLng.lng();

            // Set latitude and longitude to the hidden inputs
            document.getElementById('latitude').value = latitude;
            document.getElementById('longitude').value = longitude;

            // Also set them to the display fields
            document.getElementById('latitude_display').value = latitude;
            document.getElementById('longitude_display').value = longitude;

            // Remove the previous marker
            if (marker) {
                marker.setMap(null);
            }

            // Add a new red marker
            marker = new google.maps.Marker({
                position: event.latLng,
                map: map,
                icon: {
                    url: "http://maps.google.com/mapfiles/ms/icons/red-dot.png"
                }
            });
        });
    }
    var k = 0;
    var fileNames = '';

    function handleFileSelect(event) {
        const files = event.target.files;
        const maxFiles = 3;
        const input = event.target;
        fileNames = fileNames + ', ' + Array.from(input.files).map(file => file.name).join(' ,');
        document.getElementById('fileNames').innerHTML = fileNames || 'Файл сонгосон байхгүй';

        if (files && files.length > 0) {
            if (k >= maxFiles) {
                alert('Хамгийн ихдээ 3 зураг оруулж болно.');
                // Clear file input
                document.getElementById('fileInput').value = '';
                return;
            }
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();

                reader.onload = function(event) {
                    const base64String = event.target.result;
                    k++;
                    document.getElementById('image' + k).value = base64String.replace(/^data:image\/(png|jpeg|jpg);base64,/, "");
                };
                reader.readAsDataURL(file);
            }
        }
    }

    function confirmAddLocation() {
        var form = document.getElementById('medegdel');

        if (k == 0) {
            alert("Та ядаж 1 зураг заавал оруулна уу.");
            return;
        }

        var title = form.querySelector('input[name="name"]');
        if (!title.value.trim()) {
            alert("Нэрээ заавал оруулна уу.");
            title.focus();
            return;
        }
        var number = form.querySelector('input[name="number"]');
        if (!number.value.trim()) {
            alert("Утас эсвэл имэйл ээ оруулна уу.");
            number.focus();
            return;
        }

        var comment = form.querySelector('textarea[name="comment"]');
        if (!comment.value.trim()) {
            alert("Тайлбар заавал оруулна уу.");
            comment.focus();
            return;
        }

        var latitude = form.querySelector('input[name="latitude"]');
        if (!latitude.value.trim()) {
            alert("Та газрын зураг дээр заавал цэгээ оруулна уу.");
            return;
        }

        form.submit();
    }
    </script>
</html>
