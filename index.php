<?php require_once("components/header.php")?>

    <div class="top-navbar">
        <div class="inner-wrapper">
            <ul class="icon-lists">
                <li><i class="fas fa-bezier-curve"></i></li>
                <li>
                    <a href="login.php" class="btn btn-primary">LOGIN</a>
                </li>
            </ul>
        </div>
    </div>     
     

    <div class="wrapper indexPage"> 
      <div class="mainSection">
            <div class="logoContainer">
                <img src="assets/img/logo/logo.png" alt="logo_image">
            </div>      

            <div class="searchContainer">
                <form action="search.php" method="GET">
                        <input type="text" class="searchbox" name="term" placeholder="">
                        <input type="submit" class="search-btn"  value="Search">
                </form>
            
            </div>
        </div>

   </div>


<?php require_once("components/footer.php")?>