@include('admin.head')
<html>
    <style>
        #map {
            height: 70vh;
            width: 100%;
            margin: auto;
            justify-items: center;
        }

        #mapSection {
            border: 1px solid rgb(180, 176, 176);
            padding: 10px;
            margin-bottom: 30px;
            border-radius: 10px;
            /* width: 95%; */
            width: 100%;
        }
        .element.style {
            width: 200px;
        }
        .gm-style-iw {
            width: 300px;
            height: 300px;
        }
        .active-button {
            background-color: #007bff;
            color: white;
        }
        /* .mapButton {
            background-color: #007bff;
            color: white;
        } */
        /* #listButton {
            background-color: #007bff;
            color: white;
        } */
        th {text-align: center !important;}
        td {text-align: center !important;}
    </style>
    <body class="sb-nav-fixed" onload="initMap()">
        @include('admin.header')
        <div id="layoutSidenav">
            @include('admin.menu')
            <div id="layoutSidenav_content">
                <div class="container mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <h1 class="mb-0">Байршил</h1>
                        @if(Session::get('admin_is') == 0)
                        <a href="/admin/location/add" class="btn btn-lg btn-primary">Цэг нэмэх</a>
                        @endif
                    </div>
                    <div class="container d-flex justify-content-evenly align-items-center mb-4">
                        <button class="btn btn-lg border border-dark rounded-sm active-button" id="mapButton" onclick="showMap()">Газрын зургаар харах</button>
                        <button class="btn btn-lg border border-dark rounded" id="listButton" onclick="showList()">Жагсаалтаар харах</button>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div id="mapSection"  onload="initMap()" style="display: block;">
                        <div id="map"></div>
                        <p style="font-size: 25px;"></p>
                    </div>
                    <div id="listSection" class="card" style="display: none;">
                        <div class="card-body">
                            <table class="table table-striped table-hover align-middle" id="datatablesSimple">
                                <thead class="table-Primary">
                                    <tr>
                                        <th>ID</th>
                                        <th>Гарчиг</th>
                                        <th>Тайлбар</th>
                                        <th>Өргөрөг</th>
                                        <th>Уртраг</th>
                                        <th>Бохирдлын түвшин</th>
                                        @if(Session::get('admin_is') == 0)
                                        <th>Үйлдлүүд</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($locations as $location)
                                        <tr>
                                            <td>{{ $location->id }}</td>
                                            <td>{{ $location->title }}</td>
                                            <td>{{ $location->comment }}</td>
                                            <td>{{ $location->latitude }}</td>
                                            <td>{{ $location->longitude }}</td>
                                            <td>
                                                {{-- <span class="badge" style="background-color: {{ $location->color }}">{{ ucfirst($location->color) }}</span> --}}
                                                {{ $location->color }}
                                            </td>
                                            @if(Session::get('admin_is') == 0)
                                            <td>
                                                <a href="/admin/location/edit/{{ $location->id }}" class="btn btn-ls btn-Info">Засах</a>
                                                <form action="{{ route('locations.destroy', $location) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-ls btn-danger" onclick="return confirm('Та энэ байршлыг устгахдаа итгэлтэй байна уу?')">Устгах</button>
                                                </form>
                                            </td>
                                            @endif
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
    <script>
        var map = document.getElementById('mapSection');
        var list = document.getElementById('listSection');

        var mapButton = document.getElementById('mapButton');
        var listbutton = document.getElementById('listButton');

        function showList() {
            map.style.display = 'none';
            list.style.display = 'block';

            listButton.classList.add('active-button');
            mapButton.classList.remove('active-button');
        }

        function showMap() {
            map.style.display = 'block';
            list.style.display = 'none';

            mapButton.classList.add('active-button');
            listButton.classList.remove('active-button');
        }
    </script>
    <script>
        function initMap() {

            var latitude = 47.9221;
            var longitude = 106.9155;

            var userLocation = { lat: latitude, lng: longitude };

            var defaultLocation = {lat: 47.9221, lng: 106.9155};

            var map = new google.maps.Map(document.getElementById("map"), {
                zoom: 14,
                center: userLocation
            });

            var locations = @json($data);
            locations.forEach(function(loc) {
                var markerLocation = {
                    lat: parseFloat(loc.latitude),
                    lng: parseFloat(loc.longitude)
                };
            });

            const pinElement = document.createElement("div");
            pinElement.className = "pin";

            @foreach ($data as $loc)
                    var markerLocation{{ $loc->id }} = { lat: {{ $loc->latitude }}, lng: {{ $loc->longitude }} };
                    var c = '{{$loc->color}}';
                    var color = '#FBBC05';
                    if(c == "green") color = '#34A853';
                    if(c == "red") color = '#EA4335';
                    var marker{{ $loc->id }} = new google.maps.Marker({
                        position: markerLocation{{ $loc->id }},
                        map: map,
                        title: '{{ $loc->title }}',
                        icon:{
                            path: 'm 12,2.4000002 c -2.7802903,0 -5.9650002,1.5099999 -5.9650002,5.8299998 0,1.74375 1.1549213,3.264465 2.3551945,4.025812 1.2002732,0.761348 2.4458987,0.763328 2.6273057,2.474813 L 12,24 12.9825,14.68 c 0.179732,-1.704939 1.425357,-1.665423 2.626049,-2.424188 C 16.809241,11.497047 17.965,9.94 17.965,8.23 17.965,3.9100001 14.78029,2.4000002 12,2.4000002 Z',
                            fillColor: color,
                            fillOpacity: 1.0,
                            strokeColor: '#000000',
                            strokeWeight: 1,
                            scale: 2,
                            anchor: new google.maps.Point(12, 24),
                        },
                    });

                    var infoWindow{{ $loc->id }} = new google.maps.InfoWindow({
                        content: '<div style="font-size: 30px;"><strong>{{ $loc->title }}</strong><br>{{ $loc->comment }}</div>'
                    });

                    marker{{ $loc->id }}.addListener('click', function() {
                        infoWindow{{ $loc->id }}.open(map, marker{{ $loc->id }});
                    });
            @endforeach
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
