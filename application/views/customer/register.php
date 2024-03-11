<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Register | Customer Support Portal | Tsubaki</title>
        
        <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet">

        <link href="<?php echo base_url('assets/dist-assets/css/themes/lite-purple.min.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/css/style09.css'); ?>" rel="stylesheet">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


        <link href="<?php echo base_url('assets/libs/ladda/ladda-themeless.min.css'); ?>" rel="stylesheet" type="text/css" />

        <style type="text/css">
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

<div class="sig_min registration_bg1">

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
        echo '<img src="'.base_url('assets/images/registration.jpg').'" alt="">';
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
<h1 class="mb-3 text-20"><strong>Create An Account</strong></h1>
</div>



<?php echo form_open('customer/register',array('id' => 'register','autocomplete' => 'on') ); ?>

<div class="errors"></div>



<section class="details">

<div class="form-group">
<label for=" ">First Name*</label>
<input class="form-control form-control-rounded" id="f_name" type="text">
<span class="error error_f_name"></span>
</div>

<div class="form-group">
<label for=" ">Last Name*</label>
<input class="form-control form-control-rounded" id="l_name" type="text">
<span class="error error_l_name"></span>
</div>

<div class="form-group">
<label for="email">Email Id*</label>
<input class="form-control form-control-rounded" id="email" type="email">
<span class="error error_email"></span>
</div>


<div class="form-group">
<label for=" ">Company</label>
<select class="form-control form-control-rounded" id="company">
    <option value="">Select Company</option>
</select>
<!-- <input class="form-control form-control-rounded" id="company" type="hidden" readonly=""> -->
<!-- <input class="form-control form-control-rounded" id="company_name" type="text" readonly=""> -->
<span class="error error_company"></span>
</div>


<div class="row">

<div class="col-md-5">
<div class="form-group">
<label for=" ">Country Code*</label>
<select class="form-control form-control-rounded" id="country_code">
    <?php
        foreach ($phonecodes as $key => $value) {
            if($value['phonecode']){
                 $attr = '';
                if($value['phonecode'] == 91){
                    $attr = 'selected';
                }
                echo '<option value="'.$value['phonecode'].'" '.$attr.' >'.$value['phonecode'].' - '.$value['name'].'</option>';
            }
        }
    ?>
</select>
</div>
</div>

<div class="col-md-7">
<div class="form-group">
<label for="mobile">Mobile Number*</label>
<input class="form-control form-control-rounded" id="mobile"  name="mobile">
<span class="error error_mobile"></span>
</div>
</div>

</div>

<div class="form-group">
<label for=" ">Location*</label>
<input class="form-control form-control-rounded" id="location" type="text">
<span class="error error_location"></span>
</div>


<!-- <div class="form-group">
    <label for=" ">Select Company*</label>
    <select class="form-control form-control-rounded " id="company">
            <option value="">Select Company</option>
        <?php/*
            foreach($data as  $key => $value){
                echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
            }*/
        ?>
    </select>
    <span class="error_company"></span>
</div> -->

<div class="form-group">
    <a class="btn btn-rounded btn-primary btn-block mt-3 validate_details ladda-button" href="#"  dir="ltr" data-style="slide-up">Next</a>
</div>

</section>



<section class="verify_div d-none">
    <div class="form-group text-center">
        <span style="font-weight: initial;font-size: 13px;">Verification code is sent on your e-mail ID used for creation of this account</span>
    </div>

    <div class="form-group">
        <label for=" ">Verification Code</label>
        <input class="form-control form-control-rounded" id="vcode" type="password">

        <span class="error error_vcode"></span>
    </div>

    <div class="form-group">
        <a class="btn btn-rounded btn-primary btn-block mt-3 verify_code ladda-button" href="#"  dir="ltr" data-style="slide-up">Next</a>
    </div>
</section>


