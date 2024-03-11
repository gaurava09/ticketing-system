<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<div class="breadcrumb"><h1>News Updates</h1></div>
<div class="separator-breadcrumb border-top"></div>

<?php $this->load->view('common/flashmsg'); ?>

<div class="row">

<div class="col-9 mt-3 mb-3">
<?php

if($data){

$images = json_decode($data['image'],true);
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
                    <div class="carousel-caption d-none d-md-block"><a href="#">
                    <h2 class="text-white">'.$data['title'].'</h2>
                    </a></div>
                </div>';
        }
        echo '</div>';

        echo '</div></div>';
}else{
    echo '<div class="col-12 mt-3 mb-3"><h4>'.$data['title'].'</h4></div>';
}

?>
 <div class="col-12 mt-3 mb-3"><?php echo ps($data,'content'); ?></div>

<?php } //end if data?>
</div>

<div class="col-3 mt-3 mb-3">

<div class="card">
<div class="card-body">
<h4 class="header-title mb-3 text-center">Trending</h4>
<hr class="mt-3 mb-3">

<div class="news_wrapper">
<?php
    foreach ($list as $key => $value) {
        $img = '';
        if($value['thumb']){
            $img = base_url($value['thumb']);
        }
        echo '<div class="mt-2 mb-2"><a href="'.site_url('news/'.$value['id']).'">
            <div class="row">
            <div class="col-md-5 pl-2 pr-0"><img class="d-block w-100" src="'.$img.'" /></div>
            <div class="col-md-7"> <b>'.$value['title'].'</b></div>
            </div></a></div>';
    }
?>
</div>

<div class="mt-4 mb-2 text-center"><a href="#" class="btn btn-primary load-more">View More <i class="i-Triangle-Arrow-Right"></i></a></div>

<input type="hidden" id="row" value="0">
<input type="hidden" id="all" value="<?php echo $totalNews; ?>">

</div> <!-- end card-body-->
</div>
</div>


</div>


<?php $this->load->view('common/footer');  ?>

<script type="text/javascript">
    $(document).ready(function(){

    // Load more data
    $('.load-more').click(function(e){
        e.preventDefault();

        var row = Number($('#row').val());
        var allcount = Number($('#all').val());
        var rowperpage = 3;
        row = row + rowperpage;

        if(row <= allcount){
            $("#row").val(row);
            var newrow = Number($('#row').val());

            $.ajax({
                url: "<?php echo site_url('news/newslist') ?>",
                type: 'get',
                data: {row:row},
                beforeSend:function(){
                    $(".load-more").text("Loading...");
                },
                success: function(response){
                    $html = '';
                    $.each(response.data, function(i,v){
                        $html +='<div class="mt-2 mb-2"><a href="'+v['url']+'">';
                        $html +='<div class="row">';
                        $html +='<div class="col-md-5 pl-2 pr-0"><img class="d-block w-100" src="'+v['thumb']+'" /></div>';
                        $html +='<div class="col-md-7"> <b>'+v['title']+'</b></div>';
                        $html +='</div></a></div>';
                    });

                    $('.news_wrapper').append($html).hide().fadeIn('slow');

                    var rowno = newrow + rowperpage;

                    if(rowno > allcount){
                        $('.load-more').hide();
                    }else{
                        $(".load-more").text("Load more");
                    }
                }
            });
        }

    });

});
</script>