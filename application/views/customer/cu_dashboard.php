<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<style type="text/css">
	.marquee3k{opacity: 0;}
</style>
<div class="">
	<div class="float-left breadcrumb"><h1 class="mr-2">Dashboard </h1></div>
	<div class="clearfix"></div>
</div>
<div class="separator-breadcrumb border-top"></div>

<?php
if($news){
	/*echo '<div class="row mb-2">
			<marquee width="100%" direction="left" height="30px">';
			foreach ($news as $key => $value) {
				echo '<a href="'.site_url('news/'.$value['id']).'" class="pl-4">'.$value['title'].'</a>';
			}
	echo '</marquee>
		</div>';
*/
		echo ' <div class="row mb-2">
				<div class="marquee3k"  data-speed="1"  data-pausable="true">
					<div>';
			     	foreach ($news as $key => $value) {
						echo '<a href="'.site_url('news/'.$value['id']).'" class="pl-4">'.$value['title'].'</a>';
					}
		echo '</div></div></div>';
}
?>


<div class="row dboxes mb-4">

	<div class="col-lg-3 col-md-6 col-sm-6">
	<div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
	<div class="card-body text-center">
		<a href="<?php echo site_url('customer/complaint');?>?status=2">
			<div class="content">
			<p class="text-muted mt-2 mb-0">Open Tickets</p>
			<p class="text-primary text-24 line-height-1 mb-2"><?php echo $data['open_ticket'] ?></p>
			</div>
		</a>
	</div>
	</div>
	</div>


	<div class="col-lg-3 col-md-6 col-sm-6">
	<div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
	<div class="card-body text-center">
		<a href="<?php echo site_url('customer/complaint');?>?status=3">
			<div class="content">
			<p class="text-muted mt-2 mb-0">Assigned Tickets</p>
			<p class="text-primary text-24 line-height-1 mb-2"><?php echo $data['ongoing_ticket'] ?></p>
			</div>
			</a>
	</div>
	</div>
	</div>


	<div class="col-lg-3 col-md-6 col-sm-6">
	<div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
	<div class="card-body text-center">
		<a href="<?php echo site_url('customer/complaint');?>?status=1">
			<div class="content">
			<p class="text-muted mt-2 mb-0">Completed Tickets</p>
			<p class="text-primary text-24 line-height-1 mb-2"><?php echo $data['completed_ticket'] ?></p>
			</div>
			</a>
	</div>
	</div>
	</div>

	<div class="col-lg-3 col-md-6 col-sm-6">
	<div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
	<div class="card-body text-center">
		<a href="<?php echo site_url('customer/complaint');?>?status=4">
			<div class="content">
			<p class="text-muted mt-2 mb-0">Closed Tickets</p>
			<p class="text-primary text-24 line-height-1 mb-2"><?php echo $data['closed_ticket'] ?></p>
			</div>
			</a>
	</div>
	</div>
	</div>


	<!-- <div class="col-lg-3 col-md-6 col-sm-6">
	<div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
	<div class="card-body text-center">
		<a href="<?php echo site_url('customer/enquiry');?>?status=2">
		<div class="content">
		<p class="text-muted mt-2 mb-0">Open Spares Enquiry</p>
		<p class="text-primary text-24 line-height-1 mb-2"><?php echo $data['open_enquiry'] ?></p>
		</div>
	</a>
	</div>
	</div>
	</div> -->

	<!-- <div class="col-lg-3 col-md-6 col-sm-6">
	<div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
	<div class="card-body text-center">
		<a href="<?php echo site_url('customer/enquiry');?>?status=4">
		<div class="content">
		<p class="text-muted mt-2 mb-0">Attended Spares Enquiry</p>
		<p class="text-primary text-24 line-height-1 mb-2"><?php echo $data['att_enquiry'] ?></p>
		</div>
	</a>
	</div>
	</div>
	</div> -->

</div>

<div class="row dboxes mb-4">

<div class="col-lg-8 col-md-8 col-sm-12 prod_gallery">
	<h3 class="">Product Gallery</h3>

	<?php
    $images = json_decode($product['image'],true);
    if(is_array($images) && !empty($images)){

        echo '<div class="carousel_wrap dashboard_carousel">
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
    }

    ?>
</div>

