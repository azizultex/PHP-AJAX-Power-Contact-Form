<?php

if($_POST){
	
	//var_dump($_POST);
	
    $to_email = "azizultex@gmail.com"; //Recipient email, Replace with own email here
    $subject = "Message from Contact form SPI";
    //check if its an ajax request, exit if not
     if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') { 
        
        $output = json_encode(array( //create JSON data
            'type'=>'error', 
            'text' => 'Sorry Request must be Ajax POST'
        ));
        die($output); //exit script outputting json data
    }
    
    //Sanitize input data using PHP filter_var().
    $fullname      = filter_var($_POST["fullname"], FILTER_SANITIZE_STRING);
    $company     = filter_var($_POST["company"], FILTER_SANITIZE_STRING);
    $phone   = filter_var($_POST["phone"], FILTER_SANITIZE_NUMBER_INT);
    $email   = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $interest   = filter_var($_POST["interest"], FILTER_SANITIZE_STRING);
    
    //additional php validation
    if(strlen($fullname)< 4){ // If length is less than 4 it will output JSON error.
        $output = json_encode(array('type'=>'error', 'text' => 'Name is too short or empty!'));
        die($output);
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ //email validation
        $output = json_encode(array('type'=>'error', 'text' => 'Please enter a valid email!'));
        die($output);
    }
    if(!filter_var($phone, FILTER_SANITIZE_NUMBER_FLOAT)){ //check for valid numbers in phone number field
        $output = json_encode(array('type'=>'error', 'text' => 'Enter only digits in phone number'));
        die($output);
    }
    
    //email body
	$message_body = "Howdy, you are requested to contact below person from SPI contact form. \r\n\r\n";
    $message_body .= "Full Name: ".$fullname."\r\nCompany:".$company."\r\nEmail: ".$email."\r\nInterest : ".$interest."\r\nPhone Number: ".$phone ;
    
    //proceed with PHP email.
    $headers = 'From: '.$fullname.'' . "\r\n" .
    'Reply-To: '.$email.'' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
    
    $send_mail = mail($to_email, $subject, $message_body, $headers);
    
    if(!$send_mail)
    {
        //If mail couldn't be sent output error. Check your PHP email configuration (if it ever happens)
        $output = json_encode(array('type'=>'error', 'text' => 'Could not send mail! Please check your PHP mail configuration.'));
        die($output);
    }else{
        $output = json_encode(array('type'=>'message', 'text' => 'Hi '.$fullname .' Thank you for your email'));
        die($output);
    }
}