<?php
header("Access-Control-Allow-Origin: *");

    // Only process POST reqeusts.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form fields and remove whitespace.
        $name = strip_tags(trim($_POST["name"]));
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $sub = trim($_POST["subject"]);
        $message = trim($_POST["message"]);

        // Check that data was sent to the mailer.
        if ( empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Set a 400 (bad request) response code and exit.
            http_response_code(400);
            echo "Please complete the form and try again.";
            exit;
        }

        // Set the recipient email address.
        $recipient = "rajuofficialemail@gmail.com";

        // Set the email subject.
        $subject = "Gparts - ".$sub;

        // Build the email content.
        $email_content = 
        '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
        <html lang="en">
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
            
                <title>Contact Mail</title>
            
                <!--[if (mso)|(mso 16)]>
                    <style type="text/css">
                    body, table, td, a, span { font-family: Arial, sans-serif !important; }
                    a {text-decoration: none !important;}
                    </style>
                <![endif]-->
                <style type="text/css">
                    /* CLIENT-SPECIFIC STYLES */
            
                    body,
                    table,
                    td,
                    a {
                        -webkit-text-size-adjust: 100%;
                        -ms-text-size-adjust: 100%;
                    }
            
                    table,
                    td {
                        mso-table-lspace: 0pt;
                        mso-table-rspace: 0pt;
                    }
            
                    img {
                        -ms-interpolation-mode: bicubic;
                    }
            
                    /* RESET STYLES */
            
                    img {
                        border: 0;
                        outline: none;
                        text-decoration: none;
                    }
            
                    table {
                        border-collapse: collapse !important;
                    }
            
                    body {
                        color: #626160!important;
                        font-family: Tahoma, Arial, sans-serif !important;
                        font-size: 14px;
                        margin: 0 !important;
                        padding: 0 !important;
                        width: 100% !important;
                    }
            
                    h1,
                    h2,
                    h3,
                    h4,
                    h5,
                    h6,
                    p,
                    img {
                        margin: 0;
                        padding: 0;
                    }
            
                    /* iOS BLUE LINKS */
            
                    a[x-apple-data-detectors] {
                        color: inherit !important;
                        text-decoration: none !important;
                        font-size: inherit !important;
                        font-family: inherit !important;
                        font-weight: inherit !important;
                        line-height: inherit !important;
                    }
            
                    /* ANDROID CENTER FIX */
            
                    div[style*="margin: 16px 0;"] {
                        margin: 0 !important;
                    }
            
                    /* MEDIA QUERIES */
            
                    @media all and (max-width:639px) {
                        .wrapper {
                            width: 320px!important;
                            padding: 0 !important;
                        }
                        .container {
                            width: 300px!important;
                            padding: 0 !important;
                        }
                        .mobile {
                            width: 300px!important;
                            display: block!important;
                            padding: 0 !important;
                        }
                        .img {
                            width: 100% !important;
                            height: auto !important;
                        }
                        *[class="mobileOff"] {
                            width: 0px !important;
                            display: none !important;
                        }
                        *[class*="mobileOn"] {
                            display: block !important;
                            max-height: none !important;
                        }
                    }
            
            
                    /*===========================
                        Author Custom Style
                    ============================*/
                    hr{
                        border-color: #fefefe;
                        border-width: 1px;
                        margin-bottom: 10px;
                    }
            
                    .message-content{
                        font-size: 16px;
                        line-height: 1.6;
                    }
            
                    .content-wrapper h2{
                        font-weight: 400;
                        font-size: 20px;
                    }
            
                    .email-txt a{
                        color: #222222 !important;
                        text-decoration: none !important;
                    }
            
                </style>
            </head>
        
            <body style="margin:0; padding:0; background-color:#F3F3F3;">
                <center>
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F2F2F2">
                        <tr>
                            <td height="100" style="font-size:10px; line-height:10px;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td align="center" valign="top">
                                <!--== TemplateContainer Start ==-->
                                <table width="640" cellpadding="0" cellspacing="0" border="0" class="wrapper" bgcolor="#FFFFFF">
                                    <tr>
                                        <td height="60" style="font-size:10px; line-height:10px;">&nbsp;</td>
                                    </tr>
                
                                    <!--== Content Area Start ==-->
                                    <tr class="content-wrapper">
                                        <td align="center" valign="top">
                                            <table width="560" cellpadding="0" cellspacing="0" border="0" class="container">

                                                <!--== Start Name Field Item ==-->
                                                <tr>
                                                    <td width="100%" class="mobile" align="left" valign="middle">
                                                        <h3>Name:</h3>
                                                        <hr>
                                                        <h2>'. $name .'</h2>
                                                    </td>
                                                </tr>
                                                <!--== End Name Field Item ==-->
                                                
                                                <!--== Start Separator ==-->
                                                <tr>
                                                    <td height="30" style="font-size:10px; line-height:10px;">&nbsp;</td>
                                                </tr>
                                                <!--== End Separator ==-->
                    
                                                <!--== Start Email Field Item ==-->
                                                <tr>
                                                    <td width="100%" class="mobile" align="left" valign="middle">
                                                        <h3>Email:</h3>
                                                        <hr>
                                                        <h2 class="email-txt">'. $email .'</h2>
                                                    </td>
                                                </tr>
                                                <!--== Start Email Field Item ==-->
                                                
                                                <!--== Start Separator ==-->
                                                <tr>
                                                    <td height="30" style="font-size:10px; line-height:10px;">&nbsp;</td>
                                                </tr>
                                                <!--== End Separator ==-->
                                                
                                                <!--== Start Subject Field Item ==-->
                                                <tr>
                                                    <td width="100%" class="mobile" align="left" valign="middle">
                                                        <h3>Subject:</h3>
                                                        <hr>
                                                        <h2>'. $subject .'</h2>
                                                    </td>
                                                </tr>
                                                <!--== End Subject Field Item ==-->
                
                                                <!--== Start Separator ==-->
                                                <tr>
                                                    <td height="30" style="font-size:10px; line-height:10px;">&nbsp;</td>
                                                </tr>
                                                <!--== End Separator ==-->
                                                
                                                 <!--== Start Message Field Item ==-->
                                                <tr>
                                                    <td width="100%" class="mobile" align="left" valign="middle">
                                                        <h3>Message:</h3>
                                                        <hr>
                                                        <p class="message-content">'. $message .'</p>
                                                    </td>
                                                </tr>
                                                <!--== End Message Field Item ==-->
                                            </table>
                                        </td>
                                     </tr>
                                    <!--== Content Area End ==-->
                                    
                                    <tr>
                                        <td height="60" style="font-size:10px; line-height:10px;">&nbsp;</td>
                                    </tr>
                                </table>
                                <!--== TemplateContainer End ==-->
                            </td>
                        </tr>

                        <tr>
                           <td height="20" style="font-size:10px; line-height:10px;">&nbsp;</td>
                        </tr>

                        <tr>
                            <td align="center" valign="top">
                                <a href="https://hasthemes.com/" target="_blank"><img width="120" src="https://hasthemes.com/wp-content/uploads/2019/01/1.png" alt="HasThemes"></a>
                            </td>
                        </tr>

                        <tr>
                            <td height="100" style="font-size:10px; line-height:10px;">&nbsp;</td>
                        </tr>
                    </table>
                </center>
            </body>     
        </html>';

        // Build the email headers.
        $email_headers = "MIME-Version: 1.0" . "\r\n";
        $email_headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $email_headers .= 'From:' . $name . ' ' . 'noreply@yourdomain.com' . "\r\n";
        $email_headers .= 'Reply-To:' . $email . "\r\n";

        // Send the email.
        if (mail($recipient, $subject, $email_content, $email_headers)) {
            // Set a 200 (okay) response code.
            http_response_code(200);
            echo "Thank You! ".$name.", Your message has been sent.";
        } else {
            // Set a 500 (internal server error) response code.
            http_response_code(500);
            echo "Oops! Something went wrong and we couldn't send your message.";
        }

    } else {
        // Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
        echo "There was a problem with your submission, please try again.";
    }

?>