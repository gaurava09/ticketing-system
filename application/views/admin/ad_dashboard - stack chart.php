<style>
	.card-icon-bg .card-body .content {
    max-width: 100%;
}
</style>

<div class="breadcrumb">
<ul>
<li><a href="">Dashboard</a></li>
</ul>
</div>
<div class="separator-breadcrumb border-top"></div>
<div class="row">
<!-- ICON BG-->
<div class="col-lg-3 col-md-6 col-sm-6">
<div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
<div class="card-body text-center">
<div class="content">
<p class="text-muted mt-2 mb-0">Total Enquiry</p>
<p class="text-primary text-24 line-height-1 mb-2"><?php echo $data['total_enquiry'] ?></p>
</div>
</div>
</div>
</div>

<div class="col-lg-3 col-md-6 col-sm-6">
<div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
<div class="card-body text-center">
<div class="content">
<p class="text-muted mt-2 mb-0">Open Ticket</p>
<p class="text-primary text-24 line-height-1 mb-2"><?php echo $data['open_ticket'] ?></p>
</div>
</div>
</div>
</div>

<div class="col-lg-3 col-md-6 col-sm-6">
<div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
<div class="card-body text-center">
<div class="content">
<p class="text-muted mt-2 mb-0">Ongoing</p>
<p class="text-primary text-24 line-height-1 mb-2"><?php echo $data['ongoing_ticket'] ?></p>
</div>
</div>
</div>
</div>

<div class="col-lg-3 col-md-6 col-sm-6">
<div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
<div class="card-body text-center">
<div class="content">
<p class="text-muted mt-2 mb-0">Closed</p>
<p class="text-primary text-24 line-height-1 mb-2"><?php echo $data['closed_ticket'] ?></p>
</div>
</div>
</div>
</div>

</div>



<div class="row">
	<div class="col-lg-12 col-md-12">
		<div class="card mb-4">
		<div class="card-body">
		<div class="card-title">This Year Tickets</div>
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



<div class="row">
	<!-- start Latest Complaints -->
    <div class="col-lg-6 col-md-6 col-sm-12">
      <div class="card o-hidden mb-4">
         <div class="card-header d-flex align-items-center border-0">
            <h3 class="w-50 float-left card-title m-0">Latest Complaints</h3>
            <div class="dropdown dropleft text-right w-50 float-right">
               	<button class="btn bg-gray-100" id="dropdownMenuButton1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="nav-icon i-Gear-2"></i></button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
               		<a class="dropdown-item" href="<?php echo site_url('complaint/create'); ?>">Add New Complaint</a>
               		<a class="dropdown-item" href="<?php echo site_url('complaint'); ?>">View All Complaints</a>
                </div>
            </div>
         </div>
         <div>
            <div class="table-responsive">
               <table class="table text-center" id="user_table">
                  <thead>
                     <tr>
                        <th scope="col">#</th>
                        <th scope="col">Ticket No.</th>
                        <th scope="col">GA No.</th>
                        <th scope="col">Company</th>
                        <th scope="col">Date</th>
                        <th scope="col">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                  	<?php
                  	foreach ($data['latest_ticket'] as $key => $value) {

                  		echo '<tr>
	                        <th scope="row">'.($key+1).'</th>
	                        <td>'.$value['ticket_no'].'</td>
	                        <td>'.$value['ga_no'].'</td>
	                        <td>'.$value['company'].'</td>
	                        <td>'.$value['created_at'].'</td>
	                        <td><a class="text-success mr-2 text-18" href="'.site_url('customer/'.$value['id']).'" data-toggle="tooltip" data-placement="top" title="View"><i class="nav-icon i-Eye font-weight-bold"></i></a></td>
	                     </tr>';
                  	}
                  	?>                     
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
	<!-- end Latest Complaints -->


