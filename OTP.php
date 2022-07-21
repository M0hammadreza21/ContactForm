<?php
	session_start();

    // Check if User Coming From A Request
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
////////////////////////////////////////////////////////////////////////////    
        // Assign Variables
        $cell = filter_var($_POST['cellphone'], FILTER_SANITIZE_NUMBER_INT);
        $captcha = $_POST['farsicaptcha'];
        
        // Creating Array of Errors
        $formErrors = array();
        if (strlen($cell) < 10) {
            $formErrors[] = 'تعداد ارقام شماره موبایل کمتر از 10 کاراکتر باشد'; 
        }
        if ($captcha != $_SESSION['farsicaptchacode']) {
            $formErrors[] = $_SESSION['farsicaptchacode'].'کد وارد شده اشتباه است'; 
        }
    
        // If No Errors
        if (empty($formErrors)) {
 $otp = substr(rand(12345, 99999), 0, 6);               
            ini_set("soap.wsdl_cache_enabled", "0");
              try {
            $client = new SoapClient('http://api.payamak-panel.com/post/send.asmx?wsdl', array('encoding'=>'UTF-8'));
                $parameters['username'] = "09194087030";
                $parameters['password'] = "cge7a";
                $parameters['from'] = "50004001087030";
                $parameters['to'] = array("9194087030");
                $parameters['text'] ="$otp";
                $parameters['isflash'] = false;
                $parameters['udh'] = "";
                $parameters['recId'] = array(0);
                $parameters['status'] = 0x0;
            
            switch ($client->SendSms($parameters)->SendSmsResult) {
              case 1:
                $SendSmsResult = "پیامک با موفقیت ارسال شد";
                break;
              case 0:
                $SendSmsResult = "پیامک ارسال نشد";
                break;
              default:
                $SendSmsResult = "";
            }
            
             } catch (SoapFault $ex) {
                echo $ex->faultstring;
            }
            
            $success = '<div class="alert alert-success">'.$SendSmsResult.'</div>';
            session_destroy();
            
        }
        
    }
////////////////////////////////////////////////////////////////////////////  
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>M0hammadreza.ir</title>
        <link type="text/css" rel="stylesheet" href="https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font@v30.1.0/dist/font-face.css">
        <link rel="stylesheet" href="css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/font-awesome.min.css" />
        <link rel="stylesheet" href="css/contact2.css" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:400,700,900,900i">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        
        <!-- Start Form -->
        
        <div class="container">
            <h1 class="text-center">M0hammadreza</h1>
            <form class="contact-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <?php if (! empty($formErrors)) { ?>
                <div class="alert alert-danger alert-dismissible" role="start">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php
                        foreach($formErrors as $error) {
                            echo $error . '<br/>';
                        }
                    ?>
                </div>
                <?php } ?>
                <?php if (isset($success)) { echo $success; } ?>


                <input 
                       class="form-control" 
                       type="text" 
                       name="cellphone" 
                       placeholder="تلفن همراه شما" 
                       value="<?php if (isset($cell)) { echo $cell; } ?>" />
                <i class="fa fa-phone fa-fw"></i>

                    <div class="form-group">
                    <img src="FarsiCaptcha/src/farsicaptcha2.php" />
                    <input 
                           class="farsicaptcha form-control" 
                           type="number" 
                           name="farsicaptcha" 
                           style="width:50%;"
                           maxlength="5"
                           placeholder="کد نمایش داده شده وارد کنید"
                           value="<?php if (isset($captcha)) { echo $captcha; } ?>" />

                    <div class="alert alert-danger custom-alert">
                        عدد نمایش داده شده در کادر زیر را وارد کنید
                    </div>
                </div>


                <input 
                       class="btn btn-success" 
                       type="submit" 
                       value="ارسال پیام" />
                       <i class="fa fa-send fa-fw send-icon"></i>
            </form>
        </div>
        
        <!-- End Form -->
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>