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
    <script src="https://static.neshan.org/sdk/openlayers/4.6.5/ol.js" type="text/javascript"></script>
    
</head>

<body>


<script>
        function LoadMap() 
        {
            var lat=Number(document.getElementById('lat').value);
            var long=Number(document.getElementById('long').value);
            var distance=Number(document.getElementById('distance').value);
            var point=[long,lat];

            var map = new ol.Map({
                target: 'map',
                key: 'web.05d3ab77678d4e0cac24f6c45ff15bf7',
                maptype: 'neshan',
                poi: true,
                traffic: false,
                view: new ol.View({
                    center: ol.proj.fromLonLat(point),
                    zoom: 12,
                })
            });
           
            
            
            <?php
                require './class/Database.php';

                $q=$_GET['q'];
                $lat=$_GET['lat'];
                $long=$_GET['long'];
                $distance=(int)$_GET['distance'];
                $searchType=$_GET['SearchType'];
    
                $db=new Database();
                $query="
                    DECLARE @source geography =geography::Point(".$lat.", ".$long.", 4326)
                    select st.Title as StoreTitle,itm.Title as ItemTitle,st.Lat,st.Long
                    ,st.Address,st.Tell 
                    ,sitm.Fee
                    ,sitm.Quantity
                    ,cast(@source.STDistance(geography::Point(st.Lat, st.Long, 4326)) as bigint)  as Distance
                    from tbStoreItems sitm
                    inner join tbStores st on st.ID = sitm.StoreRef
                    inner join tbItems itm on itm.ID = sitm.ItemRef
                    where cast(@source.STDistance(geography::Point(st.Lat, st.Long, 4326)) as bigint)<=?
                    AND sitm.Quantity>0
                ";
                if($searchType==1)
                {
                    $query=$query." AND itm.Title like ?";
                    $params = array($distance,'%'.$q.'%');
                }
                else
                {
                    $query=$query." AND (itm.Title like ? OR st.Title like ?)";
                    $params = array($distance,'%'.$q.'%','%'.$q.'%');

                }
    
                $resault=$db->GetTable($query,$params);
                
                while ($row = sqlsrv_fetch_array($resault, SQLSRV_FETCH_ASSOC))
                {
                    echo "
                    var marker = new ol.Overlay(
                    {
                        position: ol.proj.fromLonLat([".$row["Long"].",".$row["Lat"]."]),
                        positioning: 'center-center',
                        element: document.createElement('div'),
                        stopEvent: false
                    });

                    marker.getElement().style.background = 'url(./images/StoreIcon.png) no-repeat';
                    marker.getElement().style.width = '32px';
                    marker.getElement().style.height = '48px';
                    marker.getElement().style.cursor = 'pointer';
                    map.addOverlay(marker);
                    
                    marker.getElement().addEventListener('click', function(event) {
                        ShowInfo('".$row["StoreTitle"]."','".$row["Address"]."','".$row["Tell"]."','".$row["ItemTitle"]."'
                        ,'".$row["Fee"]."','".$row["Quantity"]."','".$row["Distance"]."','".$row["Lat"]."','".$row["Long"]."');
                    }
                        
                    );
                        ";
                }


            ?>

            var marker = new ol.Overlay( 
                {
                    position: ol.proj.fromLonLat(point),
                    positioning: 'center-center',
                    element: document.createElement('div'),
                    stopEvent: false
                });

            marker.getElement().style.background = 'url(./images/bullon48_73.png) no-repeat';
            marker.getElement().style.width = '48px';
            marker.getElement().style.height = '73px';
            marker.getElement().style.cursor = 'pointer';
            map.addOverlay(marker);
        }        

            
        function SetAddress(lat,long)
        {
            $.ajax({ 
            type : "GET", 
            url : "https://api.neshan.org/v5/reverse?lat="+lat+"&lng="+long, 
            beforeSend: function(xhr){xhr.setRequestHeader('Api-Key', 'service.a0f1b1d74ea94a8ba88cb55ff5098c55');},
            success : function(result) { 
                document.getElementById('Address').value=result.formatted_address;
            }, 
            error : function(result) { 
                alert("خطا در دریافت آدرس") 
            } 
            }); 
        }

            function ShowInfo(StoreTitle,Address,Tell,ItemTitle,Fee,Quantity,Distance,Lat,Long)
            {
                document.getElementById('StoreTitle').value=StoreTitle;
                document.getElementById('Address').value=Address;
                document.getElementById('Tell').value=Tell;
                document.getElementById('ItemTitle').value=ItemTitle;
                document.getElementById('Fee').value=Fee;
                document.getElementById('Quantity').value=Quantity;
                document.getElementById('Distance').value=Distance;
                // SetAddress(Lat,Long);
            }





        $(document).ready(function() {
            LoadMap();
        });

      
    </script>


    <div class="content" >
     <form action="Resault.php" method="GET">
        
        <div class="d-flex align-items-center">
            <div class="col-11 d-flex align-items-center">
                <a href="./Index.php">
                <img src="images/kojaa.png" alt="کجا داره؟" class="img-fluid col-2" style="width: 150px; height: 50px;">
                </a>
                <div class="form-floating col-2">
                                <input type="number" class="form-control" name="distance" id="distance" 
                                placeholder="حداکثر فاصله مدنظر(متر)" required 
                                value="<?php if(isset($distance)) echo $distance ?>">
                                <label for="distance" class="form-label">حداکثر فاصله مدنظر(متر)</label>
                </div>

                <div class="form-floating col-5" style="margin-right:5px;">
                                <input type="text" class="form-control" name="q" id="q" 
                                placeholder="کالا/خدمت" required
                                value="<?php if(isset($q)) echo $q ?>">
                                <label for="q" class="form-label">کالا/خدمت</label>
                </div>

                <input type="hidden" id="lat" name="lat" value="<?php echo $lat; ?>"/>
                <input type="hidden" id="long" name="long" value="<?php echo $long; ?>"/>

                <div class="col-3 text-center" dir="rtl">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="SearchType" id="Radio1" 
                        value="1"
                        <?php if($searchType==1) echo 'checked'; ?>
                        >
                        <label class="form-check-label" for="Radio1">جستجو در کالاها</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="SearchType" id="Radio2" value="2"
                        <?php if($searchType==2) echo 'checked';?>
                        >
                        <label class="form-check-label" for="Radio2">جستجو در کالاها و عناوین فروشگاه ها</label>
                    </div>
                </div>

                <button class="btn btn-light  col-1" style="margin-right:5px;" type="submit">جستجو</button>
            </div>

        </div>

        

    </form>
        <hr class="hr" />
        <div class="row col-12">
            <div id="map" style="height:100%;" class="col-8"></div>
            <div id="info" style="height:100%;" class="col-4">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="ItemTitle" id="ItemTitle" 
                    placeholder="عنوان کالا/خدمت" readonly>
                    <label for="ItemTitle" class="form-label">عنوان کالا/خدمت</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="Fee" id="Fee" 
                    placeholder="قیمت" readonly>
                    <label for="Fee" class="form-label">قیمت</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="Quantity" id="Quantity" 
                    placeholder="موجودی" readonly>
                    <label for="Fee" class="form-label">موجودی</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="StoreTitle" id="StoreTitle" 
                    placeholder="عنوان فروشگاه" readonly>
                    <label for="StoreTitle" class="form-label">عنوان فروشگاه</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="Address" id="Address" 
                    placeholder="آدرس فروشگاه" readonly>
                    <label for="Address" class="form-label">آدرس فروشگاه</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="Tell" id="Tell" 
                    placeholder="شماره تماس فروشگاه" readonly>
                    <label for="Tell" class="form-label">شماره تماس فروشگاه</label>
                </div>
                
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="Distance" id="Distance" 
                    placeholder="فاصله(متر)" readonly>
                    <label for="Distance" class="form-label">فاصله(متر)</label>
                </div>
               
                
                
            </div>
        </div>
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
