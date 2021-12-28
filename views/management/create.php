<?php
    require_once("views/layouts/header.php") ;
?>

<title>Admin - Create</title>
<link rel="stylesheet" href='public/css/create.css'>


<div id="wrapper-create">
    <h4>Admin - Create</h4>
    <form method="POST" action="">
        <div id="wrapper-create-sub">
            <div id="wrapper-create-form">
                <div class="form-group row">
                    <label for="avatar" class="col-sm-2 col-form-label">Avatar*</label>
                    <label class="file-upload"><input type="file"/>File Upload</label>
                </div>

                <div class="form-group row">
                    <label for="avatar" class="col-sm-2 col-form-label"></label>
                    <img src="#">
                </div>

                <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Name*</label>
                    <input type="text" class="form-control" id="name">
                </div>

                <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label">Email:</label>
                    <input type="email" class="form-control" id="email">
                </div>

                <div class="form-group row">
                    <label for="password" class="col-sm-2 col-form-label">Password*</label>
                    <input type="password" class="form-control" id="password">
                </div>

                <div class="form-group row">
                    <label for="confirm-password" class="col-sm-2 col-form-label">Password Verify*</label>
                    <input type="confirm-password" class="form-control" id="confirm-password">
                </div>

                <div class="form-group row">
                    <label for="role" class="col-sm-2 col-form-label">Role*</label>
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="optradio">Super Admin
                        </label>
                    </div>
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="optradio">Admin
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group d-flex mt-4" style="justify-content: space-between">
            <button type="submit" class="btn btn-secondary">Reset</button>
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>
</div>

<?php
    require_once("views/layouts/footer.php") ;
?>