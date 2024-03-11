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
	<a href="<?php echo site_url('complaint');?>">
		<div class="content">
		<p class="text-muted mt-2 mb-0">Assigned Tickets</p>
		<p class="text-primary text-24 line-height-1 mb-2"><?php echo $data['assigned'] ?></p>
		</div>
	</a>
</div>
</div>
</div>



<div class="col-lg-3 col-md-6 col-sm-6">
<div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
<div class="card-body text-center">
	<a href="<?php echo site_url('complaint');?>?action=yes">
	<div class="content">
	<p class="text-muted mt-2 mb-0">Ongoing Tickets</p>
	<p class="text-primary text-24 line-height-1 mb-2"><?php echo $data['action_taken'] ?></p>
	</div>
	</a>
</div>
</div>
</div>

<div class="col-lg-3 col-md-6 col-sm-6">
<div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
<div class="card-body text-center">
	<a href="<?php echo site_url('complaint');?>?action=no">
	<div class="content">
	<p class="text-muted mt-2 mb-0">Pending Action Tickets</p>
	<p class="text-primary text-24 line-height-1 mb-2"><?php echo $data['pending_count'] ?></p>
	</div>
	</a>
</div>
</div>
</div>



<div class="col-lg-3 col-md-6 col-sm-6">
<div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
<div class="card-body text-center">
	<a href="<?php echo site_url('complaint');?>?status=4">
	<div class="content">
	<p class="text-muted mt-2 mb-0">Closed Tickets</p>
	<p class="text-primary text-24 line-height-1 mb-2"><?php echo $data['closed_ticket'] ?></p>
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
		<div class="card-title">This Year Tickets 
			<a href="<?php echo site_url('complaint'); ?>" class="btn btn-primary float-right">View All</a></div>
		<div id="echartBar" style="height: 300px;"></div>
		</div>
		</div>
	</div>
	<div class="col-lg-12 col-sm-12 ">
		<div class="card mb-4">
		<div class="card-body">
		<div class="card-title">Complaint Types</div>
		<div id="echartPie" style="height: 300px;"></div>
		</div>
		</div>
	</div>
</div>




<?php $this->load->view('common/footer');  ?>

<?php
/*$months_arr = array(
        "Jan"   => 85550,
        "Feb"   => 85550,
        "Mar"   => 85550,
        "Apr"   => 85550,
        "May"   => 85550,
        "Jun"   => 85550,
        "Jul"   => 85550,
        "Aug"   => 85550,
        "Sep"   => 85550,
        "Oct"   => 85550,
        "Nov"   => 85550,
        "Dec"   => 85550
    );*/

$month_data = array_values($data['complaint_data']);
$month_keys = array_keys($data['complaint_data']);

// d($js_month_data);
?>
<script src="<?php echo base_url('assets/dist-assets/js/plugins/echarts.min.js') ?>"></script>

<script type="text/javascript">
	var complaint_types = <?php echo json_encode($data['complaint_types']);?>;

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
		  } // Chart in Dashboard version 1

		  var echartElemPie = document.getElementById("echartPie");

	if (echartElemPie) {
	    var echartPie = echarts.init(echartElemPie);
	    echartPie.setOption({
	      color: ["#62549c", "#7566b5", "#7d6cbb", "#8877bd"],
	      tooltip: {
	        show: true,
	        backgroundColor: "rgba(0, 0, 0, .8)",
	      },
	      series: [
	        {
	          name: "Tickets",
	          type: "pie",
	          radius: "60%",
	          center: ["50%", "50%"],
	          data: complaint_types,
	          itemStyle: {
	            emphasis: {
	              shadowBlur: 10,
	              shadowOffsetX: 0,
	              shadowColor: "rgba(0, 0, 0, 0.5)",
	            },
	          },
	        },
	      ],
	    });
	    $(window).on("resize", function () {
	      setTimeout(function () {
	        echartPie.resize();
	      }, 500);
	    });
	  }//end if pie chart
		
	});
</script>