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




<div class="row">

<!--  end of col -->
<div class="col-12 mt-3 mb-3">
<div class="card text-left">
<div class="card-body">

<?php
    if($faqs){
        $index = 1;
        echo '<ul class="nav nav-pills" id="myPillTab" role="tablist">';
        foreach ($faqs as $key => $value) {
            $class ='';
            if($index == 1){  $class = 'active';  }

            if(empty($value['subcat'])){
                continue;
            }
            echo '<li class="nav-item"><a class="nav-link '.$class.'" id="'.$key.'" data-toggle="pill" href="#'.$key.'_cat" role="tab" aria-controls="'.$key.'_cat" aria-selected="true"><i class="nav-icon i-Split-Horizontal-2-Window"></i> '.$value['title'].'</a></li>';
            $index++;
        }
        echo '</ul>';


        $index = 1;
        echo '<div class="tab-content" id="myPillTabContent">';

        foreach ($faqs as $key => $value) { 
            $class ='';
            if($index == 1){  $class = 'active';  }

          echo '<div class="tab-pane fade show '.$class.' accordion_faq_min" id="'.$key.'_cat" role="tabpanel" aria-labelledby="'.$key.'">
             <!-- accordion-->
            <div class="accordion_tab accordion_4 ">';

                foreach ($value['subcat'] as $k => $cat) {
                    if(is_array($cat['content'])){

                        echo '<div class="acc-row ">
                            <a class="min_box-h_tab "><h3 class="font_s0 co_b">'.$cat['title'].'</h3></a>
                            <div class="min_box-tx_tab">
                            <ul>  <div class="accordion accordion_faq">';
                            foreach ($cat['content'] as $f => $val) {  //faq loop
                                echo '<li class="fqn_q">
                                        <a class="min_box-h"><span class="ques">Q'.($f+1).'.</span> <p class="ques-txt">'.$val['title'].'</p></a>
                                        <div class="min_box-tx">
                                        <span class="ans">ANS.</span> <p>'.$val['desc'].'</p>
                                        </div>
                                    </li>';
                            }
                        echo '</div> </ul>
                            </div> 
                        </div>';
                        $index++;
                     }
                }
        echo   '</div>
            </div>';

        }
        echo '</div>';
    }
?>
<!-- <ul class="nav nav-pills" id="myPillTab" role="tablist">
    <li class="nav-item"><a class="nav-link active" id="home-icon-pill" data-toggle="pill" href="#homePIll" role="tab" aria-controls="homePIll" aria-selected="true"><i class="nav-icon i-Split-Horizontal-2-Window"></i> FAQ 's for equipments of Unit Material Handling (UMH)</a></li>
    <li class="nav-item"><a class="nav-link" id="profile-icon-pill" data-toggle="pill" href="#profilePIll" role="tab" aria-controls="profilePIll" aria-selected="false"><i class="nav-icon i-Split-Horizontal-2-Window"></i> FAQ's for equipment of Bulk Material Handling (BMH)</a></li>
</ul> -->

</div>
</div>
</div>
</div>

<?php $this->load->view('common/footer');  ?>