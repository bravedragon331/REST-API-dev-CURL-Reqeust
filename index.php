<?php
session_start();
$_SESSION['token'] = false;

ini_set("display_errors","on");

require_once('./Config/route.config.php');
require_once('./Config/output_json.php');

require_once('./Middleware/users.php');
require_once('./Middleware/groups.php');
require_once('./Middleware/accesscontrol.php');
require_once('./Middleware/logins.php');
require_once('./Middleware/labels.php');

require_once('./Logic/Auth.php');
require_once('./Logic/AbstractMiddleware.php');
require_once('./Logic/structure.php');
require_once('./Logic/groups.php');
require_once('./Logic/logins.php');
require_once('./Logic/usergroups.php');
require_once('./Logic/accesscontrol.php');
require_once('./Logic/labels.php');
require_once('./Logic/passwords.php');

require_once('./Model/structure.db.php');
require_once('./Model/groups.db.php');
require_once('./Model/logins.db.php');
require_once('./Model/hashes.db.php');
require_once('./Model/passwords.db.php');

require_once('./Route/route.php');
?>