<!-- start Latest Enquiry -->
    <div class="col-lg-6 col-md-6 col-sm-12">
      <div class="card o-hidden mb-4">
         <div class="card-header d-flex align-items-center border-0">
            <h3 class="w-50 float-left card-title m-0">Sparepart Enquiry</h3>
            <div class="dropdown dropleft text-right w-50 float-right">
               	<button class="btn bg-gray-100" id="dropdownMenuButton1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="nav-icon i-Gear-2"></i></button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
               		<a class="dropdown-item" href="<?php echo site_url('enquiry'); ?>">View All Enquiries</a>
                </div>
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
	                        <td>'.$value['equipment'].'</td>
	                        <td>'.$value['company'].'</td>
	                        <td>'.$value['created_at'].'</td>
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


	<!-- start Latest Registered Customer -->
    <div class="col-lg-6 col-md-6 col-sm-12">
      <div class="card o-hidden mb-4">
         <div class="card-header d-flex align-items-center border-0">
            <h3 class="w-50 float-left card-title m-0">Newly Registered Customer</h3>
            <div class="dropdown dropleft text-right w-50 float-right">
               	<button class="btn bg-gray-100" id="dropdownMenuButton1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="nav-icon i-Gear-2"></i></button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
               		<a class="dropdown-item" href="<?php echo site_url('customer/create'); ?>">Add New Customer</a>
               		<a class="dropdown-item" href="<?php echo site_url('customer'); ?>">View All Customers</a>
                </div>
            </div>
         </div>
         <div>
            <div class="table-responsive">
               <table class="table text-center" id="user_table">
                  <thead>
                     <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Avatar</th>
                        <th scope="col">Email</th>
                        <th scope="col">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                  	<?php
                  	foreach ($data['customers'] as $key => $value) {

                  		$profile_picture = ($value['profile_picture'] != '') ? $value['profile_picture'] : 'assets/dist-assets/images/faces/user.jpg';

                  		echo '<tr>
	                        <th scope="row">'.($key+1).'</th>
	                        <td>'.ucfirst($value['first_name']).' '.ucfirst($value['first_name']).'</td>
	                        <td><img class="rounded-circle m-0 avatar-sm-table" src="'.base_url($profile_picture).'" alt="" /></td>
	                        <td>'.$value['email'].'</td>
	                        <td><a class="text-success mr-2 text-18" href="'.site_url('customer/'.$value['id']).'" data-toggle="tooltip" data-placement="top" title="View"><i class="nav-icon i-Eye font-weight-bold"></i></a></td>
	                     </tr>';
                  	}
                  	?>                     
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
	<!-- end Latest Registered Customer -->

</div>



<?php $this->load->view('common/footer');  ?>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>
<!-- <script src="<?php echo base_url('assets/dist-assets/js/plugins/echarts.min.js') ?>"></script> -->
<!-- <script src="<?php echo base_url('assets/dist-assets/js/scripts/echart.options.min.js') ?>"></script> -->
<!-- <script src="<?php echo base_url('assets/dist-assets/js/scripts/dashboard.v1.script.min.js') ?>"></script> -->
<!-- <script src="<?php echo base_url('assets/dist-assets/js/scripts/customizer.script.min.js') ?>"></script>  -->

