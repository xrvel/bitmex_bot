<?php
ob_start();
ignore_user_abort(true);
set_time_limit(0);

define('DIR', dirname(__FILE__));

require(DIR.'/lib.php');

// This is directory of ccxt-master
$root = DIR.'/../ccxt-master';
require_once($root . '/ccxt.php');

$exchange = new \ccxt\bitmex (array (
    'apiKey' => constant('MY_API_KEY'), // ←------------ replace with your keys
    'secret' => constant('MY_API_SECRET'),
    'enableRateLimit' => true,
));

if (isset($_GET['cancel_open'])) {
    my_cancel(my_get(array(
		'only_ids' => true,
		'only_open' => true
	)));
	file_put_contents(constant('MY_ORDER_ID_FILE'), '');
}

echo 'Done';
?>