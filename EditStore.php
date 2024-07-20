<!DOCTYPE html>
<html dir="rtl">
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

  <script>
        function LoadMap() 
        {
            
            var lat=Number(document.getElementById('lat').value);
            var long=Number(document.getElementById('long').value);
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

        marker.getElement().style.background = 'url(./images/StoreIcon.png) no-repeat';
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

</head>

<body>
    <?php
        require './class/Database.php';
        session_start();
        $db=new Database();
        $groups=$db->GetTable("SELECT * FROM tbStoresGroups",null);

        if ($_SERVER["REQUEST_METHOD"] == "GET") 
        {
            $id=(int)$_GET['id'];
            if($id>0)
            {
                $query="SELECT  [ID],[UserRef],[Title],[Lat],[Long],[GroupRef],[Address],[Tell],[CreateDateTime]  
                FROM [tbStores] WHERE UserRef=? AND ID=?";
                $paramsInfo = array($_SESSION['CurrentUser_ID'], $id);
                $currentStore= $db->GetTable($query,$paramsInfo);
                //اگر فروشگاهی با این شناسه پیدا نشد
                if(sqlsrv_num_rows($currentStore)!=1)
                {
                    die("Store Not Found");
                    exit();
                }
                else
                {
                    $row=sqlsrv_fetch_array($currentStore);
                    $title = $row['Title'];
                    $address = $row['Address'];
                    $tell = $row['Tell'];
                    $GroupRef = $row['GroupRef'];
                    $lat = $row['Lat'];
                    $long = $row['Long'];
                }
            }
            else
            {
                //set default location - daneshgah!
                $lat=36.295978;
                $long=59.591107;
            }
        }


        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            unset($_SESSION['form_error_store']);
            
            $id=(int)$_POST['id'];
            $title = $_POST['Title'];
            $address = $_POST['Address'];
            $tell = $_POST['Tell'];
            $GroupRef = $_POST['GroupRef'];
            $lat = $_POST['lat'];
            $long = $_POST['long'];
    
            if ($lat=="" || $long=="") 
            {
                $_SESSION['form_error_store'] = "مختصات فروشگاه وارد نشده است";
            } 
            else 
            {
                    if($id>0)
                    {
                        $query=" UPDATE [tbStores] SET Title=?,Lat=?,Long=?,GroupRef=?,Address=?,Tell=?
                        WHERE ID=? AND UserRef=?";
                        $paramsInfo = array($title,$lat,$long,$GroupRef,$address,$tell,$id,$_SESSION['CurrentUser_ID']);
                    }
                    else
                    {
                        $query = "INSERT INTO [tbStores]([UserRef],[Title],[Lat],[Long],[GroupRef],[Address],[Tell]) 
                        values(?,?,?,?,?,?,?)";
                        $paramsInfo = array($_SESSION['CurrentUser_ID'], $title,$lat,$long,$GroupRef,$address,$tell);
                    }
                    $result= $db->ExecuteQuery($query,$paramsInfo);

                    if ($result === false) 
                    {
                        die(print_r(sqlsrv_errors(), true));
                        exit();
                    }
                    header('Location: stores.php');
            }
    
        }

    ?>
    <div class="content">
        <?php include('header.php'); ?>
        <div class="p-5 ">
            <?php
                if($id>0)
                    echo '<h2>ویرایش اطلاعات فروشگاه</h2>';
                else
                    echo '<h2>فروشگاه جدید</h2>';
            ?>
            <p>از طریق این بخش میتوانید فروشگاه جدیدی ایجاد نموده و یا اطلاعات فروشگاه خود را اصلاح نمایید.</p>
            <hr class="hr" />
           
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3 p-md-4 p-xl-5">
                <h6>اطلاعات فروشگاه</h6>
          
            <form action="EditStore.php" method="POST">
                <?php echo '<input type="hidden" id="id" name="id" value="'.$id.'"/>'; ?>
                <div class="row gy-3 overflow-hidden">

                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="Title" id="Title" 
                            placeholder="عنوان فروشگاه" required
                            value="<?php if(isset($title)) echo $title ?>">
                            <label for="Title" class="form-label">عنوان فروشگاه</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="Address" id="Address" 
                            placeholder="آدرس فروشگاه" required
                            value="<?php if(isset($address)) echo $address ?>"
                            >
                            <label for="Address" class="form-label">آدرس فروشگاه</label>
                        </div>
                        <div class="col-12">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="Tell" id="Tell" 
                            placeholder="تلفن فروشگاه" required
                            value="<?php if(isset($tell)) echo $tell ?>"
                            >
                            <label for="tell" class="form-label">تلفن فروشگاه</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <select class="form-control" name="GroupRef" id="GroupRef" placeholder="گروه فروشگاهی" required>
                                <?php
                                    while ($row = sqlsrv_fetch_array($groups, SQLSRV_FETCH_ASSOC))
                                    {
                                        if($row["ID"]==$GroupRef)
                                            echo '<option value="'.$row["ID"].'" selected>'.$row["Title"].'</option>';
                                        else
                                            echo '<option value="'.$row["ID"].'">'.$row["Title"].'</option>';

                                    }
                                ?>
                            </select>
                            <label for="GroupRef" class="form-label">گروه فروشگاهی</label>
                        </div>
                    </div>
                    
                    <br>
                    <div class="col-12" id="map" style="width: 100%; height: 400px;"></div>
                    <br>
                    <div class="col-6 col-xs-12">
                        <div class="input-group form-floating m-3 col-6 col-xs-12">
                            <input type="text" id="lat" name="lat" class="form-control" readonly
                            value="<?php if(isset($lat)) echo $lat ?>"
                            >
                            <input type="text" id="long" name="long" class="form-control" readonly
                            value="<?php if(isset($long)) echo $long ?>"
                            >
                        </div>
                    </div>


                  <div class="col-12">
                    <div class="d-grid">
                        <div class="form-floating mb-3">
                            <button class="btn bsb-btn-2xl btn-primary" type="submit">ثبت تغییرات</button>
                            <a class="btn bsb-btn-2xl btn-danger" href="Stores.php">انصراف</a>
                        </div>
                    </div>
                  </div>

                  <?php
          if(isset($_SESSION['form_error_store'])){
            echo '<div class="col-12 bg-warning">
              <div class="d-grid">
                <p>';
                        echo $_SESSION['form_error_store'];
                echo '</p>
              </div>
            </div>';
            unset($_SESSION['form_error_store']);
            }
            ?>

                </div>
           </form>
           </div>
           </div>
           
        </div>
    </div>
</body>

</html>
