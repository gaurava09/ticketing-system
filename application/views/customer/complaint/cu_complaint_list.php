<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?php echo base_url('assets/dist-assets/css/plugins/datatables.min.css') ?>" />
<link href="<?php echo base_url('assets/libs/jquery-ui/jquery-ui.min.css'); ?>" rel="stylesheet" type="text/css">

    
<div class="">
<div class="float-left breadcrumb"><h1 class="mr-2">All Complaint</h1></div>
<div class="float-right"><a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo site_url('customer/complaint/create'); ?>"><i class="i-Add-File"></i> Add</a></div>
<div class="clearfix"></div>
</div>

<div class="separator-breadcrumb border-top"></div>


<?php $this->load->view('common/flashmsg'); ?>
<?php $this->load->view('common/ajaxerror'); ?>


<div class="row mb-12 search_div">


<!-- <div class="col-md-3 mb-3"><input class="form-control" id=" " type="text" placeholder="Company Name"></div> -->
<div class="col-md-3 mb-3"><input class="form-control" id="ticket_no" type="text" placeholder="Ticket No."></div>
<!-- <div class="col-md-3 mb-3"><input class="form-control" id="ga_no" type="text" placeholder="GA Number"></div> -->

<!-- <div class="col-md-3 mb-3"><input class="form-control" id="cust_equi_no" type="text" placeholder="Customer equipment No."></div> -->

<div class="col-md-3 mb-3">
	<!-- <input class="form-control" id="complaint_type" type="text" placeholder="Slect Complaint Type"> -->
 	<select class="form-control" id="complaint_type" >
    	<option value="">Select Complaint Type</option>
        <?php
        $complaint_types = complaint_types();
        	foreach ($complaint_types as $key => $value) {
        		echo '<option value="'.$key.'">'.$value.'</option>';
        	}
        ?>
    </select>
</div>

<div class="col-md-3 mb-3">
	<select class="form-control" id="status">
		<option value="">Select Status</option>
		<?php
		$url_status = (isset($_GET['status'])) ? $_GET['status'] :'';
        $status_list = complaint_status_list();
        	foreach ($status_list as $key => $value) {
        		if($key == $url_status && $url_status != ''){
        			echo '<option value="'.$key.'" selected>'.$value.'</option>';
        		}else{
	        		echo '<option value="'.$key.'">'.$value.'</option>';
        		}
        	}
        ?>
	</select>
</div>
<!-- <div class="col-md-3 mb-3"><input class="form-control" id="stau" type="text" placeholder="Select Status"></div> -->
<div class="col-md-12"><button class="btn btn-primary float-right" id="search_filter">Search</button></div>

</div>


<!--row-->
<div class="row mb-12">
<div class="col-md-12 mb-3">
<div class=""><div class="">
<div class="table-responsive">
<table  class="table table-striped" id="zero_configuration_table" style="width:100%">
<thead>
<tr>
<th scope="col">Sr. No.</th>
<th scope="col">Ticket No.</th>
<!-- <th scope="col">GA Number</th> -->
<th scope="col">Complaint Type</th>
<th scope="col">Created Date</th>
<th scope="col">Status</th>
<th scope="col">Action</th>
</tr>
</thead>
<tbody>
    <?php
    	if($data){
    		foreach ($data as $key => $value) {
    			switch ($value['status']) {
    				case '0': $class = 'badge-danger '; break; //deleted
    				case '1': $class = 'badge-success '; break; //completed
    				case '2': $class = 'badge-info '; break; //open
    				case '3': $class = 'badge-warning '; break; //ongoing
    				case '4': $class = 'badge-success '; break;//closed
    				default: $class = ''; break;
    			}
    			echo '<tr>
					<th scope="row">'.($key+1).'</th>
					<td>'.$value['ticket_no'].'</td>
					
					<td>'.$complaint_types[$value['complaint_type']].'</td>
					<td>'.custDate($value['created_at']).'</td>
					<td><span class="badge '.$class.'">'.$status_list[$value['status']].'</span></td>
					<td><a class="text-success mr-2" href="'.site_url('customer/complaint/'.$value['id']).'"><i class="nav-icon i-Eye font-weight-bold"></i></a></td>
					</tr>';
    		}
    	}

    ?>
