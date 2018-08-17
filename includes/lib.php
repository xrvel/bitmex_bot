<?php
if (!defined('MY_POSITION_ORDER_ID_FILE')) {
	define('MY_POSITION_ORDER_ID_FILE', dirname(__FILE__).'/order_id/position.txt');
}

date_default_timezone_set('UTC');

function my_cancel($order_ids) {
	if (!is_array($order_ids)) {
		$order_ids = array($order_ids);
	}

	global $exchange;

	foreach ($order_ids as $order_id) {
		$order_id = trim($order_id);
		if ('' != $order_id) {
			$exchange->cancel_order($order_id);
		}
	}

	return true;
}

function my_close_all_positions() {
	global $exchange;

	$order_id = trim(file_get_contents(MY_POSITION_ORDER_ID_FILE));

	if ('' != $order_id) {
		$order = $exchange->fetch_order($order_id);

		if ('open' != $order['status']) {// Only counter trade if order is closed / partial filled
			$side = my_counter_side($order['side']);
			$type = 'Stop';

			if ('sell' == $side) {
				$stoploss = 1000000;
			} else {
				$stoploss = 100;
			}

			$params = array (
				'stopPx' => $stoploss,
			);

			$exchange->create_order ('BTC/USD', $type, $side, $order['amount'], null, $params);
		}
	}

	my_cancel(my_get(array(
		'only_ids' => true,
		'only_open' => true
	)));

	file_put_contents(MY_POSITION_ORDER_ID_FILE, '');

	return true;
}

function my_counter_side($side) {
	if ('buy' == $side) {
		return 'sell';
	}
	return 'buy';
}

function my_get($params = array()) {
	global $exchange;

	if (!isset($params['only_open'])) {
		$params['only_open'] = true;
	}

	if (!isset($params['only_ids'])) {
		$params['only_ids'] = true;
	}

	if ($params['only_open']) {
		$orders = $exchange->fetch_open_orders();
	} else {
		$orders = $exchange->fetch_orders();
	}

	if ($params['only_ids']) {
		$temp = array();
		foreach ($orders as $order) {
			if ('open' == $order['status']) {
				$temp[] = $order['id'];
			}
		}
		ksort($temp);
		return $temp;
	}

	return $orders;
}

function my_long($price, $amount, $stoploss = false, $take_profit = false) {
	global $exchange;

	$symbol = 'BTC/USD';
	$side = 'buy';
	$type = 'Limit';

	// extra params and overrides
	$params = array (
	);

	$temp = $exchange->create_order ($symbol, $type, $side, $amount, $price, $params);
	if (isset($temp['id'])) {
		file_put_contents(MY_POSITION_ORDER_ID_FILE, $temp['id']);
	}

	if (false != $stoploss) {
		$params = array (
			'stopPx' => $stoploss,
		);

		$exchange->create_order ($symbol, 'Stop', my_counter_side($side), $amount, null, $params);
	}

	return true;
}

function my_short($price, $amount, $stoploss = false) {
	global $exchange;

	$symbol = 'BTC/USD';
	$side = 'sell';
	$type = 'Limit';

	// extra params and overrides
	$params = array (
	);

	$temp = $exchange->create_order ($symbol, $type, $side, $amount, $price, $params);
	if (isset($temp['id'])) {
		file_put_contents(MY_POSITION_ORDER_ID_FILE, $temp['id']);
	}

	if (false != $stoploss) {
		$params = array (
			'stopPx' => $stoploss,
		);

		$exchange->create_order ($symbol, 'Stop', my_counter_side($side), $amount, null, $params);
	}

	return true;
}


?>