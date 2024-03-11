<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Reset Password | Tsubaki</title>
        
        <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet">
        <link href="<?php echo site_url(); ?>assets/dist-assets/css/themes/lite-purple.min.css" rel="stylesheet">
        <link href="<?php echo site_url(); ?>assets/css/style09.css" rel="stylesheet">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <link href="<?php echo site_url(); ?>/assets/libs/ladda/ladda-themeless.min.css" rel="stylesheet" type="text/css" />

        <style type="text/css">

            .toggle-password{cursor: pointer;}

            .sig_col_left{
                float: left;
                display: flex;
                align-content: center;
                align-items: center;
                width: 70%;
                background: #fff;
                height: 100%;}
            .toggle-password{cursor: pointer;}
        </style>

    </head>

<body>


<body>

<div class="sig_min registration_bg resetpass_bg">


<div class="sig_col_left">

    <?php
    $images = json_decode($page['image'],true);
    if(is_array($images) && !empty($images)){

        echo '<div class="carousel_wrap">
            <div class="carousel slide" id="carouselExamplePause" data-ride="carousel">';

            echo '<ol class="carousel-indicators">';
            foreach ($images as $key => $value) {
                $class =''; if($key == 0){  $class = 'active';  }
                echo '<li class="'.$class.'" data-target="#carouselExamplePause" data-slide-to="'.$key.'"></li>';
            }
            echo '</ol>';

            echo '<div class="carousel-inner">';
            foreach ($images as $key => $value) {
                $class =''; if($key == 0){  $class = 'active';  }
                echo '<div class="carousel-item '.$class.'"><img class="d-block w-100" src="'.base_url($value['path']).'" />
                        
                    </div>';
            }
            echo '</div>';

            echo '</div></div>';
    }else{
        echo '<img src="'.base_url('assets/images/login.jpg').'" alt="">';
    }

    ?>
</div>

<div class="sig_col_right">
<div class="box_alg_center">    
    
<div class="p-4">
<div class="text-center mt-4 mb-4">
    <a href="<?php echo site_url('customer/login'); ?>">
    <img src="<?php echo base_url('assets/images/mahindra_tsubaki.png'); ?>" alt=""></a>
</div>

<div class="text-center">
<h1 class="mb-3 text-20"><strong>Reset Password</strong></h1>
</div>



<?php echo form_open('customer/resetpassword/',array('id' => 'reset_pass_form','autocomplete' => 'on') ); ?>

<input class="form-control form-control-rounded" id="reset_token" type="hidden" value="<?php echo $reset_token; ?>">


<div class="errors"></div>

<div class="form-group ">
    <label for="email">Email Id*</label>
    <input class="form-control form-control-rounded" id="email" type="email">
    <span class="error_email"></span>
</div>


    <div class="form-group">
        <label for="password">Password*</label>
        <div class="input-group input-group-merge">
            <input type="password" id="pass_1" class="form-control form-control-rounded">
            <div class="input-group-append" data-password="false">
                <div class="input-group-text toggle-password">
                     <i class="fa fa-eye " aria-hidden="true"></i>
                    <!-- <span class="fa fa-eye field_icon  i-Eye"></span> -->
                </div>
            </div>
            <span class="error_pass_1"></span>
        </div>
    </div>

    <div class="form-group">
        <label for="password">Re-Type Password*</label>
        <div class="input-group input-group-merge">
            <input type="password" id="pass_2" class="form-control form-control-rounded">
            <div class="input-group-append" data-password="false">
                <div class="input-group-text toggle-password">
                    <i class="fa fa-eye " aria-hidden="true"></i>
                </div>
            </div>
            <span class="error_pass_2"></span>
        </div>
    </div>

<!-- <div class="form-group">
    <label for=" ">Password*</label>
    <input class="form-control form-control-rounded" id="pass_1" type="password">
    <span class="error_pass_1"></span>
</div>

<div class="form-group">
    <label for=" ">Re-Type Password*</label>
    <input class="form-control form-control-rounded" id="pass_2" type="password">
    <span class="error_pass_2"></span>
</div> -->

<div class="form-group mt-3">
    <div class="g-recaptcha" data-sitekey="<?php echo $RECAPTCHA_SITEKEY;?>"></div>
    <span class="gresp"></span>
</div>

<button class="ladda-button btn btn-rounded btn-primary btn-block mt-5" dir="ltr" data-style="slide-up" type="submit" id="reset_pass">Reset </button>

</form>


<div class="success d-none">
    Password reset successful. <br>
     <a class="btn btn-rounded btn-info btn-block mt-3" href="<?php echo site_url('customer/login'); ?>">Login</a>

</div>


</div>

</div>
<div class="clearfix"></div>
</div>
</div>    

<style type="text/css">
    .d-none{display: none;}
</style>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<?php
    echo getScript('jquery');
    echo getScript('bootstrap');
?>

<script src="<?php echo site_url(); ?>/assets/libs/ladda/spin.min.js"></script>
<script src="<?php echo site_url(); ?>/assets/libs/ladda/ladda.min.js"></script>

<script src="<?php echo site_url(); ?>/assets/custom.js"></script>

<script type="text/javascript">

    $(document).on('click', '.toggle-password', function() {
        $(this).find('i').toggleClass("fa-eye fa-eye-slash");
        var input = $(this).parents('.form-group').find('input');
        input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
    });


    $('#reset_pass').click(function(e){
        e.preventDefault();

        $token          = $('#reset_pass_form [name=token]').val();
        $email          = $('#email').val();
        $reset_token    = $('#reset_token').val();
        
        $pass_1         = $('#pass_1').val();
        $pass_2         = $('#pass_2').val();
        $error = 0;

        if($pass_1  == ""){
            $('.error_pass_1').html('Enter password');
            $error = 1;
        }
        else if(!nilesh.strongPass($pass_1)){
            $('.error_pass_1').html('Password must be more than 8 digit and must contain atleast one lower & upper case letter, one digit and a special character');
            $error = 1;
        }
        else{
            $('.error_pass_1').html('');
        }

        if($pass_2  == ""){
            $('.error_pass_2').html('Confirm password');
            $error = 1;
        }
        else if($pass_1  != $pass_2){
            $('.error_pass_2').html('Password does not match');
            $error = 1;
        }else{
            $('.error_pass_2').html('');
        }


        if($email == ""){
            $('.error_email').html('Enter email id');
            $error = 1;
        }else if(!nilesh.validateEmail($email)){
            $('.error_email').html('Invalid email id');
             $error = 1;
        }else{
            $('.error_email').html('');
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

        var postdata = {"email":$email,"pass_1":$pass_1,"pass_2":$pass_2,"reset_token":$reset_token,"token":$token,"captcha" : g_resp};

        $button = $(this);
        $button.addClass('process');
        var l = Ladda.create($(this)[0]);
        l.start();

        $('.errors').html('');
        $.ajax({
            type: 'post',
            url: $('#reset_pass_form').attr('action'),
            data: postdata,

            success: function($res) {
                
                if($res.status == 1){
                   $('#email').val('');
                   $('#reset_pass_form').hide();
                   $('.success').removeClass('d-none');
                }
                else {
                    grecaptcha.reset();
                    $('.errors').html($res.message);
                }
                
                
                $button.removeClass('process');
                Ladda.stopAll();
            },
            error: function(error, textStatus, errorMessage) {
                $('.errors').html('Some error occured.');
                $button.removeClass('process');
                Ladda.stopAll();
                grecaptcha.reset();
            }             
        }); //end ajax
        
    });

</script>

</body>

</html>