<section class="password_div d-none">

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
            
        </div>
        <span class="error error_pass_1"></span>
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
            
        </div>
        <span class="error error_pass_2"></span>
    </div>


   <!--  <div class="form-group">
        <label for=" ">Password*</label>
        <input class="form-control form-control-rounded" id="pass_1" type="password">
        <span class="fa fa-fw fa-eye field_icon toggle-password"></span>

        <span class="error_pass_1"></span>
    </div> -->
<!-- 
    <div class="form-group">
        <label for=" ">Re-Type Password*</label>
        <input class="form-control form-control-rounded" id="pass_2" type="password">
        <span class="error_pass_2"></span>
    </div> -->

    <div class="form-group mt-3">
        <div class="g-recaptcha" data-sitekey="<?php echo $RECAPTCHA_SITEKEY;?>"></div>
        <span class="gresp"></span>
    </div>

    <div class="form-group">
        <label class="checkbox checkbox-primary">
            <input type="checkbox" class="tc"><span>I accept <a href="#" data-toggle="modal" data-target="#tandcModal"><b>Terms and Conditions*</b></a> </span><span class="checkmark"></span>
        </label>
        <span class="error error_tc"></span>

        <a class="btn btn-rounded btn-primary btn-block mt-3 regsiter_button ladda-button" href="#"  dir="ltr" data-style="slide-up">Create Account</a>
    </div>
</section>

</form>
</div>

</div>
<div class="clearfix"></div>
</div>
</div>    

<style type="text/css">
    .d-none{display: none;}
</style>

