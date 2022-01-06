<?php

//=====DB CONNECT=====
define('LOCALHOST', 'localhost');
define('DBNAME', 'basephp');
define('USERNAME', 'root');
define('PASSWORD', '');

define('ACTIVED', 0);
define('DELETED', 1);
define('ROLE_TYPE_ADMIN', 1);
define('ROLE_TYPE_SUPERADMIN', 2);
define('DEL_FLAG', 0);
define('DEL_FLAG_DELETE', 1);

//=====PAGGING=====
define("RECORD_PER_PAGE", 5);

//=====BASE URL====

//=====URL UPLOAD=====
define('UPLOADS_ADMIN', 'public/uploads/admin/');
define('UPLOADS_USER', 'public/uploads/user/');

//=====URL ADMIN=====
define('URL_SEARCH_ADMIN', 'index.php?controller=admin&action=search');
define('URL_CREATE_ADMIN', 'index.php?controller=admin&action=create');
define('URL_EDIT_ADMIN', 'index.php?controller=admin&action=edit');
define('URL_DELETE_ADMIN', 'index.php?controller=admin&action=delete');
define('URL_LOGIN_ADMIN', 'index.php?controller=admin&action=login');
define('URL_LOGOUT_ADMIN', 'index.php?controller=admin&action=logout');

//=====URL USER=====
define('URL_LOGIN_USER', 'index.php?controller=user&action=login');
define('URL_LOGOUT_USER', 'index.php?controller=user&action=logout');
define('URL_DETAIL_USER', 'index.php?controller=user&action=detail');
define('URL_SEARCH_USER', 'index.php?controller=user&action=search');
define('URL_EDIT_USER', 'index.php?controller=user&action=edit');
define('URL_DELETE_USER', 'index.php?controller=user&action=delete');