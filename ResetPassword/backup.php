<?php
$sub="Subject";
require_once("../config/root.php");
?>
<html>
 <head>
   <style>
    *
    {
      padding:0;
      margin:0;
    }
    body
    {
      background:linear-gradient(#0080C0,#FFFFFF,#FFFFFF,#FFFFFF);
    }
    div.img
    {
       width:100vw;
       height:100vh;
       position:absolute;
       top:0%;
       left:0%;
       background-color:transparent;
    }
    div.img1
    {
       background:url("1.jpg");
       background-size:30%;
       background-position:right bottom;
       background-repeat:no-repeat;
    }
    div.img2
    {
       background:url("2.jpg");
       background-size:30%;
       background-position:left bottom;
       background-repeat:no-repeat;
    }
    form.resetForm
    {
      background-color:#FFFFFF;
      color:#000000;
      display:inline-block;
      position:absolute;
      top:50%;
      left:50%;
      transform:translate(-50%,-50%) rotate(2deg);
      min-width:500px;
      z-index:100;
      border:1px solid #000000;
    }
    form.resetForm::before,form.resetForm::after
    {
      content:"";
      height:100%;
      width:100%;
      background-color:#FFFFFF;
      border:1px solid #000000;
      z-index:-1;
    }
    form.resetForm::before
    {
      position:absolute;
      top:-5px;
      left:-5px;
    }
    form.resetForm::after
    {
      position:absolute;
      bottom:-5px;
      right:-5px;
    }
    form.resetForm table
    {
      width:100%;
      color:#000000;
      background-color:#FFFFFF;
      border:1px solid #000000;
      //padding:10px;
      transform:rotate(-2deg);
    }
    input,textarea
    {
      width:100%;
      color:#000050;
    }
    td,input[type="submit"]
    {
      padding:10px;
    }
    input[type="email"]
    {
      min-width:300px;
      padding:5px;
      background-color:#C0C0FF;
    }
    input[type="submit"]
    {
      background-color:#5050FF;
      border:1px solid #C0C0C0;
      color:#FFFFFF;
      font-family:arial black,arial,sans-serif;
      font-weight:900;
      font-size:20px;
      transition:0.5s background-color;
    }
    input[type="submit"]:hover
    {
      background-color:#50FF50;
      color:#000000;
    }
   </style>
 </head>
 <body>
   <div class="img img1"></div>
   <div class="img img2"></div>
 <form class="resetForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
 <table>
   <?php
   if(!isset($_POST['sub']))
   {
   ?>
 <tr>
 <td>E - Mail</td>
 <td>:</td>
 <td><input type="email" name="to" placeholder="E-Mail" autocomplete="off" required/></td>
 </tr>
 <tr>
 <td colspan="3"><input type="submit" name="sub" value="Generate OTP"/></td>
 </tr>
 <?php
 }
 else
 {
   $to=$_POST['to'];
   date_default_timezone_set('Asia/Kolkata');
   require_once($_SERVER['DOCUMENT_ROOT']."/".$root.'Plugins\Mail\class.phpmailer.php');
   $mail=new PHPMailer();
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
   $body="Reset Password";
   $mail->MsgHTML($body);
   $address=$to;
   //to name
   $mail->AddAddress($address,"Virtual Desktop");
   if($mail->Send())
   {
     echo"Success";
     ?>
     <tr>
     <td>New Password</td>
     <td>:</td>
     <td><input type="password" value="" placeholder="New Password"/></td>
     </tr>
     <tr>
     <td>Confirm Password</td>
     <td>:</td>
     <td><input type="password" value="" placeholder="Confirm Password"/></td>
     </tr>
     <?php
   }
   else
   {
     echo "Error";
   }
 }
 ?>
 </table>
 </form>
 </body>
 </html>
