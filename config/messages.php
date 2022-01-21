<?php

//=====MESSAGES SUCCESS - ERROR=====
define('INSERT_SUCCESSFUL', 'Create data successful!');
define('UPDATE_SUCCESSFUL', 'Update data successful!');
define('DELETE_SUCCESSFUL', 'Delete data successful!');
define('LOGIN_FB_SUCCESSFUL', 'Login via Facebook successful!');

//=====MESSAGES ERROR EMPTY=====
define('ERROR_EMPTY_AVATAR', 'Avatar is not blank!');
define('ERROR_EMPTY_EMAIL', 'Email is not blank!');
define('ERROR_EMPTY_NAME', 'Name is not blank!');
define('ERROR_EMPTY_PASSWORD', 'Password is not blank!');
define('ERROR_EMPTY_CONFIRM_PASSWORD', 'Confirm password is not blank!');

//=====MESSAGES ERROR VALIDATE=====
define('ERROR_VALID_AVATAR', 'Avatar invalidate!');
define('ERROR_VALID_EMAIL', 'Email invalidate!');
define('ERROR_VALID_NAME', 'Name invalidate!');
define('ERROR_VALID_PASSWORD', 'Password invalidate!');

define('ERROR_LENGTH_NAME', 'Name with a length of '. MINIMUM_LENGTH_NAME .'-' . MAXIMUM_LENGTH_NAME . ' characters');
define('ERROR_LENGTH_EMAIL', 'Email with a length of '. MINIMUM_LENGTH_EMAIL .'-' . MAXIMUM_LENGTH_EMAIL . ' characters');
define('ERROR_LENGTH_PASSWORD', 'Password with a length of '. MINIMUM_LENGTH_PASSWORD .'-' . MAXIMUM_LENGTH_PASSWORD . ' characters');

//=====MESSAGES ERROR LOGIN=====
define('ERROR_LOGIN', 'Login information is incorrect !');

//=====MESSAGES ERROR SEARCH=====
define('NO_EXISTS_USER', 'No exists user');

//=====MESSAGES CHECK EXISTS=====
define('ERROR_EMAIL_EXISTS', 'Email already exists');

//=====MESSAGES CHECK PASSWORD=====
define('ERROR_CONFIRM_PASSWORD', 'Confirm password is incorrect');

//=====MESSAGES IMAGE=====
define('ERROR_IMAGE_INVALID', 'Invalid file image(.jpg, .jpeg, .gif, .png)');
define('ERROR_IMAGE_MAX_SIZE', 'The maximum size file is 20MB');