<div class="col-lg-4 col-md-4 col-sm-12 pending_feedback">

	<h3 class="pend_feed_title">Pending Feedback <span class="f_count"> <?php echo count($data['pending_feedbacks']); ?></span></h3>

	
	<?php 

	if($data['pending_feedbacks']){

		echo '<div class="pending_content">';
		
		foreach ($data['pending_feedbacks'] as $key => $value) { ?>
			<div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
				<div class="card-body text-left">
					<a href="<?php echo site_url('customer/complaint/'.$value['id']);?>">
						<div class="content1">
							<p class="text-muted mt-2 mb-0">Ticket #<?php echo $value['ticket_no'];?> </p>
							<span>Closed Date: <?php echo $value['closed_date'];?></span>
						</div>
					</a>
				</div>
			</div>
	<?php
		}

		echo '</div>';
	}

	?>
	

	
</div>

</div>

<div class="row">
	<div class="col-lg-12 col-md-12">
		<div class="card mb-4">
		<div class="card-body">
		<div class="card-title">This Year Tickets <a href="<?php echo site_url('customer/complaint'); ?>" class="btn btn-primary float-right">View All</a></div>
		<div id="echartBar" style="height: 300px;"></div>
		</div>
		</div>
	</div>
	<div class="col-lg-12 col-sm-12 d-none">
		<div class="card mb-4">
		<div class="card-body">
		<div class="card-title">Sales by Countries</div>
		<div id="echartPie" style="height: 300px;"></div>
		</div>
		</div>
	</div>
</div>




<?php $this->load->view('common/footer');  ?>

<?php
$month_data = array_values($data['complaint_data']);
$month_keys = array_keys($data['complaint_data']);
?>

<script src="<?php echo base_url('assets/dist-assets/js/plugins/echarts.min.js') ?>"></script>
<script src="<?php echo base_url('assets/dist-assets/js/scripts/echart.options.min.js') ?>"></script>

<script src="<?php echo base_url('assets/marquee3k.js') ?>"></script>


<script type="text/javascript">
	var month_data = <?php echo json_encode($month_data );?>;
	var month_keys = <?php echo json_encode($month_keys );?>;
	var month_max_complaint = <?php echo max($month_data);?>;

	if(month_max_complaint < 5){
		var g_interval = month_max_complaint;
	}else{
		var g_interval = Math.round(month_max_complaint/5);
	}
	
	var g_max = g_interval*7;
    console.log(g_interval);

	$(document).ready(function () {

		Marquee3k.init();

		$('.marquee3k').css('opacity','1');

		var echartElemBar = document.getElementById("echartBar");

		if (echartElemBar) {
		    var echartBar = echarts.init(echartElemBar);
		    echartBar.setOption({
		      legend: {
		        borderRadius: 0,
		        orient: "horizontal",
		        x: "right",
		        data: ["Tickets"],
		      },
		      grid: {
		        left: "8px",
		        right: "8px",
		        bottom: "0",
		        containLabel: true,
		      },
		      tooltip: {
		        show: true,
		        backgroundColor: "rgba(0, 0, 0, .8)",
		      },
		      xAxis: [
		        {
		          type: "category",
		          data: month_keys,
		          axisTick: {
		            alignWithLabel: true,
		          },
		          splitLine: {
		            show: false,
		          },
		          axisLine: {
		            show: true,
		          },
		        },
		      ],
		      yAxis: [
		        {
		          type: "value",
		          axisLabel: {
		            formatter: "{value}",
		          },
		          min: 0,
		          max: g_max,
		          interval: g_interval,
		          axisLine: {
		            show: false,
		          },
		          splitLine: {
		            show: true,
		            interval: "auto",
		          },
		        },
		      ],
		      series: [
		        {
		          name: "Tickets",
		          data: month_data,
		          label: {
		            show: false,
		            color: "#0168c1",
		          },
		          type: "bar",

		          stack : 'nilesh',

		          barGap: 0,
		          color: "#A78BFA",
		          smooth: true,
		          itemStyle: {
		            emphasis: {
		              shadowBlur: 10,
		              shadowOffsetX: 0,
		              shadowOffsetY: -2,
		              shadowColor: "rgba(0, 0, 0, 0.3)",
		            },
		          },
		        },

		      ],
		    });
		    $(window).on("resize", function () {
		      setTimeout(function () {
		        echartBar.resize();
		      }, 500);
		    });
		  } // Chart in Dashboard version 1

	});
</script>