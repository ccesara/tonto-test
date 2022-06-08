<?php

include 'config.php';
require_once 'vendor/autoload.php';
use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Item;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// http check
function f_http(){
include 'config.php';

$ch = curl_init($http_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, 0);
$data = curl_exec($ch);
curl_close($ch);

$arr = explode(' ',trim($data));
$lw= array_pop($arr);

	if ( $lw == $http_string ) {
		return true;	

	} else {
		$http_error = $data;
		global $http_error;
		return false;
	}
}


// tcp check
function f_tcp(){
include 'config.php';

$address = gethostbyname($tcp_host);

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    echo "Fail -> " . socket_strerror(socket_last_error()) . "\n";
} else {

	$result = socket_connect($socket, $address, $tcp_port);
	if ($result === false) {
    		echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
	} else {

	$out = '';

	socket_write($socket, $tcp_auth, strlen($tcp_auth));

	$out = socket_read($socket, 2048);
	$arr = explode(' ',trim($out));

	if ( $arr[1] == "ok" ) {

		$in = "GET\r\n";
		$out = '';

		socket_write($socket, $in, strlen($in));
		$out = socket_read($socket, 2048);

		$arr2 = explode(' ',trim($out));
		if ( $arr2[0] == $tcp_response ) {

			return true;	

		} else {

			return false;

		}

		socket_close($socket);

	} else {

		return false;	

	}
	}
}
}



// feed
function f_feed($message){
include 'config.php';

// Load test target classes
spl_autoload_register(function ($c) {
    @include_once strtr($c, '\\_', '//') . '.php';
});
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__DIR__) . '/src');

$feed = new Feed();

$channel = new Channel();
$channel
    ->title($rss_title)
    ->description($rss_description)
    ->url($rss_url)
    ->feedUrl($rss_feedUrl)
    ->language($rss_language)
    ->copyright($rss_copyright)
    ->pubDate(strtotime(date("r")))
    ->lastBuildDate(strtotime('Tue, 21 Aug 2021 19:50:37 +0900'))
    ->ttl($rss_ttl)
    ->appendTo($feed);


$item = new Item();
$item
    ->title($rss_item_title)
    ->description($rss_item_description)
    ->contentEncoded("<div>$message</div>")
    ->pubDate(strtotime('Tue, 21 Aug 2021 19:50:37 +0900'))
    ->preferCdata(true) // By this, title and description become CDATA wrapped HTML.
    ->appendTo($channel);

echo $feed; // or echo $feed->render();
}


// email message
function f_mail(){

$mail = new PHPMailer();
$mail->IsSMTP();
include 'config.php';
$mail->IsHTML(true);
$mail->MsgHTML($content); 
if(!$mail->Send()) {
  var_dump($mail); // If fail 
}
}


if ((f_http() == true) && (f_tcp() == true )) {
	$o = f_feed("Online");	
} else {
	//$o = f_mail();
	$o = f_feed("Offline/Fail");
}



?>
