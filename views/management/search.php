<?php
    require_once("views/layouts/header.php") ;
?>
    <title>Admin - Search</title>
    <link rel="stylesheet" href='public/css/search.css'>

            <?php if(isset($data['fill_out'])){echo '<p class="error">'.$data["fill_out"].'</p>';} else{ echo ""; } ?>

<!--            Form Search-->
            <div id="form_search">
                <form method="GET" action="<?php base_url('index.php'); ?>">
                    <input type="" style="display: none" name="controller" value="management">
                    <input type="" style="display: none" name="action" value="search">
                    <span class="input-space">
                        <label for="email">Email</label>
                        <input type="email" name="email" value="<?php if(isset($_GET['email'])) echo $_GET['email'] ?>" id="email" maxlength="50">
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
                <table border="1">
                    <tr>
                        <th>ID<a href=""><i class="fas fa-sort"></i></a></th>
                        <th>Avatar</th>
                        <th>Name<a href=""><i class="fas fa-sort"></i></a></th>
                        <th>Email<a href=""><i class="fas fa-sort"></i></a></th>
                        <th>Role<a href=""><i class="fas fa-sort"></i></a></th>
                        <th>Action<a href=""><i class="fas fa-sort"></i></a></th>
                    </tr>
                    <tr>
                        <?php
                            if(isset($_GET['search'])){
                                if(!empty($_GET['email']) || !empty($_GET['name'])){
                                    if(is_array($data)){
                                        foreach ($data as $value){
                        ?>
                                            <td><?php echo $value['id'] ?></td>
                                            <td><?php echo $value['avatar'] ?></td>
                                            <td><?php echo $value['name'] ?></td>
                                            <td><?php echo $value['email'] ?></td>
                                            <td><?php echo $value['role_type'] ?></td>
                                            <td>
                                                <span id='edit'><a href=''>Edit</a></span>
                                                <span id='delete'><a href=''>Delete</a></span>
                                            </td>
                    </tr>
                        <?php
                                        }
                                    }else{
                                        echo "<td colspan='6'>$data</td>";
                                    }
                                }
                            }else{
                                echo "<td colspan='6'></td>";
                            }
                        ?>
                </table>
            </div>
<?php
    require_once("views/layouts/footer.php") ;
?>