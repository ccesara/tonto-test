<?php

// http check
$http_url="https://tonto-http.test.com?auth=xxx&buf=test";
$http_string="test";

// tcp check
$tcp_port='3000'; 
$tcp_host='tonto.test.com';
$tcp_auth="auth aaaaaaaaa\r\n";
$tcp_response='CLOUDWALK';

// RSS
$rss_title='Cloud Test';
$rss_description='Cloud Test';
$rss_url='https://yyy.com.br';
$rss_feedUrl='https://xxx.com.br';
$rss_language='en-US';
$rss_copyright='Copyright 2022, Cloud Test';
$rss_ttl=60;
$rss_item_title='Website and TCP checker';
$rss_item_description='<div>Status</div>';

// SMTP
$mail->Mailer = "smtp";
$mail->SMTPDebug  = 0;
$mail->SMTPAuth   = TRUE;
$mail->SMTPSecure = "tls";
$mail->Port       = 587;
$mail->Host       = "smtp.gmail.com";
$mail->Username   = "xxxxx";
$mail->Password   = "xxxxxx";
$mail->AddAddress = "xxxxx";
$mail->SetFrom = "xxxxxx";
$mail->Subject = "Cloud Test Alert";
$content = "<b> $http_url or $tcp_host is down <b>";

?>
