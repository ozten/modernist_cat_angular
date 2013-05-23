<?
require_once('Mail.php');
require_once('./config.php');

function emailSale($to, $name, $body) {
    $from = "Sales <austin@carkeek.co>";
    $subject = "New Sale! $name $email";

    $host = "smtp.socketlabs.com";
    $port = "25";
    $username = "server7853";
    $password = "i8QBr75YgMq4n6";

    $headers = array ('From' => $from,
          'To' => $to,
          'Subject' => $subject);

    $smtp = Mail::factory('smtp',
          array ('host' => $host,
            'port' => $port,
            'auth' => true,
            'username' => $username,
            'password' => $password));

    $mail = $smtp->send($to, $headers, $body);

    if (PEAR::isError($mail)) {
	return TRUE;
    } else {
      	return FALSE;
    }
}
?>