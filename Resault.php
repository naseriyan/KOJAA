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
        session_start();
        if ($_SERVER["REQUEST_METHOD"] == "GET") 
        {
            $q=(int)$_GET['q'];

        }
    ?>

    <div class="content" >
    <form action="Resault.php" method="GET">
        
        <div class="d-flex align-items-center">
            <div class="col-6">
                <?php include('header.php'); ?>
            </div>

            <img src="images/kojaa.png" alt="کجا داره؟" class="img-fluid" style="width: 150px; height: 50px;">
            <input type="text" class="form-control mx-2" id="q" name="q"
            value="<?php if(isset($q)) echo $q ?>">
            <button class="btn btn-light" type="submit">جستجو</button>
        </div>
    </form>
        <hr class="hr" />

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
