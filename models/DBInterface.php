<?php
interface DBInterface {
    function insert();
    function update();
    function delete();
    function findById($id);
}