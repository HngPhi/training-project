<nav aria-label="Page navigation example" style="margin: 20px auto; width: 960px;">
    <ul class="pagination justify-content-end">
        <?php
            $url_search = URL_SEARCH_ADMIN;
            if($_GET['controller'] == 'user') $url_search = URL_SEARCH_USER;
            echo "<li class='page-item'><a class='page-link' href=" . $url_search . $add_url_pagging . "&page={$previous}>Previous</a></li>";
            for($i=1; $i<=$total_page; $i++){
                $active = "";
                if($i == $page){
                    $active = 'active';
                }
                echo "<li class='page-item {$active}'><a class='page-link' href=" . $url_search . $add_url_pagging . "&page={$i}>".$i."</a></li>";
            }
            echo "<li class='page-item'><a class='page-link' href=" . $url_search . $add_url_pagging . "&page={$next}>Next</a></li>";
        ?>
    </ul>
</nav>