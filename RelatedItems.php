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
</head>

<body>
    
    <?php
        require './class/Database.php';
        session_start();
        $db=new Database();
        if ($_SERVER["REQUEST_METHOD"] == "GET") 
        {
            $StoreId=(int)$_GET['StoreId'];
            $params = array($StoreId,$_SESSION["CurrentUser_ID"]);

            $store=$db->GetTable("SELECT * FROM tbStores where ID=? and UserRef=?",$params);
            //اگر فروشگاهی با این شناسه پیدا نشد
            if(sqlsrv_num_rows($store)!=1)
            {
                die("Store Not Found");
                exit();
            }
            else
            {
                $rowStore=sqlsrv_fetch_array($store);
                $storeTitle=$rowStore["Title"];
            }
            $items=$db->GetTable("
                SELECT i.[ID]
                    ,itm.Title
                    ,i.[Quantity]
                    ,i.[Fee]
                FROM [tbStoreItems] i
                inner join tbStores st on st.id=i.StoreRef
                inner join tbItems itm on itm.ID = i.ItemRef
                WHERE i.StoreRef=? 
                AND st.UserRef=?
            ",$params);
        }
    ?>

    <div class="content">
        <?php include('header.php'); ?>
        <div class="p-5 ">
            <h2>کالاهای مرتبط با فروشگاه - <?php echo $storeTitle ?></h2>
                <p>از طریق این بخش کالاهای مرتبط با فروشگاه را مدیریت نمایید.</p>
                <hr class="hr" />
                <a class="btn btn-primary mb-3" href="EditRelatedItem.php?id=0&StoreId=<?php echo $StoreId ?>">کالا/خدمت جدید</a>
                <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>عنوان کالا/خدمت</th>
                        <th>تعداد</th>
                        <th>قیمت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                    <tbody>
                    <?php
                        while ($row = sqlsrv_fetch_array($items, SQLSRV_FETCH_ASSOC))
                        {
                            echo '<tr>';
                            echo '<td>'.$row["Title"].'</td>';
                            echo '<td>'.$row["Quantity"].'</td>';
                            echo '<td>'.$row["Fee"].'</td>';
                            echo '<td>
                                <a class="btn btn-primary"  href="EditRelatedItem.php?StoreId='.$StoreId.'&id='.$row["ID"].'">ویرایش</a>
                                <a class="btn btn-danger">حذف</a>
                                </td> 
                             </tr>';
                        }
                        ?>
                    </tbody>
                </table>
                </div>
        </div>

        <script>
            

        </script>

    </body>
</html>
