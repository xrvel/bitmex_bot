<?php
if (!defined('DIR')) {
	echo 'Forbidden';
	exit();
}

if (!defined('MY_ORDER_QUANTITY')) {
	define('MY_ORDER_QUANTITY', 1);
}

if (!defined('MY_STOP_LOSS_PERC')) {
	define('MY_STOP_LOSS_PERC', 1);
}

// Set to 0 to disable take profit
// and close only if new counter signal is made
if (!defined('MY_TAKE_PROFIT_PERC')) {
	define('MY_TAKE_PROFIT_PERC', 50);
}
?>