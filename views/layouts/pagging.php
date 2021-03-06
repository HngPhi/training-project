<?php
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $previous = ($page > 1) ? $page - 1 : $page;
    $next = $page < $totalPage ? $page + 1 : $page;
?>

<nav aria-label="Page navigation example" style="margin: 20px auto; width: 960px;">
    <ul class="pagination justify-content-end">
        <?php
        $urlSearch = getUrl("management/search");
        if (isset($_GET['controller']) && $_GET['controller'] == 'user') $urlSearch = getUrl("user/search");
        echo "<li class='page-item'><a class='page-link' href=" . $urlSearch . getQuery() . "&page={$previous}>Previous</a></li>";
        for ($i = 1; $i <= $totalPage; $i++) {
            $active = "";
            if ($i == $page) {
                $active = 'active';
            }

            echo "<li class='page-item {$active}'><a class='page-link' href=" . $urlSearch . getQuery() . "&page={$i}>" . $i . "</a></li>";
        }
        echo "<li class='page-item'><a class='page-link' href=" . $urlSearch . getQuery() . "&page={$next}>Next</a></li>";
        ?>
    </ul>
</nav>