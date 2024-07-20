<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <title>کجا داره؟!</title>
    <meta charset="utf-8">
    <link rel="icon" type="image/x-icon" href="./images/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link href="./css/styles.css" rel="stylesheet">
    <link href="./css/fonts.css" rel="stylesheet">
    <script src="./js/bootstrap.bundle.min.js"></script>
    <script src="./js/jquery.min.js"></script>

    <link href="https://static.neshan.org/sdk/openlayers/4.6.5/ol.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>
    <script src="https://static.neshan.org/sdk/openlayers/4.6.5/ol.js" type="text/javascript"></script>
</head>

<body>
    <?php
        session_start();

    ?>

    
<script>
        function LoadMap() 
        {
            var lat=Number(36.295452059515185);
            var long=Number(59.59048748016356);

            document.getElementById('lat').value = lat;
            document.getElementById('long').value = long;
            
            var point=[long,lat];

            var map = new ol.Map({
                target: 'map',
                key: 'web.05d3ab77678d4e0cac24f6c45ff15bf7',
                maptype: 'neshan',
                poi: true,
                traffic: false,
                view: new ol.View({
                    center: ol.proj.fromLonLat(point),
                    zoom: 14,
                })
            });
           
            var marker = new ol.Overlay({
            position: ol.proj.fromLonLat(point),
            positioning: 'center-center',
            element: document.createElement('div'),
            stopEvent: false
        });

        marker.getElement().style.background = 'url(https://openlayers.org/en/latest/examples/data/icon.png) no-repeat';
        marker.getElement().style.width = '32px';
        marker.getElement().style.height = '48px';
        marker.getElement().style.cursor = 'pointer';
        map.addOverlay(marker);

        map.on('click', function(event) {
            // مختصات
            var coordinates = ol.proj.toLonLat(event.coordinate);
            // console.log(event.coordinate); 
            marker.setPosition(event.coordinate);
            document.getElementById('lat').value = coordinates[1];
            document.getElementById('long').value = coordinates[0];
        });

        }        

        $(document).ready(function() {
            LoadMap();
        });

      
    </script>

    <div class="content">
        <?php include('header.php'); ?>
        <form action="Resault.php" method="GET">
            <section>
                <img src="images/kojaa.png" alt="کجا داره؟!!" class="google_logo">
                <div class="search-btns col-xl-6 col-lg-6 col-sm-10 col-xs-10">
                    <div class="search_bar">
                        <input type="text" id="q" name="q" required>
                        <div class="mic_space">
                            <img src="images/google-mic.PNG" alt="Mic" class="mic_icon">
                        </div>
                    </div>
                    <br>
                    <div class="btns_centered">
                        <button  id="btn_search" type="submit">جستجو</button>
                    </div>
                    <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="distance" id="distance" 
                            placeholder="حداکثر فاصله مد نظر(متر)" required value="1000">
                            <label for="Title" class="form-label">حداکثر فاصله مد نظر(متر) </label>
                        </div>                    
                        <h6 class="pt-3">موقعیت مورد نظر خود را مشخص نمایید:</h6>

                    <div id="map" style="height: 300px;" style="col-12">

                    </div>
                </div>
                <input type="hidden" id="lat" name="lat" class="form-control" readonly
                            value="<?php if(isset($lat)) echo $lat ?>">
                <input type="hidden" id="long" name="long" class="form-control" readonly
                            value="<?php if(isset($long)) echo $long ?>">
            </section>
        <form>
    </div>

    <footer>
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link about float-start" href="#">درباره ما</a>
            </li>
        </ul>
    </footer>


</body>
</html>
