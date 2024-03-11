<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<div class="">
	<div class="float-left breadcrumb"><h1 class="mr-2">Dashboard </h1></div>
	
	<div class="clearfix"></div>
</div>


<div class="separator-breadcrumb border-top"></div>
<div class="row dboxes">
<!-- ICON BG-->



<div class="col-lg-3 col-md-6 col-sm-6">
<div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
<div class="card-body text-center">
	<a href="<?php echo site_url('enquiry');?>?status=2">
	<div class="content">
	<p class="text-muted mt-2 mb-0">Open Spares Enquiry</p>
	<p class="text-primary text-24 line-height-1 mb-2"><?php echo $data['open_enquiry'] ?></p>
	</div>
</a>
</div>
</div>
</div>

<div class="col-lg-3 col-md-6 col-sm-6">
<div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
<div class="card-body text-center">
	<a href="<?php echo site_url('enquiry');?>?status=4">
	<div class="content">
	<p class="text-muted mt-2 mb-0">Attended Spares Enquiry</p>
	<p class="text-primary text-24 line-height-1 mb-2"><?php echo $data['att_enquiry'] ?></p>
	</div>
</a>
</div>
</div>
</div>



</div>



<div class="row">
	<div class="col-lg-12 col-md-12">
		<div class="card mb-4">
		<div class="card-body">
		<div class="card-title">This Year Enquiries 
			<a href="<?php echo site_url('enquiry'); ?>" class="btn btn-primary float-right">View All</a></div>
		<div id="echartBar" style="height: 300px;"></div>
		</div>
		</div>
	</div>
</div>



<div class="row">

<!-- start Latest Enquiry -->
    <div class="col-lg-6 col-md-6 col-sm-12">
      <div class="card o-hidden mb-4">
         <div class="card-header d-flex align-items-center border-0">
            <h3 class="w-50 float-left card-title m-0">Spares Enquiry</h3>
            <div class="dropdown dropleft text-right w-50 float-right">
            	<a href="<?php echo site_url('enquiry'); ?>" class="btn btn-primary">View All</a>

               	<!-- <button class="btn bg-gray-100" id="dropdownMenuButton1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="nav-icon i-Gear-2"></i></button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
               		<a class="dropdown-item" href="<?php echo site_url('enquiry'); ?>">View All Enquiries</a>
                </div> -->
            </div>
         </div>
         <div>
            <div class="table-responsive">
               <table class="table text-center" id="user_table">
                  <thead>
                     <tr>
                        <th scope="col">#</th>
                        <th scope="col">Equipment</th>
                        <th scope="col">Company</th>
                        <th scope="col">Date</th>
                        <th scope="col">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                  	<?php
                  	foreach ($data['enquiries'] as $key => $value) {

                  		echo '<tr>
	                        <th scope="row">'.($key+1).'</th>
	                        <td>'.cap($value['equipment']).'</td>
	                        <td>'.cap($value['company']).'</td>
	                        <td>'.cap($value['created_at']).'</td>
	                        <td><a class="text-success mr-2 text-18" href="'.site_url('enquiry/'.$value['id']).'" data-toggle="tooltip" data-placement="top" title="View"><i class="nav-icon i-Eye font-weight-bold"></i></a></td>
	                    </tr>';
                  	}
                  	?>                     
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
	<!-- end Latest Enquiry -->

</div>


<?php $this->load->view('common/footer');  ?>

<?php

$month_data = array_values($data['enquiry_data']);
$month_keys = array_keys($data['enquiry_data']);

// d($js_month_data);
?>

<script src="<?php echo base_url('assets/dist-assets/js/plugins/echarts.min.js') ?>"></script>


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
		var echartElemBar = document.getElementById("echartBar");

		if (echartElemBar) {
		    var echartBar = echarts.init(echartElemBar);
		    echartBar.setOption({
		      legend: {
		        borderRadius: 0,
		        orient: "horizontal",
		        x: "right",
		        data: ["Enquiry"],
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
		/*        {
		          name: "Offline",
		          data: month_data,
		          label: {
		            show: false,
		            color: "#639",
		          },
		          type: "bar",
		          stack : 'nilesh',

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
		        },*/
		      ],
		    });
		    $(window).on("resize", function () {
		      setTimeout(function () {
		        echartBar.resize();
		      }, 500);
		    });
		  } // if bar char element

		
	});
</script>