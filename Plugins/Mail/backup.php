<?php
$sub="Subject";
require_once("../../config/root.php");
if(isset($_POST['sub']))
{
  $to=$_POST['to'];
  $msg=$_POST['msg'];
  date_default_timezone_set('Asia/Kolkata');
  require_once($_SERVER['DOCUMENT_ROOT']."/".$root.'Mail\class.phpmailer.php');
  $mail=new PHPMailer();
  $body=$msg;
  $mail->IsSMTP();
  $mail->SMTPDebug=1;
  $mail->SMTPAuth=true;
  $mail->CharSet="UTF-8";
  $mail->SMTPSecure="tls";

  $mail->Host="smtp.gmail.com";
  $mail->Port=25;
  $mail->Username="virtualappstore@gmail.com";
  $mail->Password="#FF00FF00";
  //Reply to
  $mail->SetFrom('rahulr0047@gmail.com',"Virtual Desktop");
  $mail->Subject=$sub;
  $mail->MsgHTML($body);
  $address=$to;
  //to name
  $mail->AddAddress($address,"Virtual Desktop");
  if($mail->Send())
  {
    echo"Success";
  }
  else
  {
    echo "Error";
  }
}
?>
<html>
 <head>
 <body>
 <form method="post">
 <table>
 <tr>
 <td>To</td>
 <td>
 <input type="text" name="to" />
 </td>
 </tr>
 <tr>
 <td>Message</td>
 <td>
 <textarea name="msg"></textarea>
 </td>
 </tr>
 <tr>
 <td colspan="2">
 <input type="submit" name="sub" value="send" />
 </td>
 </tr>
 </table>
 </form>
 </body>
 </html>
