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
    ?>

    <div class="content">
        <?php include('header.php'); ?>
        <form action="Resault.php" method="GET">
            <section>
                <img src="images/kojaa.png" alt="کجا داره؟!!" class="google_logo">
                <div class="search-btns">
                    <div class="search_bar">
                        <input type="text" id="q" name="q">
                        <div class="mic_space">
                            <img src="images/google-mic.PNG" alt="Mic" class="mic_icon">
                        </div>
                    </div>
                    <br>
                    <div class="btns_centered">
                        <button  id="btn_search" type="submit"> جستجو نزدیکترین</button>
                        <button id="btn_lucky"  type="submit">جستجوی همه</button>
                    </div>
                </div>
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
