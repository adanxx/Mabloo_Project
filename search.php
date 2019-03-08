<?php 
    require_once "include/config.php";
    require_once "include/classes/siteResultProvider.php";
    require_once "include/classes/imageResultProvider.php";

   if(isset($_GET['term'])){
        $term = $_GET['term']; 
   }else{
       exit("Missing a search term");
   }

   $type= isset($_GET['type']) ? $_GET['type'] : "sites";
   $page= isset($_GET['page']) ? $_GET['page'] : 1;


?>

<?php require_once("components/header.php")?>


<div class="wrapper">
    <div class="header">
        
        <div class="headerContent">
            
            <div class="logoContainer">
                <a href="index.php">
                <img src="assets/img/logo/logo.png" alt="logo_image">
                </a>
            </div>

            <div class="searchContainerbox">

                <form action="search.php" method="GET">
                    
                    <div class="searchBarContainer">
                        <input type="hidden" name="type" value=<?php echo $type ?> >
                        <input type="text" name="term" class="searchBox" value="<?php echo $term; ?>">
                        <button class="search-icon-btn">
                            <img src="assets/img/icons/search-icon.png" alt="search-.icon" >
                        </button>
                    </div>

                </form>

            </div>  
            
        </div>

        <div class="tabsContainer">
            <ul class="tablist">
                <li class="<?php echo $type == "sites" ? 'active' :'' ?>">
                    <a href='<?php echo "search.php?term=$term&type=sites";?>'>Site</a>
                </li>
                <li  class="<?php echo $type == "images" ? 'active' :'' ?>">
                    <a href='<?php echo "search.php?term=$term&type=images";?>'>Images</a>
                </li>
                
            </ul>
        </div>
    </div>   

    <div class="mainResultSection">
        
        <?php

          if($type == "sites"){
            $resultPorvider = new siteResultProvider($conn);
            $pageLimit = 20; 
           
          }else{
            $resultPorvider = new imageResultProvider($conn);
            $pageLimit = 30; 
          }
          $numResult = $resultPorvider->getNumResults($term); //return the number of result found

        
          echo "<p class='resultCount'>$numResult  result found</p>";
          echo $resultPorvider->getResultHtml($page, $pageLimit , $term);
        ?>
        
    </div>

    <div class="paginationContainer">

        <div class="pageButtons">
      
            <div class="pageNumberContainer">
              <img src="assets/img/logo/brandStart.png" alt="">  
            </div>

            <?php
            
            
                $pagesToShow = 10;
                $numPage     =  ceil($numResult/$pageLimit);
                $pagesLeft   = min($pagesToShow, $numPage);

                $currentPage = $page - floor($pagesToShow / 2);

                if($currentPage < 1 ){
                    $currentPage = 1;
                }

                if($currentPage + $pagesLeft > $numPage + 1){
                    $currentPage = $numPage +1 - $pagesLeft;
                }
             
              
                // for ($i=0; $i < $pages; $i++) { 
                while($pagesLeft != 0 && $currentPage <=$numPage){


                    if($currentPage == $page){
                        echo "<div class='pageNumberContainer'>
                            <img src='assets/img/logo/brandSelected.png'>
                            <span class='pageNumber'> $currentPage</span>
                        </div>";
                    }else{
                        echo "<div class='pageNumberContainer'>
                          <a href='search.php?term=$term&type=$type&page=$currentPage'>
                            <img src='assets/img/logo/brand.png'>
                            <span class='pageNumber'>$currentPage</span>
                         </a>
                       </div>";
                    }                 

                    $currentPage++;
                    $pagesLeft--;
                    
                }

            
            ?>


            <div class="pageNumberContainer">
              <img src="assets/img/logo/brandEnd.png" alt="">  
            </div>
        </div>

    
    </div>

</div>

<?php require_once("components/footer.php")?>