<!-- <tr>
<th scope="row"><input class="" id="gridCheck1" type="checkbox"> 1</th>
<td>MS0001</td>
<td>SA1102</td>
<td>Jan 29th 19</td>
<td><span class="badge badge-info">Active</span></td>
<td><a class="text-success mr-2" href="complaint_view.html"><i class="nav-icon i-Eye font-weight-bold"></i></a><a class="text-danger mr-2" href="#"><i class="nav-icon i-Close-Window font-weight-bold"></i></a></td>
</tr> -->
    
</tbody>
</table>
</div>
</div>
</div>
</div>

</div>




<?php $this->load->view('common/footer');  ?>
<script src="<?php echo base_url('assets/dist-assets/js/plugins/datatables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/libs/jquery-ui/jquery-ui.min.js') ?>"></script>
<script src="<?php echo base_url('assets/custom.js'); ?>"></script>

<script type="text/javascript">
	var status_list =<?php echo json_encode($status_list );?>;
	var ga_no_list =<?php echo json_encode($projects );?>;

	$( "#ga_no" ).autocomplete({
      		source: ga_no_list,
      		 focus: function( event, ui ) {
                  $( "#ga_no" ).val( ui.item.value );
                    return false;
               },
      		select: function( event, ui ) {
      			console.log(ui.item);
      			$( "#ga_no" ).val( ui.item.value );
                return false;
            }
    });

    $(document).ready(function(){
    	
    	$dataList = $('#zero_configuration_table').DataTable();


    	$('#search_filter').click(function () {

	        var postdata = {};
	        var params = '';

	        //$ga_no = $.trim($('#ga_no').val());
	        $ticket_no = $.trim($('#ticket_no').val());
	        $cust_equi_no = $.trim($('#cust_equi_no').val());
	        $type = $.trim($('#complaint_type').find(':selected').val());
	        $status = $.trim($('#status').find(':selected').val());

	        // if($ga_no){
	        //   postdata.ga_no = $ga_no;
	        //   params += 'ga_no='+$ga_no+'&';
	        // }
	        if($ticket_no){
	          postdata.ticket_no = $ticket_no;
	          params += 'ticket_no='+$ticket_no+'&';
	        }
	        if($cust_equi_no){
	          postdata.cust_equi_no = $cust_equi_no;
	          params += 'cust_equi_no='+$cust_equi_no+'&';
	        }
	        if($type){
	          postdata.type = $type;
	          params += 'type='+$type+'&';
	        }
	        if($status){
	          postdata.status = $status;
	          params += 'status='+$status+'&';
	        }

	        if(params){
	          params = '?'+params;
	        }

	        $cache_url = '<?php echo site_url('customer/complaint/');?>'+params;

	        showLoading($(this));

	        $.ajax({
	            type: 'GET',
	            url: '<?php echo site_url('customer/complaint/search'); ?>',
	            data: postdata,

	            success: function($res) {

	                if($res.status == 1){
	                    $dataList.clear().draw();

	                    $.each($res.data.list, function(i,v){

	                    	switch (v.status) {
			    				case '0': $class = 'badge-danger '; break; //deleted
			    				case '1': $class = 'badge-success '; break; //completed
			    				case '2': $class = 'badge-info '; break; //open
			    				case '3': $class = 'badge-warning '; break; //ongoing
			    				case '4': $class = 'badge-success '; break;//closed
			    				default: $class = ''; break;
			    			}

	                        $actionHtml = '<a class="text-success mr-2" href="<?php echo site_url('customer/complaint/') ?>'+v.id+'"><i class="nav-icon i-Eye font-weight-bold"></i></a>';

	                        $statusHtml = '<span class="badge '+$class+'">'+status_list[v.status]+'</span>';


	                        $dataList.row.add( [
	                            (i+1),
	                            v.ticket_no,
	                            //v.ga_no,
	                            v.complaint_type,
	                            v.created_at,
	                            $statusHtml,
	                            $actionHtml,
	                        ] ).draw( true );
	                    });
	                    
	                    // window.history.pushState({"page":"cust_complaint"},"", $cache_url);
	                }
	                else{
	                	$('#danger-alert-modal .msg').text($res.message)
	                	$('#danger-alert-modal').modal();
	                }
	                stopLoading()
	            },
	            error: function(error, textStatus, errorMessage) {
	                $('#danger-alert-modal .msg').text('Some error occured.')
	                $('#danger-alert-modal').modal();

	                $button.removeClass('process');
	                Ladda.stopAll();
	            }  

	        }); //end ajax

	    });//end on change

	    if($('#status').val()){
    		$('#search_filter').trigger('click');
    	}

    });
</script>