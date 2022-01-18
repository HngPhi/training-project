<?php
    getHeader();
?>
    <title>Admin - Search</title>
    <link rel="stylesheet" href='<?php echo getUrl("public/css/search.css") ?>'>
    <link rel="stylesheet" href='<?php echo getUrl("public/css/pagging.css") ?>'>

<!--            Form Search-->
            <div id="form_search">
                <form method="GET" action="<?php echo getUrl("management/search"); ?>">
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
                <?php
                    require_once "views/layouts/pagging.php";
                ?>
<!--            Data Table-->
            <div id="data_table">
                <?php
                    if(isset($_SESSION['alert']['update-success'])){echo '<p class="alert-success bg-green">'.$_SESSION['alert']['update-success'].'</p>';} else{ echo ""; }
                    if(isset($_SESSION['alert']['delete-success'])){echo '<p class="alert-success bg-green">'.$_SESSION['alert']['delete-success'].'</p>';} else{ echo ""; }
                    unset($_SESSION['alert']);
                ?>
                <table class="table table-striped table-hover table-condensed">
                    <tr>
                        <th>ID<a href="<?php echo getUrl("management/search") . $addUrlSearch . '&column=id&sort=' . $sort . "&page=" . $page; ?>"><i class="fas fa-sort"></i></a></th>
                        <th>Avatar</th>
                        <th>Name<a href="<?php echo getUrl("management/search") . $addUrlSearch  . "&column=name&sort=" . $sort . "&page=" . $page; ?>"><i class="fas fa-sort"></i></a></th>
                        <th>Email<a href="<?php echo getUrl("management/search") . $addUrlSearch  . "&column=email&sort=" . $sort . "&page=" . $page; ?>"><i class="fas fa-sort"></i></a></th>
                        <th>Role<a href="<?php echo getUrl("management/search") . $addUrlSearch . "&column=role_type&sort=" . $sort . "&page=" . $page; ?>"><i class="fas fa-sort"></i></a></th>
                        <th>Action</th>
                    </tr>
                    <tr>
                        <?php
                            if(is_array($data)){
                                foreach ($data as $value){
                        ?>
                                    <td><?php echo $value['id'] ?></td>
                                    <td><img src="<?php echo getUrl(UPLOADS_ADMIN) . $value['avatar']; ?>"></td>
                                    <td><?php echo $value['name'] ?></td>
                                    <td><?php echo $value['email'] ?></td>
                                    <td><?php echo $value['role_type'] ?></td>
                                    <td>
                                        <span class="btn btn-danger"><a href='<?php echo getUrl("management/edit/").$value['id']; ?>'>Edit</a></span>
                                        <span class="btn btn-success"><a href='<?php echo getUrl("management/delete/").$value['id']; ?>' onclick="return confirm('Do you want to delete this record?')";>Delete</a></span>
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
<?php
    getFooter();
?>