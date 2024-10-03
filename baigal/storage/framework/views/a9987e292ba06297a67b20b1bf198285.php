<html>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC-mJPcidccQNY9l1-QBXv9VKREeK5f5oM"></script>
<head>
    <style>
        #map {
            height: 100%;
            width: 100%;
        }
        .element.style {
            width: 200px;
        }
        .gm-style-iw {
            width: 300px;
            height: 300px;
        }
    </style>
</head>
<body  onload="initMap()">
    <div id="map"></div>
    <p style="font-size: 25px;">sdfkj</p>
</body>
<script>
    function initMap() {

        var latitude = <?php echo e($location['latitude'] ?? 47.9221); ?>;
        var longitude = <?php echo e($location['longitude'] ?? 106.9155); ?>;

        var userLocation = { lat: latitude, lng: longitude };

        var defaultLocation = {lat: 47.9221, lng: 106.9155};

        var map = new google.maps.Map(document.getElementById("map"), {
            zoom: 17,
            center: userLocation
        });

        var marker = new google.maps.Marker({
            position: userLocation,
            map: map,
            icon: {
                url: "<?php echo e(asset('images/marker.current.png')); ?>",
                scaledSize: new google.maps.Size(30, 30)
            }
        });
        console.log(userLocation);

        var locations = <?php echo json_encode($data, 15, 512) ?>;
        locations.forEach(function(loc) {
            var markerLocation = {
                lat: parseFloat(loc.latitude),
                lng: parseFloat(loc.longitude)
            };
        });

        const pinElement = document.createElement("div");
        pinElement.className = "pin";

        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                var markerLocation<?php echo e($loc->id); ?> = { lat: <?php echo e($loc->latitude); ?>, lng: <?php echo e($loc->longitude); ?> };
                var c = '<?php echo e($loc->color); ?>';
                var color = '#FBBC05';
                if(c == "green") color = '#34A853';
                if(c == "red") color = '#EA4335';
                var marker<?php echo e($loc->id); ?> = new google.maps.Marker({
                    position: markerLocation<?php echo e($loc->id); ?>,
                    map: map,
                    title: '<?php echo e($loc->title); ?>',
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

                var infoWindow<?php echo e($loc->id); ?> = new google.maps.InfoWindow({
                    content: '<div style="font-size: 30px;"><strong><?php echo e($loc->title); ?></strong><br><?php echo e($loc->comment); ?></div>'
                });

                marker<?php echo e($loc->id); ?>.addListener('click', function() {
                    infoWindow<?php echo e($loc->id); ?>.open(map, marker<?php echo e($loc->id); ?>);
                });
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
</html>
<?php /**PATH C:\Users\Amka\Documents\baigal\resources\views/admin/staticUrls/map.blade.php ENDPATH**/ ?>