<script type="text/javascript">
	$(document).ready(function () {
		var echartElemBar = document.getElementById("echartBar");

		 /* if (echartElemBar) {
		    var echartBar = echarts.init(echartElemBar);
		    echartBar.setOption({
		      legend: {
		        borderRadius: 0,
		        orient: "horizontal",
		        x: "right",
		        data: ["Online", "Offline"],
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
		          data: [
		            "Jan",
		            "Feb",
		            "Mar",
		            "Apr",
		            "May",
		            "Jun",
		            "Jul",
		            "Aug",
		            "Sept",
		            "Oct",
		            "Nov",
		            "Dec",
		          ],
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
		          max: 100000,
		          interval: 25000,
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
		          name: "Online",
		          data: [
		            35000,
		            69000,
		            22500,
		            60000,
		            50000,
		            50000,
		            30000,
		            80000,
		            70000,
		            60000,
		            20000,
		            30005,
		          ],
		          label: {
		            show: false,
		            color: "#0168c1",
		          },
		          type: "bar",

		          stack : 'nilesh',

		          barGap: 0,
		          color: "#DDD6FE",
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
		        {
		          name: "Offline",
		          data: [
		            45000,
		            82000,
		            35000,
		            93000,
		            71000,
		            89000,
		            49000,
		            91000,
		            80200,
		            86000,
		            35000,
		            40050,
		          ],
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
		        },
		      ],
		    });
		    $(window).on("resize", function () {
		      setTimeout(function () {
		        echartBar.resize();
		      }, 500);
		    });
		  } // Chart in Dashboard version 1

		  */

		var option = {
			    tooltip: {
			        trigger: 'axis',
			        axisPointer: {            // 坐标轴指示器，坐标轴触发有效
			            type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
			        }
			    },
			    legend: {
			        data: ['直接访问', '邮件营销', '联盟广告', '视频广告', 'Nilesh', '百度', '谷歌', '必应', '其他']
			    },
			    grid: {
			        left: '3%',
			        right: '4%',
			        bottom: '3%',
			        containLabel: true
			    },
			    xAxis: [
			        {
			            type: 'category',
			            data: ['A', 'b', 'c', 'd', 'e', 'f', 'g']
			        }
			    ],
			    yAxis: [
			        {
			            type: 'value'
			        }
			    ],
			    series: [
			        {
			            name: '直接访问',
			            type: 'bar',
			            emphasis: {
			                focus: 'series'
			            },
			            data: [320, 332, 301, 334, 390, 330, 320]
			        },
			        {
			            name: '邮件营销',
			            type: 'bar',
			            stack: '广告',
			            emphasis: {
			                focus: 'series'
			            },
			            data: [120, 132, 101, 134, 90, 230, 210]
			        },
			        {
			            name: '联盟广告',
			            type: 'bar',
			            stack: '广告',
			            emphasis: {
			                focus: 'series'
			            },
			            data: [220, 182, 191, 234, 290, 330, 310]
			        },
			        {
			            name: '视频广告',
			            type: 'bar',
			            stack: '广告',
			            emphasis: {
			                focus: 'series'
			            },
			            data: [150, 232, 201, 154, 190, 330, 410]
			        },
			        {
			            name: 'Nilesh',
			            type: 'bar',
			            data: [862, 1018, 964, 1026, 1679, 1600, 1570],
			            emphasis: {
			                focus: 'series'
			            },
			            markLine: {
			                lineStyle: {
			                    type: 'dashed'
			                },
			                data: [
			                    [{type: 'min'}, {type: 'max'}]
			                ]
			            }
			        },
			        {
			            name: '百度',
			            type: 'bar',
			            barWidth: 5,
			            stack: 'Nilesh',
			            emphasis: {
			                focus: 'series'
			            },
			            data: [620, 732, 701, 734, 1090, 1130, 1120]
			        },
			        {
			            name: '谷歌',
			            type: 'bar',
			            stack: 'Nilesh',
			            emphasis: {
			                focus: 'series'
			            },
			            data: [120, 132, 101, 134, 290, 230, 220]
			        },
			        {
			            name: '必应',
			            type: 'bar',
			            stack: 'Nilesh',
			            emphasis: {
			                focus: 'series'
			            },
			            data: [60, 72, 71, 74, 190, 130, 110]
			        },
			        {
			            name: '其他',
			            type: 'bar',
			            stack: 'Nilesh',
			            emphasis: {
			                focus: 'series'
			            },
			            data: [62, 82, 91, 84, 109, 110, 120]
			        }
			    ]
			};

		var echartBar = echarts.init(echartElemBar);
		echartBar.setOption(option);
	});
</script>