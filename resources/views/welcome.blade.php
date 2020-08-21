<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    {{-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script> --}}
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>

    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAxP3bsaJ8-4gS04PAJ42N_7ufFqIpaL80&callback=initMap&libraries=&v=weekly"
        defer>
    </script>
    <!-- Styles -->
    <style>
        html,
        body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links>a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }

    </style>

    <style>
        #map {
            height: 100%;
            width: 100%;
        }

    </style>
</head>

<body>
    {{-- <div id="notification"></div> --}}
    <div id="map"></div>
</body>


<script>
    window.laravel_echo_port = `{{ env('LARAVEL_ECHO_PORT') }}`;

</script>
<script src="//{{ Request::getHost() }}:{{ env('LARAVEL_ECHO_PORT') }}/socket.io/socket.io.js"></script>
<script src="{{ url('/js/laravel-echo-setup.js') }}" type="text/javascript"></script>

<script type="text/javascript">
    $(document).ready(() => {
        let i = 0;
        window.Echo.channel('channel-name').listen('.UserEvent', (data) => {
            i++;
            $("#notification").append('<div class="alert alert-success">Lat: ' + data.lat + ' Long: ' +
                data.long +
                '</div>');
        });
    })

</script>




{{-- <script>
    var map;
    var service;
    var infowindow;
    // Initialize and add the map
    function initMap() {
        window.Echo.channel('channel-name').listen('.UserEvent', (data) => {
            var latti = data.lat;
            var lngti = data.long;
            // The location of Uluru
            // var coordinates = {
            //     lat: parseFloat(latti),
            //     lng: parseFloat(lngti)
            // };
            var coordinates = new google.maps.LatLng(parseFloat(latti), parseFloat(lngti));
            var myOptions = {
                zoom: 19,
                center: coordinates,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            // The map, centered at coordinates
            var map = new google.maps.Map(document.getElementById('map'), myOptions);
            // The marker, positioned at coordinates
            var marker = new google.maps.Marker({
                position: coordinates,
                map: map
            });
        });
        var latti = 21.0277644;
        var lngti = 105.83415979999995;
        // The location of Uluru
        // var coordinates = {
        //     lat: parseFloat(latti),
        //     lng: parseFloat(lngti)
        // };
        var coordinates = new google.maps.LatLng(parseFloat(latti), parseFloat(lngti));
        var myOptions = {
            zoom: 19,
            center: coordinates,
            mapTypeId: google.maps.MapTypeId.MAP
        };
        // The map, centered at coordinates
        var map = new google.maps.Map(document.getElementById('map'), myOptions);
        // The marker, positioned at coordinates
        var marker = new google.maps.Marker({
            position: coordinates,
            map: map
        });
    }

</script> --}}

<script>
    function initMap() {

        let map;
        let markers = [];

        const image = {
            url: "https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png",
            size: new google.maps.Size(20, 32),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(0, 32)
        };
        const shape = {
            coords: [1, 1, 1, 20, 18, 20, 18, 1],
            type: "poly"
        };

        const makeMarker = (coordinates) => {
            return new google.maps.Marker({
                position: coordinates,
                icon: image,
                shape: shape,
                draggable: true,
                animation: google.maps.Animation.BOUNCE
            })
        }

        function addMarker(marker) {
            markers.push(marker);
            setMapOnAll(map)
        }

        function setMapOnAll(map) {
            for (let i = 0; i < markers.length; i++) {
                markers[i].setMap(map);
                if(map){
                    const position = JSON.parse(JSON.stringify(markers[i].position))
                    console.log(position)
                    map.setCenter(new google.maps.LatLng(parseFloat(position.lat), parseFloat(position.lng)));
                }
             
            }
        }

        function clearMarkers() {
            setMapOnAll(null);
        }

        function deleteMarkers() {
            clearMarkers();
            markers = [];
        }

        let arrCoordinates = [{
                lat: 21.030590,
                long: 105.800971
            },
            {
                lat: 21.030699,
                long: 105.800878
            },
            {
                lat: 21.030932,
                long: 105.800629
            },
            {
                lat: 21.031100,
                long: 105.800413
            },
            {
                lat: 21.031100 + (0.000510 * 1),
                long: 105.800413 - 0.000216
            },
            {
                lat: 21.031100 + (0.000510 * 2),
                long: 105.800413 - (0.000216 * 2)
            },
            {
                lat: 21.031100 + (0.000510 * 3),
                long: 105.800413 - (0.000216 * 3)
            },
            {
                lat: 21.031100 + (0.000510 * 4),
                long: 105.800413 - (0.000216 * 4)
            },
        ];

        let times = 0;
        let timerId = setInterval(() => {
            times++;
            if (times < arrCoordinates.length) {
                var lat = arrCoordinates[times]['lat'];
                var long = arrCoordinates[times]['long'];
                var url = `{{ url('/api/send-coordinates') }}`
                $.get(url, {
                    lat: arrCoordinates[times]['lat'],
                    long: arrCoordinates[times]['long']
                })
            }
        }, 1000);

        // Clear intervals after 6 sec with the timer id 
        setTimeout(() => {
            clearInterval(timerId);
        }, 3000 * arrCoordinates.length)


        window.Echo.channel('channel-name').listen('.UserEvent', (data) => {
            let latti = data.lat;
            let lngti = data.long;
            let coordinates = new google.maps.LatLng(parseFloat(latti), parseFloat(lngti));
            let myOptions = {
                zoom: 18,
                center: coordinates,
                mapTypeId: "satellite",
            };
            deleteMarkers();
            addMarker(makeMarker(coordinates))
        });

        // vị trí khỏi điểm. Tam cốc bích động
        let latti = 21.030590;
        let lngti = 105.800971;
        let coordinates = new google.maps.LatLng(parseFloat(latti), parseFloat(lngti));
        const directionsService = new google.maps.DirectionsService();
        const directionsRenderer = new google.maps.DirectionsRenderer();
        map = new google.maps.Map(document.getElementById("map"), {
            zoom: 18,
            center: coordinates,
            mapTypeId: "satellite",
            heading: 90,
            tilt: 45
        });
        addMarker(makeMarker(coordinates));



        // lấy lat long trên map khi click vào 1 vị trí
        // Create the initial InfoWindow.
        let infoWindow = new google.maps.InfoWindow({
            content: 'Click the map to get Lat/Lng!',
            // position: coordinates
        });
        infoWindow.open(map);

        // Configure the click listener.
        map.addListener('click', function(mapsMouseEvent) {
            console.log(mapsMouseEvent.latLng)
            // Close the current InfoWindow.
            infoWindow.close();
            // Create a new InfoWindow.
            infoWindow = new google.maps.InfoWindow({
                position: mapsMouseEvent.latLng
            });
            infoWindow.setContent(mapsMouseEvent.latLng.toString());
            infoWindow.open(map);
        });
    }

    // vẽ di chuyển hình tròn
    function animateCircle(line) {
        let count = 0;
        window.setInterval(() => {
            count = (count + 1) % 500;
            const icons = line.get("icons");
            icons[0].offset = count / 5 + "%";
            line.set("icons", icons);
        }, 20);
    }

    // zoom
    function rotate90() {
        const heading = map.getHeading() || 0;
        map.setHeading(heading + 90);
    }

    function autoRotate() {
        // Determine if we're showing aerial imagery.
        if (map.getTilt() !== 0) {
            window.setInterval(rotate90, 3000);
        }
    }

</script>

</html>
