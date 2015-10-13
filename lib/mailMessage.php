<?php
//$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
//
// This function mails the text passed in to the people specified 
// it requires the person sending it to and a message 
// CONSTRAINTS:
//      $to must not be empty
//      $to must be an email format
//      $cc must be an email format if its not empty
//      $bcc must be an email format if its not empty
//      $from must not be empty
//      $subject must not be empty
//      $message must not be empty
//      $message must have a minium number of characters
//      $message must be a minuim lenght (just count the characters and spaces
//      
//      $from should be cleand of invalid html before being sent here but needs 
//            to allow < and >
//      $message should be cleand of invalid html before being sent here as you 
//            may want to allow html characters
//
// function returns a boolean value
function sendMail($to, $cc, $bcc, $from, $subject, $message){ 
    $MIN_MESSAGE_LENGTH=10;
    
    $debug = false;
    if ($debug){
            print "<p>Mailing ...";
        }
  
    $blnMail=false;
    
    $to = filter_var($to, FILTER_SANITIZE_EMAIL);
    $cc = filter_var($cc, FILTER_SANITIZE_EMAIL);
    $bcc = filter_var($bcc, FILTER_SANITIZE_EMAIL);
   
    $subject = htmlentities($subject,ENT_QUOTES,"UTF-8");
     
    // just checking to make sure the values passed in are reasonable
    if(empty($to)){
        if ($debug){
            print "<p>Failed To Check";
        }
        return false;
    }
    
    if(!filter_var($to, FILTER_VALIDATE_EMAIL)){
        if ($debug){
            print "<p>Failed To email check";
        }
        return false;
    }
    
    
    if($cc!="") if(!filter_var($cc, FILTER_VALIDATE_EMAIL)) {
        if ($debug){
            print "<p>Failed cc Check";
        }
        return false;
    }
    
    if($bcc!="") if(!filter_var($bcc, FILTER_VALIDATE_EMAIL)) {
        if ($debug){
            print "<p>Failed bcc Check";
        }
        return false;
    }
    
    
    if(empty($from)) {
        if ($debug){
            print "<p>Failed from Check";
        }
        return false;
    }
    
    
    if(empty($subject)) {
        if ($debug){
            print "<p>Failed subject Check";
        }
        return false;
    }
    
    
    if(empty($message)){
        if ($debug){
            print "<p>Failed message Check";
        }
        return false;
    }
    
    if (strlen($message)<$MIN_MESSAGE_LENGTH) {
        if ($debug){
            print "<p>Failed message length Check";
        }
        return false;
    }
    
    
    // message 
    $messageTop  = '<html><head><title>' . $subject . '</title></head><body>';
    $mailMessage = $messageTop . $message;
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: " . $from . "\r\n";
    if ($cc!="") $headers .= "CC: " . $cc . "\r\n";
    if ($bcc!="") $headers .= "Bcc: " . $bcc . "\r\n";
    // this line actually sends the email 
    $blnMail=mail($to, $subject, $mailMessage, $headers);

    if ($debug){
            print "<p>Mailing Complete";
        }
    return $blnMail;
}
?>