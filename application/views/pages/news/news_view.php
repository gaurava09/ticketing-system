<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- <link type="text/css" rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css" />
<link type="text/css" rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css" />
<link type="text/css" rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.transitions.min.css" />
 -->

<link rel="stylesheet" href="<?php echo base_url('assets/libs/OwlCarousel2-2.3.4/dist/assets/owl.carousel.min.css') ?>" />
<!-- <link rel="stylesheet" href="<?php echo base_url('assets/libs/OwlCarousel2-2.3.4/dist/assets/owl.theme.default.css') ?>" /> -->

<style type="text/css">
    .owl-dots {
      margin: 0 auto /*for centering the dots*/
}

.owl-dot {
     width: 10px;
     height: 10px;
     border-radius: 100%;
     border: 1px solid #ccc;
     background: #333 !important;
     margin-right: 5px;
     display: inline-block;  /*for making them horizontal, if you want vertical then you can use- display: block*/
}

.owl-dot.active {
     background: #ffa60a !important;
}

</style>

<div class="breadcrumb"><h1>News Updates</h1></div>
<div class="separator-breadcrumb border-top"></div>

<?php $this->load->view('common/flashmsg'); ?>

<div class="row">

<div class="col-md-9 mt-3 mb-3">
<?php if($news){ ?>

    <div id="figcaption"><?php echo $news[0]['title']; ?></div>

    <div class="owl-carousel news-owl-carousel">
        <?php
            foreach ($news as $key => $value) {
                echo '
                <div class="item">
                    <img src="'.base_url($value['thumb']).'" title="'.$value['title'].'" alt="Alt 1" />
                </div>';
            }
        ?>
    </div>

<?php } //end if data ?>
</div>

<div class="col-md-3 mt-3 mb-3 ">

    <div class="card">
        <div class="card-body">
        <h4 class="header-title mb-3 text-center">Trending</h4>
        <hr class="mt-3 mb-3">
        <?php if($list){  ?>
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

        <?php
            if($totalNews > 3){
                echo '<div class="mt-4 mb-2 text-center"><a href="#" class="btn btn-primary load-more">View More <i class="i-Triangle-Arrow-Right"></i></a></div>';
            }
        } else{
            echo '<p class="no_new_content"> No news available</p>';
        } ?>
        <input type="hidden" id="row" value="0">
        <input type="hidden" id="all" value="<?php echo $totalNews; ?>">

        </div> <!-- end card-body-->
    </div>
</div>


</div>




<?php $this->load->view('common/footer');  ?>


<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js"></script> -->


<script src="<?php echo base_url('assets/libs/OwlCarousel2-2.3.4/dist/owl.carousel.min.js'); ?>"></script>


<script type="text/javascript">
$(document).ready(function(){

    /*$('.owl-carousel').owlCarousel({
        loop: true,
        items: 1,
        navigation: true,
        pagination: true,
        lazyLoad: true,
        singleItem: true,
        afterMove: function(elem) {
            var current = this.currentItem;
            var currentImg = elem.find('.owl-item').eq(current).find('img');

            $('figure')
                .find('img')
                .attr({
                    'src': currentImg.attr('src'),
                    'alt': currentImg.attr('alt'),
                    'title': currentImg.attr('title')
                });
            $('#figcaption').text(currentImg.attr('title'));
        }
    });*/ 

    var owl = $(".owl-carousel");

    owl.owlCarousel({
        loop: true,
        items: 1,
        nav: true,
        navText:["<div class='nav-btn prev-slide'>Prev</div>","<div class='nav-btn next-slide'>Next</div>"],
        autoHeight: true,
        lazyLoad: true,
        singleItem: true,
        animateOut: 'fadeOut',
        autoplaySpeed: 500,
        dots:true,
        // dotsContainer:'#dots',
        // dotsEach: true    
    });

    owl.on('changed.owl.carousel',function(property){
        var current = property.item.index;
        var title = $(property.target).find(".owl-item").eq(current).find("img").attr('title');
        $('#figcaption').text(title);
    });

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