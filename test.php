<?php

$to = "asahasrabuddhe@torinit.com";
$subject = "Test Mail";
         
$message = "<b>This is test message.</b>";
         
$header = "From:asahasrabuddhe@torinit.com \r\n";
//$header .= "Cc:afgh@somedomain.com \r\n";
$header .= "MIME-Version: 1.0\r\n";
$header .= "Content-type: text/html\r\n";

 $result = mail( $to, $subject, $message, $header );

 if( $result == true ) 
 {
 	echo "Message sent successfully...";
 }
 else
 {
 	echo "Message could not be sent...";
 }