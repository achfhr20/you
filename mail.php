<?php
$msg      = "test";
$subject  = "This is test email";
$headers  = "From: Test <test@yourdomain.com>";
$sending  = mail("achfhr33@gmail.com", $subject, $msg, $headers);
if (!$sending)
{
  echo "Failed";
}
else
{
  echo "Success";
}
?>
