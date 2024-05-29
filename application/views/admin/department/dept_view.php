<?php
defined('BASEPATH') OR exit('No direct script access allowed');


// if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '') {
//   	$back_url = $_SERVER['HTTP_REFERER'];
// }else{
	$back_url = site_url('department');
// }
?>


<div class="">
	<div class="float-left breadcrumb"><h1 class="mr-2">Department View</h1></div>
	<div class="float-right">
	<a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo $back_url; ?>"><i class="i-Left-3"></i> Back</a>
	<a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo site_url('department/'.$data['id'].'/edit'); ?>"><i class="i-File-Edit"></i> Edit</a>
	</div>
	<div class="clearfix"></div>
</div>



<div class="separator-breadcrumb border-top"></div>


<?php $this->load->view('common/flashmsg'); ?>


<div class="form-group">

<div class="row">

<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Name:</b></p> </div>  
<div class="col-md-8"><p><?php echo $data['name']; ?></p></div>
</div>
</div>

<!-- <div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Top Department:</b></p> </div>  
<div class="col-md-8"><p><?php echo ( $data['top_dept'] == 1) ? 'Yes' : 'No'; ?></p></div>
</div>
</div> -->

<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Status:</b></p> </div>  
<div class="col-md-8"><p><?php echo ( $data['status'] == 1) ? 'Active' : 'De-active'; ?></p></div>
</div>
</div>


<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Created At:</b></p> </div>  
<div class="col-md-8"><p><?php echo custDate($data['created_at']); ?></p></div>
</div>
</div>
</div>

</div>


<?php $this->load->view('common/footer');  ?>
<script src="<?php echo base_url('assets/dist-assets/js/plugins/datatables.min.js') ?>"></script>

<script src="<?php echo base_url('assets/custom.js'); ?>"></script>


<script type="text/javascript">

    $(document).ready(function(){

    	// $dataList = $('#zero_configuration_table').DataTable();
    	var i = 1;
    	var table =  $('#zero_configuration_table').DataTable({
	            'processing': true,
	             "oLanguage": {
	            		'sProcessing': ' <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
	        		},
	        "stripeClasses": [],
	        "lengthMenu": [10, 25, 75, 100,200],
	        "pageLength": 10,
	        "sDom": 'lrtip',
	        "bInfo":true,
	         "columnDefs": [{
	              "defaultContent": "-",
	              "targets": "_all"
	            }], 
	           ajax: "<?php echo site_url('department/list') ?>",
	           columns: [
	                    {
					      "render": function(data, type, full, meta) {
					        return i++;
					      }
					    },
	                    { data: 'name' },
	                    { data: 'top_dept' },
	                    { data: 'status'},
	                    { data: 'created_at' },
	                    {
					      "render": function(data, type, full, meta) {
					        console.log(full.name);
					        $html = '<a class="text-success mr-2 text-18" href="<?php echo site_url('department/view/') ?>'+full.id+'" data-toggle="tooltip" data-placement="top" title="View"><i class="nav-icon i-Eye font-weight-bold"></i></a>';

							$html += '<a class="text-success mr-2 text-18" href="<?php echo site_url('department/edit/') ?>'+full.id+'" data-toggle="tooltip" data-placement="top" title="Edit"><i class="nav-icon i-File-Edit"></i></a>';

							$html += '<a class="text-danger mr-2  text-18" href="#" data-toggle="tooltip" data-placement="top" title="Delete" ><i class="nav-icon i-Close-Window font-weight-bold"></i></a>';

							return $html;
					      }
					    },
                 ]
        });   

    });
</script>