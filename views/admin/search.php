<?php
    require_once("views/layouts/header.php") ;
?>
    <title>Admin - Search</title>
    <link rel="stylesheet" href='public/css/search.css'>

<!--            Form Search-->
            <div id="form_search">
                <form method="GET" action="<?php base_url('index.php'); ?>">
                    <input type="" style="display: none" name="controller" value="admin">
                    <input type="" style="display: none" name="action" value="search">
                    <span class="input-space">
                        <label for="email">Email</label>
                        <input type="text" name="email" value="<?php if(isset($_GET['email'])) echo $_GET['email'] ?>" id="email" maxlength="50">
                     </span>
                    <span class="input-space">
                        <label for="name">Name</label>
                        <input type="text" name="name" value="<?php if(isset($_GET['name'])) echo $_GET['name'] ?>" id="name">
                     </span>
                    <div class="form-group d-flex mt-4" style="justify-content: space-between">
                        <button type="submit" class="btn btn-secondary" name="reset">Reset</button>
                        <button type="submit" class="btn btn-primary" name="search">Search</button>
                    </div>
                </form>
            </div>

<!--            Pagging-->
            <div id="pagging">
                <a href=""><< Press</a>
                <a href="">1</a>
                <a href="">2</a>
                <a href="">3</a>
                <a href="">4</a>
                <a href="">Next >></a>
            </div>

<!--            Data Table-->
            <div id="data_table">
                <?php
                    if(isset($_SESSION['alert']['update-success'])){echo '<p class="alert-success bg-green">'.$_SESSION['alert']['update-success'].'</p>';} else{ echo ""; }
                    if(isset($_SESSION['alert']['delete-success'])){echo '<p class="alert-success bg-green">'.$_SESSION['alert']['delete-success'].'</p>';} else{ echo ""; }
                    unset($_SESSION['alert']);
                ?>
                <table class="table table-striped table-hover table-condensed">
                    <tr>
                        <th>ID<a href="<?php echo URL_SEARCH_ADMIN . "&sortID=" . $sort; ?>"><i class="fas fa-sort"></i></a></th>
                        <th>Avatar</th>
                        <th>Name<a href="<?php echo URL_SEARCH_ADMIN . "&sortName=" . $sort; ?>"><i class="fas fa-sort"></i></a></th>
                        <th>Email<a href="<?php echo URL_SEARCH_ADMIN . "&sortEmail=" . $sort; ?>"><i class="fas fa-sort"></i></a></th>
                        <th>Role<a href="<?php echo URL_SEARCH_ADMIN . "&sortRole=" . $sort; ?>"><i class="fas fa-sort"></i></a></th>
                        <th>Action</th>
                    </tr>
                    <tr>
                        <?php
                            if(is_array($data)){
                                foreach ($data as $value){
                        ?>
                                    <td><?php echo $value['id'] ?></td>
                                    <td><img src="<?php echo UPLOADS.$value['avatar']; ?>"></td>
                                    <td><?php echo $value['name'] ?></td>
                                    <td><?php echo $value['email'] ?></td>
                                    <td><?php echo $value['role_type'] ?></td>
                                    <td>
                                        <span class="btn btn-success"><a href='<?php echo base_url("index.php?controller=admin&action=edit&id={$value['id']}"); ?>'>Edit</a></span>
                                        <span class="btn btn-danger"><a href='<?php echo base_url("index.php?controller=admin&action=delete&id={$value['id']}"); ?>' id ="delete" onclick="return confirm('Do you want to delete this record?')";>Delete</a></span>
                                    </td>
                    </tr>
                        <?php
                                }
                            }else{
                                echo "<td colspan='6' style='background: #e0e0e0'>$data</td>";
                            }
                        ?>
                </table>
            </div>
<script>

</script>
<?php
    require_once("views/layouts/footer.php") ;
?>