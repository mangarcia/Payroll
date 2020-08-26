<?php

/**
 * Description of EmailUtils
 * @author Holmes Dennys Mojica Montero
 */
class EmailUtils 
{
    const INFO_RAIZ_MAIL_ADDRESS = "inforaizcolombia@gmail.com";
//    const INFO_RAIZ_MAIL_ADDRESS = "navarroyanda@gmail.com";
    
    /**
     * Sends a EMail 
     * @param string $to : Email recipient
     * @param String $title : Title of the mail
     * @param string $message : The Email Message
     * @param bool   $autoBcc : If the mail has an BCC to InfoRaiz Manager
     * @return bool : True if the EMail is accepted, false in other wise
     */
    public static function sendMail ($to, $title, $message, $autoBcc)
    {
        require_once BASE_UTILS . "phpmailer/class.phpmailer.php";
        
        $emailMessage = wordwrap($message, 70, "\r\n");
        
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPDebug = 0;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;
        $mail->Username = self::INFO_RAIZ_MAIL_ADDRESS;
        $mail->Password = "jaimico12";
        $mail->From = self::INFO_RAIZ_MAIL_ADDRESS;
        $mail->SetFrom(self::INFO_RAIZ_MAIL_ADDRESS);
        $mail->FromName = utf8_decode(APP_NAME);
        $mail->Subject = utf8_decode($title);
        $mail->AddAddress($to);
        
        if ($autoBcc)
        {
            $mail->AddBCC(self::INFO_RAIZ_MAIL_ADDRESS);
        }
        
        $mail->MsgHTML(utf8_decode($message));
        
        $result = $mail->Send();
       
        return $result;
    }   
   
}