<?php $this->load->view('pages/terms');  ?>

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

    $("#email").focusout(function(){
        $token  = $('#register [name=token]').val();
        $email = $.trim($('#email').val());

        $('#company, #company_name').val('');

        if($email == ""){
            $('.error_email').html('Enter email id');
            return false;
        }else if(!nilesh.validateEmail($email)){
            $('.error_email').html('Invalid email id');
            return false;
        }else{
            $('.error_email').html('');
        }

        var postdata = {"email":$email,"token":$token};
        $.ajax({
            type: 'post',
            url: '<?php echo site_url()?>customer/getCompany',
            data: postdata,

            success: function($res) {
                console.log($res.data);
                $html = '<option value="">Select Company</option>';
                if($res.status == 1){
                    // $('#company').val($res.data.id);
                    // $('#company_name').val($res.data.name);
                    $.each($res.data,function(i,v){
                        $html += '<option value="'+v.id+'">'+v.name+'</option>';
                    });
                    $('#company').html($html);
                }else{  
                    // $('#company, #company_name').val('');
                    $('#company').html($html);
                    alert($res.message);
                }
            },
            error: function(error, textStatus, errorMessage) {
                alert('Failed to get company details');
            }             
        }); //end ajax
    });

    $('.validate_details').click(function(e){
        e.preventDefault();

        $f_name = $('#f_name').val();
        $l_name = $('#l_name').val();
        $email = $('#email').val();
        $country_code = $('#country_code').val();
        $mobile = $('#mobile').val();
        $company = $('#company').val();
        $location = $('#location').val();
        $token  = $('#register [name=token]').val();

        $error = 0;

        if($email == ""){
            $('.error_email').html('Enter email id');
            $error = 1;
        }else if(!nilesh.validateEmail($email)){
            $('.error_email').html('Invalid email id');
             $error = 1;
        }else{
            $('.error_email').html('');
        }
        
        if($f_name  == ""){
            $('.error_f_name').html('Enter First Name');
            $error = 1;
        }else{
            $('.error_f_name').html('');
        }

        if($l_name  == ""){
            $('.error_l_name').html('Enter Last Name');
            $error = 1;
        }else{
            $('.error_l_name').html('');
        } 

        if($country_code  == ""){
            $('.error_mobile').html('Select Country Code');
            $error = 1;
        }else{
            $('.error_mobile').html('');
        }

        $regex = /^\d{10,12}$/;
        if($mobile  == ""){
            $('.error_mobile').html('Enter Mobile');
            $error = 1;
        }
        else if(!$regex.test($mobile)){
            $('.error_mobile').html('Invalid mobile');
            $error = 1;
        }
        else{
            $('.error_mobile').html('');
        }

        if($location  == ""){
            $('.error_location').html('Enter location');
            $error = 1;
        }else{
            $('.error_location').html('');
        } 

        if($company  == ""){
            $('.error_company').html('Select Company');
            $error = 1;
        }else{
            $('.error_company').html('');
        }

      /*  if($company  == ""){
            $('.error_company').html('Select Company');
            $error = 1;
        }else{
            $('.error_company').html('');
        }*/
        

        if($error == 1){
            return false;
        }

        var postdata = {"f_name":$f_name,"l_name":$l_name,"email":$email, "country_code":$country_code, "mobile":$mobile, "company":$company,"location":$location, "token":$token};

        $button = $(this);
        $button.addClass('process');
        var l = Ladda.create($('.validate_details')[0]);
        l.start();

        $('.errors').html('');
        $.ajax({
            type: 'post',
            url: '<?php echo site_url()?>customer/verifyDetails',
            data: postdata,

            success: function($res) {
                
                if($res.status == 1){
                    $('.details').addClass('d-none');
                    $('.verify_div').removeClass('d-none');
                    // alert($res.message);
                }else{  
                    $('.errors').html($res.message);
                }
                
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

     
    //verify code
     $('.verify_code').click(function(e){
        e.preventDefault();

        $vcode = $('#vcode').val();
        $token  = $('#register [name=token]').val();
        $error = 0;

        $regex = /^\d{6}$/;
        if($vcode  == ""){
            $('.error_vcode').html('Enter verification code');
            $error = 1;
        }
        else if(!$regex.test($vcode)){
            $('.error_vcode').html('Invalid code');
            $error = 1;
        }
        else{
            $('.error_vcode').html('');
        }

        if($error == 1){
            return false;
        }

        var postdata = {"vcode":$vcode,"token":$token};

        $button = $(this);
        $button.addClass('process');
        var l = Ladda.create($('.verify_code')[0]);
        l.start();

        $('.errors').html('');
        $.ajax({
            type: 'post',
            url: '<?php echo site_url()?>customer/verifyCode',
            data: postdata,

            success: function($res) {
                
                if($res.status == 1){
                    $('.verify_div').addClass('d-none');
                    $('.password_div').removeClass('d-none');
                }else{  
                    $('.errors').html($res.message);
                }
                
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

     //set password
     $('.regsiter_button').click(function(e){
        e.preventDefault();

        $f_name = $('#f_name').val();
        $l_name = $('#l_name').val();
        $email = $('#email').val();
        $country_code = $('#country_code').val();
        $mobile = $('#mobile').val();
        $company = $('#company').val();
        $location = $('#location').val();

        $token  = $('#register [name=token]').val();

        $pass_1 = $('#pass_1').val();
        $pass_2 = $('#pass_2').val();
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

        if($('.tc:checked').length == 0){
            $('.error_tc').html('Please accept terms and conditions');
            $error = 1;
        }else{
            $('.error_tc').html('');
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

        var postdata = {"f_name":$f_name,"l_name":$l_name,"email":$email, "country_code":$country_code, "mobile":$mobile, "company":$company, "location":$location, "pass_1":$pass_1,"pass_2":$pass_2, "token":$token,"captcha" : g_resp};

        $button = $(this);
        $button.addClass('process');
        var l = Ladda.create($('.regsiter_button')[0]);
        l.start();

        $('.errors').html('');
        $.ajax({
            type: 'post',
            url: '<?php echo site_url()?>customer/register',
            data: postdata,

            success: function($res) {
                
                if($res.status == 1){
                    window.location.href = "<?php echo site_url($this->CUST_HOME)?>";
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
                grecaptcha.reset();
            }             
        }); //end ajax
        
    });
</script>

</body>

</html>