<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
	<meta charset="utf-8" />
	<title> <?php 

    if(isset($title)){
        echo $title; 
    }else{
        echo "Tsubaki ";
    }
    
    $ver = 6;
    ?> </title>


    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <!--========================= CSS =========================-->
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet" />
    <link href="<?php echo base_url('assets/dist-assets/css/themes/lite-purple.min.css'); ?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/dist-assets/css/plugins/perfect-scrollbar.min.css'); ?>" rel="stylesheet" />

    <link href="<?php echo base_url('assets/css/style09.css'); ?>" rel="stylesheet" />


        
    <!-- third party css end -->
    <link href="<?php echo base_url('assets/libs/ladda/ladda-themeless.min.css'); ?>" rel="stylesheet" type="text/css" />

    <!-- Loading buttons css/js -->
    <script src="<?php echo base_url('assets/libs/ladda/spin.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/libs/ladda/ladda.min.js'); ?>"></script>

    <script type="text/javascript">
        var base_url = "<?php echo site_url() ?>";

        function menuActive($class){
            $('.'+$class).addClass('menuitem-active');
            $('.'+$class+' > a').attr('aria-expanded','true');
            $('.'+$class+' .collapse').addClass('show');
            $('.'+$class+' > a').addClass('active');
        }
    </script>

    <style type="text/css">
        #image_preview {
            width: 200px;
            margin-top: 12px;
        }

        .image_preview {
            width: 170px;
            margin-top: 12px;
        }
    </style>
</head>

    <body class="text-left">
        <div class="app-admin-wrap layout-sidebar-large clearfix">

            <?php 
                if($this->role == 'customer'){
                    $this->load->view('common/left_sidebar_customer'); 
                }else{
                    $this->load->view('common/left_sidebar_admin'); 
                }
            ?>

            <?php $this->load->view('common/topbar'); ?>

            <div class="main-content-wrap sidenav-open d-flex flex-column">
                <div class="main-content">
                    <?php 
                        if(isset($flag)){
                            $this->load->view($template); 
                        }
                        else if($this->role =='customer'){
                            $this->load->view('customer/'.$template); 
                        }else{
                            $this->load->view('admin/'.$template); 
                        }
                        
                    ?>

            <?php $this->load->view('common/notification_js');  ?>
    </body>

</html>