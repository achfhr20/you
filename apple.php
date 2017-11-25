<?php
error_reporting(0);
function get(){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://appleid.apple.com/account");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
  curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

  $headers = array();
  $headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";
  $headers[] = "Connection: keep-alive";
  $headers[] = "Accept-Encoding: gzip, deflate, sdch, br";
  $headers[] = "Accept-Language: en-US,en;q=0.8,id;q=0.6,fr;q=0.4";
  $headers[] = "Upgrade-Insecure-Requests: 1";
  $headers[] = "User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36";
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $result = curl_exec($ch);
  curl_close($ch);
  return $result;
}
function getStr($string,$start,$end){
  $str = explode($start,$string);
  $str = explode($end,$str[1]);
  return $str[0];
}
function inStr($s,$as){
  $s = strtoupper($s);
  if(!is_array($as)) $as = array($as);
  for($i = 0; $i<count($as); $i++)
  if(strpos(($s),strtoupper($as[$i])) !== false)
  return true;
  return false;
}
function check($email){
  $page = get();
  $Scnt = getStr($page, "scnt: '","'");
  $apiKey = getStr($page, "apiKey: '","'");
  $sessionId = getStr($page, "sessionId: '","'");

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://appleid.apple.com/account/validation/appleid");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"emailAddress\":\"$email\"}");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

  $headers = array();
  $headers[] = "Scnt: ".$Scnt;
  $headers[] = "Accept-Encoding: gzip, deflate, br";
  $headers[] = "X-Apple-I-Fd-Client-Info: {\"U\":\"Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36\",\"L\":\"en-US\",\"Z\":\"GMT+08:00\",\"V\":\"1.1\",\"F\":\"F8a44j1e3NlY5BSo9z4ofjb75PaK4Vpjt4U_98uszHVyVxFAk.lzXJJIneGffLMC7EZ3QHPBirTYKUowRslzRQqwSM2YSQTPNKSgydUPm8LKfAaZ4pAJZ7OQuyPBB2SCXw2SCWRUdFUFTc4s.QuyPB94UXuGlfUm9z9JIply_0x0uVMV0Yz3ccbbJYMLgiPFU77qZoOSix5ezdstlYysrhsui65uqwokevOxHypZHgfLMC7Awvw0BpUMnGWmccbhdqTK43xbJlpMpwoNSUC56MnGWpwoNHHACVZXnN9NW2quaud01lpi.uJtHoqvynx9MsFyxYM914Ygh5DsTpw.Tf5.EKXJtJdmX3ivojkxbsJz3YMJ5tI.KUfpKSELtTclY5BSp.5BNlan0Os5Apw.C7U\"}";
  $headers[] = "Accept-Language: en-US,en;q=0.8,id;q=0.6,fr;q=0.4";
  $headers[] = "X-Requested-With: XMLHttpRequest";
  $headers[] = "Cookie: aid=".$sessionId."; ccl=KFbl40Od3yW1Xe5+mG394w==; geo=ID; idclient=web; dslang=US-EN; site=USA";
  $headers[] = "Connection: keep-alive";
  $headers[] = "X-Apple-Api-Key: ".$apiKey;
  $headers[] = "X-Apple-ID-Session-Id: ".$sessionId;
  $headers[] = "User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36";
  $headers[] = "Content-Type: application/json";
  $headers[] = "Accept: application/json, text/javascript, */*; q=0.01";
  $headers[] = "Referer: https://appleid.apple.com/account";
  $headers[] = "X-Apple-Request-Context: create";
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $result = curl_exec($ch);
  curl_close($ch);
  return $result;
}
if(isset($_GET['email'])){
  $email = $_GET['email'];
  $filter = filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $email);

  if(!$filter){
    $error = 2;
    $valid = 'invalid';
  } else {
    $error = 0;
    $page = check($email);
    if(inStr($page, 'used" : false')){
      $valid = 'invalid';
    } else {
      $valid = 'valid';
    }
  }

  $check->error   = $error;
  $check->email   = $email;
  $check->msg     = $valid;
  $result = json_encode($check, JSON_PRETTY_PRINT);
  header('Content-Type: application/json');
  echo $result;
} else {
  $check->error   = 2;
  $check->email   = 'Not Found';
  $check->msg     = 'email parameter not found!';
  $result = json_encode($check, JSON_PRETTY_PRINT);
  header('Content-Type: application/json');
  echo $result;
}
?>
