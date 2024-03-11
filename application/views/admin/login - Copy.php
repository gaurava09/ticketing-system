<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Signin | Tsubaki</title>
        
        <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet">
        <link href="<?php echo site_url(); ?>assets/dist-assets/css/themes/lite-purple.min.css" rel="stylesheet">
        <link href="<?php echo site_url(); ?>assets/css/style09.css" rel="stylesheet">


        <link href="<?php echo site_url(); ?>/assets/libs/ladda/ladda-themeless.min.css" rel="stylesheet" type="text/css" />
    </head>

<body>

        <div class="account-pages mt-5 mb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card bg-pattern">

                            <div class="card-body p-3">
                                
                                <div class="text-center w-75 m-auto">
                                       <div class="text-center mt-4 mb-4"><img src="<?php echo site_url(); ?>assets/images/mahindra_tsubaki.png" alt=""></div>
                                </div>


                                <!-- <form class="login_div" action="#"> -->
                              <?php echo form_open('admin/login',array('id' => 'login','autocomplete' => 'on') ); ?>

                                    <div class="form-group mb-3">
                                        <label for="userid" style="margin-top:10px;">Login</label>
                                        <input class="form-control" type="text" id="email" required="" placeholder="Enter email">
                                        <span class="user_email"></span>
                                    </div>

                                    <div class="form-group mb-3">
                                        <input class="form-control" type="password" id="password" required="" placeholder="Enter password">
                                        <span class="user_pass"></span>
                                    </div> 
                                    <div class="form-group mb-3">
                                        <div class="g-recaptcha" data-sitekey="<?php echo $RECAPTCHA_SITEKEY;?>"></div>
                                        <span class="gresp"></span>
                                    </div>

                                    <div class="form-group mb-0 text-center">
                                        <button class="ladda-button btn btn-primary btn-block" dir="ltr" data-style="slide-up" type="submit" id="submit_login">Submit </button>
                                    </div>

                                     <!-- <input type="text" class="token" name="<?php //echo $this->security->get_csrf_token_name(); ?>" value="<?php //echo $this->security->get_csrf_hash(); ?>"><br>    -->
                                </form>
                                <div class="errors">
                                    
                                </div>
                            </div> <!-- end card-body -->
                        </div>
                        <!-- end card -->


                        <!-- end row -->

                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end page -->


<script src="https://www.google.com/recaptcha/api.js" async defer></script>

  
<script src="<?php echo site_url(); ?>assets/dist-assets/js/plugins/jquery-3.3.1.min.js"></script>

<script src="<?php echo site_url(); ?>/assets/libs/ladda/spin.min.js"></script>
<script src="<?php echo site_url(); ?>/assets/libs/ladda/ladda.min.js"></script>

    <script type="text/javascript">

    $('#submit_login').click(function(e){
        e.preventDefault();
        $error = '';
        $email = $('#email').val();
        $password  = $('#password').val();
        $token  = $('#login [name=token]').val();

        var emailpattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);


        if($email == ""){
            $('.user_email').html('Enter email id');
            $error = 1;
        }else if(!emailpattern.test($email)){
            $('.user_email').html('Invalid email id');
           $error = 1;
        }else{
            $('.user_email').html('');
        }

        if($password  == ""){
            $('.user_pass').html('Enter password');
            $error = 1;
        }else{
            $('.user_pass').html('');
        }

        var g_resp = grecaptcha.getResponse();
        if(g_resp  == ""){
            $('.gresp').html('Invalid Captcha');
            $error = 1;
        }else{
            $('.gresp').html('');
        }

        if($error == 1){
            return false;
        }
        var postdata = {"email":$email, "password":$password, "token":$token,"captcha" : g_resp};

        $button = $(this);
        $button.addClass('process');
        var l = Ladda.create($('#submit_login')[0]);
        l.start();

        $('.errors').html('');
        $.ajax({
            type: 'post',
            url: '<?php echo site_url()?>admin/login',
            data: postdata,

            success: function($res) {
                
                if($res.status == 1){
                     window.location.href = "<?php echo site_url()?>";
                     return false;
                }
                else if($res.status == 2){
                    grecaptcha.reset();
                }

                $('.errors').html($res.message);
                
                $button.removeClass('process');
                Ladda.stopAll();
            },
            error: function(error, textStatus, errorMessage) {
                $('.errors').html('Some error occured.');
                $button.removeClass('process');
                Ladda.stopAll();
            }             
        }); //end ajax
        
    });
        </script>
        
    </body>
</html>