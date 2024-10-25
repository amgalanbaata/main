@include('admin.head')
<html>
    <style>
        .card-image {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .card-image .image {
            width: 25%;
            height: 25%;
            margin: 5px;
            transition: transform 0.4s;
            border-radius: 8px;
            object-fit: cover;
            display: flex;
        }

        .card-image .image:hover {
            transform: scale(1.2);
        }

        .card-title {
            font-size: 1.2em;
            margin-top: 10px;
        }
        #map {
            height: 500px;
            width: 100%;
        }

        .type-counts-card {
            border-radius: 10px;
            overflow: hidden;
        }

        .card-body {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .type-column {
            flex: 1;
            margin: 0 10px;
        }

        .type-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .type-section h5 {
            margin: 0;
            font-size: 1.2rem;
        }

        .type-section h3 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .type-section:hover {
            transform: translateY(-3px);
            box-shadow: inset 0 0 15px rgba(0, 0, 0, 0.3);
        }

        .bg-dark h5, .bg-dark h3 {
            color: #f8f9fa;
        }

        .bg-warning h5, .bg-warning h3 {
            color: #212529;
        }

        .bg-danger h5, .bg-danger h3 {
            color: #f8f9fa;
        }

        .bg-info h5, .bg-info h3 {
            color: #212529;
        }
        .row {
            justify-content: space-between;
        }
        .otherButton {
            cursor: pointer;
            width: 100%;
        }
    </style>
    <body class="sb-nav-fixed" onload="initMap()">
    @include('admin.header')
        <div id="layoutSidenav">
            @include('admin.menu')
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Нүүр хуудас</h1>
                        <form class="row" action="{{route('admin.postsPost')}}" method="POST">
                            @csrf
                            <input type="hidden" name="status" id="status">
                            @if (Session::get('admin_is') == 0)
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <h4 class="card-body">Зөвшөөрөх хүсэлт: {{ $counts['Conduct_soil_analysis'] + $counts['Register_directly_on_location'] - $agreedCounts }}</h4>
                                    <button onclick="adminStatusFunction(10)" class="btn">
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        Дэлгэрэнгүй
                                        <div class="small text-white"><i class="icon-angle-right"></i></div>
                                    </div>
                                    </button>
                                </div>
                            </div>
                            @endif
                            @if (Session::get('admin_is') == 2)
                            <div class="col-xl-3 col-md-6">
                                <p class="">Хог хаягдал хариуцсан админ</p>
                                <div class="card bg-primary text-white mb-4">
                                    {{-- <h4 class="card-body">Шинээр ирсэн: {{ $typeCounts['Хог хягдал'] }}</h4> --}}
                                    <h4 class="card-body">Шинээр ирсэн: {{ $newCounts }}</h4>
                                    {{-- <h4 class="card-body">Шинээр ирсэн: {{ $counts['new'] }}</h4> --}}
                                    <button onclick="statusFunction(1)" class="btn">
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        Дэлгэрэнгүй
                                        <div class="small text-white"><i class="icon-angle-right"></i></div>
                                    </div>
                                    </button>
                                </div>
                            </div>
                            @endif
                            @if (Session::get('admin_is') == 3)
                            <div class="col-xl-3 col-md-6">
                                <p>Эвдрэл доройтлын хариуцсан админ</p>
                                <div class="card bg-primary text-white mb-4">
                                    {{-- <h4 class="card-body">Шинээр ирсэн: {{ $typeCounts['Эвдрэл доройтол'] }}</h4> --}}
                                    <h4 class="card-body">Шинээр ирсэн: {{ $newCounts }}</h4>
                                    <button onclick="statusFunction(1)" class="btn">
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        Дэлгэрэнгүй
                                        <div class="small text-white"><i class="icon-angle-right"></i></div>
                                    </div>
                                    </button>
                                </div>
                            </div>
                            @endif
                            @if (Session::get('admin_is') == 4)
                            <div class="col-xl-3 col-md-6">
                                <p>Бохир хариуцсан админ</p>
                                <div class="card bg-primary text-white mb-4">
                                    {{-- <h4 class="card-body">Шинээр ирсэн: {{ $typeCounts['Бохир'] }}</h4> --}}
                                    <h4 class="card-body">Шинээр ирсэн: {{ $newCounts }}</h4>
                                    <button onclick="statusFunction(1)" class="btn">
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        Дэлгэрэнгүй
                                        <div class="small text-white"><i class="icon-angle-right"></i></div>
                                    </div>
                                    </button>
                                </div>
                            </div>
                            @endif
                            {{-- <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div>
                                        <h4 class="card-body">Хүлээн авсан {{ $counts['received'] }}</h4>
                                    </div>
                                    <button onclick="statusFunction(2)" class="btn">
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        Дэлгэрэнгүй
                                        <div class="small text-white"><i class="icon-angle-right"></i></div>
                                    </div>
                                    </button>
                                </div>
                            </div> --}}
                            <div class="col-xl-6 col-md-5">
                                <div class="card bg-secondary text-white mb-4 shadow type-counts-card">
                                    <div class="card-body d-flex">
                                        <!-- Left Column -->
                                        <div class="type-column">
                                            <div class="type-section bg-warning text-dark">
                                                <h5>Хог хягдал</h5>
                                                <h3>{{ $typeCounts['Хог хягдал'] }}</h3>
                                            </div>
                                            <div class="type-section bg-info text-dark">
                                                <h5>Бохир</h5>
                                                <h3>{{ $typeCounts['Бохир'] }}</h3>
                                            </div>
                                        </div>
                                        <!-- Right Column -->
                                        <div class="type-column">
                                            <div class="type-section bg-danger">
                                                <h5>Эвдрэл доройтол</h5>
                                                <h3>{{ $typeCounts['эвдрэл доройтол'] }}</h3>
                                            </div>
                                            @if (Session::get('admin_is') == 0)
                                            <button onclick="fetchPosts(7)" class="type-section bg-dark otherButton">
                                                <h5>Бусад</h5>
                                                <div>
                                                    <h3>{{ $typeCounts['Бусад'] }}</h3>
                                                </div>
                                                <input type="hidden" id="check7" name="check7" value="0">
                                            </button>
                                            @else
                                            <div class="type-section bg-dark">
                                                <h5>Бусад</h5>
                                                <div>
                                                    <h3>{{ $typeCounts['Бусад'] }}</h3>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                            {{-- <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <h4 class="card-body">Шийдвэрлэсэн {{ $counts['resolved'] }}</h4>
                                    <button onclick="statusFunction(3)" class="btn">
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        Дэлгэрэнгүй
                                        <div class="small text-white"><i class="icon-angle-right"></i></div>
                                    </div>
                                    </button>
                                </div>
                            </div> --}}
                            {{-- <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <h4 class="card-body">Татгалзсан {{ $counts['rejected'] }}</h4>
                                    <button onclick="statusFunction(4)" class="btn">
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        Дэлгэрэнгүй
                                        <div class="small text-white"><i class="icon-angle-right"></i></div>
                                    </div>
                                    </button>
                                </div>
                            </div> --}}
                        <div class="card mb-4">
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
    function initMap() {
    // Map options
    var options = {
        zoom: 12,
        center: { lat: 47.918836, lng: 106.917622 }
    };

    // New map
    var map = new google.maps.Map(document.getElementById('map'), options);

    // Array of markers
    var data = <?php echo json_encode($data); ?>;
    var image_path = <?php echo json_encode($image_path); ?>;
    var markers = [];
    for (var i = 0; i < data.length; i++) {
        var image_name = [];
        for (var j = 0; j < image_path.length; j++) {
            if(data[i].id == image_path[j].post_id) {
                image_name.push(image_path[j]);
            }
        }
        var item = {
            coords: { lat: parseFloat(data[i].latitude), lng: parseFloat(data[i].longitude) },
            content: content(data[i], image_name)
        };
        markers.push(item);
    }
    console.log(markers, ' markers');

    // Loop through markers
    for (var i = 0; i < markers.length; i++) {
        addMarker(markers[i]);
    }

    // Add Marker Function
    function addMarker(props) {
        var marker = new google.maps.Marker({
        position: props.coords,
        map: map
        });

        // Check for custom content
        if (props.content) {
        var infoWindow = new google.maps.InfoWindow({
            content: props.content
        });

        marker.addListener('click', function () {
            infoWindow.open(map, marker);

        console.log(date.image_name, ' datagiin image')
        });
        }
    }
    function content(data, images) {
        // Construct HTML content for marker
        var imageUrl = "";
        var contentString = '<div class="content card-image">';
        for (var i = 0; i < images.length; i++) {
            imageUrl = "{{ asset('images/posts/') }}" + '/' + images[i].image_name;
            contentString += '<img class="image" src="' + imageUrl + '" alt="Post Image">';
        }
        contentString += '<div class="image-body">' +
            '<h5 class="card-title">' + data.comment + '</h5>' +
            '</div>' +
            '</div>';
        return contentString;
    }
    }

    function fetchPosts(check) {
        document.getElementById('check7').value = check;
        // if (check) {
        //     // Submit the form
        //     // document.getElementById('check7').submit();
        //     this.form.submit();
        // }
    }
    function statusFunction(check) {
        document.getElementById('status').value = check;
        conosle.log(check);
        if (check) {
            this.form.submit();
        }
    }
    function adminStatusFunction(check10) {
        document.getElementById('status').value = check10;
        if (check) {
            this.form.submit();
        }
    }
</script>
<style>
    #map {
    height: 500px;
    width: 100%;
    }
</style>
</html>
