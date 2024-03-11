<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<div class="breadcrumb"><h1><?php echo ps($data,'title'); ?></h1></div>
<div class="separator-breadcrumb border-top"></div>
<div class="row">
    <?php
        if(ps($data,'image')){
            echo '<div class="col-md-12"><div class="user-profile">
            <div class="header-cover" style="background-image: url('.base_url($data['image']).')"></div>
            </div></div>';
        }
    ?>

    <div class="col-12 mt-3 mb-3"><?php echo ps($data,'content'); ?></div>
</div>


<?php $this->load->view('common/footer');  ?>