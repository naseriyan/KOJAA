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
    <link href="./css/select2.min.css" rel="stylesheet">
    <script src="./js/bootstrap.bundle.min.js"></script>
    <script src="./js/jquery.min.js"></script>
    <script src="./js/select2.min.js"></script>

</head>

<body>
    <?php
        require './class/Database.php';
        session_start();
        $db=new Database();
        $items=$db->GetTable("SELECT * FROM tbItems",null);

        if ($_SERVER["REQUEST_METHOD"] == "GET") 
        {
            $id=(int)$_GET['id'];
            $storeRef=(int)$_GET['StoreId'];
            $itemRef=0;

            if($id>0)
            {
                $query="SELECT i.*
                FROM [tbStoreItems] i
                inner join tbStores st on st.id=i.StoreRef
                WHERE i.ID=?
                AND st.UserRef=?
                AND i.StoreRef=?
                ";
                $paramsInfo = array($id,$_SESSION['CurrentUser_ID'],$storeRef);

                $currentItem= $db->GetTable($query,$paramsInfo);
                //اگر کالایی با این شناسه پیدا نشد
                if(sqlsrv_num_rows($currentItem)!=1)
                {
                    die("Item Not Found");
                    exit();
                }
                else
                {
                    $row=sqlsrv_fetch_array($currentItem);
                    $storeRef=$row["StoreRef"];
                    $itemRef=$row["ItemRef"];
                    $quantity=$row["Quantity"];
                    $fee=$row["Fee"];
                }
            }
        }


        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            unset($_SESSION['form_error_item']);
            
            $id=(int)$_POST['id'];
            $storeRef = $_POST['StoreRef'];
            $itemRef = $_POST['ItemRef'];
            $quantity = $_POST['Quantity'];
            $fee = $_POST['Fee'];
    
            if($id>0)
            {
                $query=" 
                    UPDATE [tbStoreItems]
                    SET [Quantity] =? 
                    ,[Fee] =?
                    ,[LastUpdateDate] = getdate()
                WHERE ID=? AND StoreRef=?";
                $paramsInfo = array($quantity,$fee,$id,$storeRef);

            }
            else
            {
                $query = "INSERT INTO [tbStoreItems]
                ([StoreRef]
                ,[ItemRef]
                ,[Quantity]
                ,[Fee]) 
                values(?,?,?,?)";
                $paramsInfo = array($storeRef,$itemRef,$quantity,$fee);
            }

            $result= $db->ExecuteQuery($query,$paramsInfo);

            if ($result === false) 
            {
                die(print_r(sqlsrv_errors(), true));
                exit();
            }
            header('Location: RelatedItems.php?StoreId='.$storeRef);
    
        }

    ?>
    <div class="content">
        <?php include('header.php'); ?>
        <div class="p-5 ">
            <?php
                if($id>0)
                    echo '<h2>ویرایش کالا/خدمت مرتبط</h2>';
                else
                    echo '<h2>تعریف کالا/خدمت جدید</h2>';
            ?>
            <p>از طریق این بخش میتوانید کالا/خدمت جدید ایجاد نمایید و یا اطلاعات قبلی را اصلاح نمایید.</p>
            <hr class="hr" />
           
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3 p-md-4 p-xl-5">
                <h6>اطلاعات کالا/خدمت</h6>
          
            <form action="EditRelatedItem.php" method="POST">
                <?php echo '<input type="hidden" id="id" name="id" value="'.$id.'"/>'; ?>
                <?php echo '<input type="hidden" id="StoreRef" name="StoreRef" value="'.$storeRef.'"/>'; ?>
                <div class="row gy-3 overflow-hidden">
                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <select class="form-control" name="ItemRef" id="ItemRef" placeholder="کالا/خدمت" required
                            <?php if($id>0) echo 'disabled' ?> 
                            >
                                <?php
                                    while ($row = sqlsrv_fetch_array($items, SQLSRV_FETCH_ASSOC))
                                    {
                                        if($row["ID"]==$itemRef)
                                            echo '<option value="'.$row["ID"].'" selected>'.$row["Title"].'</option>';
                                        else
                                            echo '<option value="'.$row["ID"].'">'.$row["Title"].'</option>';

                                    }
                                ?>
                            </select>
                            <label for="ItemRef" class="form-label">کالا/خدمت</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="Quantity" id="Quantity" 
                            placeholder="تعداد" required
                            value="<?php if(isset($quantity)) echo $quantity ?>">
                            <label for="Quantity" class="form-label">تعداد</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="Fee" id="Fee" 
                            placeholder="مبلغ" required
                            value="<?php if(isset($fee)) echo $fee ?>">
                            <label for="Fee" class="form-label">مبلغ</label>
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
          if(isset($_SESSION['form_error_item'])){
            echo '<div class="col-12 bg-warning">
              <div class="d-grid">
                <p>';
                        echo $_SESSION['form_error_item'];
                echo '</p>
              </div>
            </div>';
            unset($_SESSION['form_error_item']);
            }
            ?>

                </div>
           </form>
           </div>
           </div>
           
        </div>
    </div>

    <script>
  $(document).ready(function() {
    $('#ItemRef').select2();
  });
</script>

</body>

</html>
