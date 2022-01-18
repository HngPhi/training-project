<?php
    session_destroy();
    header("Location: ".getUrl("management/login"));
?>