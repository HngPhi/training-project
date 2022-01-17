<nav aria-label="Page navigation example" style="margin: 20px auto; width: 960px;">
    <ul class="pagination justify-content-end">
        <?php
            $urlSearch = URL_SEARCH_ADMIN;
            if($_GET['controller'] == 'user') $urlSearch = URL_SEARCH_USER;
            echo "<li class='page-item'><a class='page-link' href=" . $urlSearch . $addUrlPagging . "&page={$previous}>Previous</a></li>";
            for($i=1; $i<=$totalPage; $i++){
                $active = "";
                if($i == $page){
                    $active = 'active';
                }
                echo "<li class='page-item {$active}'><a class='page-link' href=" . $urlSearch . $addUrlPagging . "&page={$i}>".$i."</a></li>";
            }
            echo "<li class='page-item'><a class='page-link' href=" . $urlSearch . $addUrlPagging . "&page={$next}>Next</a></li>";
        ?>
    </ul>
</nav>