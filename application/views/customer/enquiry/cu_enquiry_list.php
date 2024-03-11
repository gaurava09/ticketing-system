<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?php echo base_url('assets/dist-assets/css/plugins/datatables.min.css') ?>" />
<link href="<?php echo base_url('assets/libs/jquery-ui/jquery-ui.min.css'); ?>" rel="stylesheet" type="text/css">

    
<div class="">
<div class="float-left breadcrumb"><h1 class="mr-2">All Enquiry</h1></div>
<div class="float-right"><a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo site_url('customer/enquiry/create'); ?>"><i class="i-Add-File"></i> Add</a></div>
<div class="clearfix"></div>
</div>


<div class="separator-breadcrumb border-top"></div>



<?php $this->load->view('common/flashmsg'); ?>
<?php $this->load->view('common/ajaxerror'); ?>



<div class="row mb-12 search_div">
	<div class="col-md-3 mb-3"><input class="form-control" id="enquiry_no" type="text" placeholder="Enquiry No."></div>
	<div class="col-md-3 mb-3"><input class="form-control" id="ga_no" type="text" placeholder="GA No."></div>

	<div class="col-md-3 mb-3">

	<select class="form-control" id="status" >
    	<option value="">Select Status</option>
    	<?php
			$url_status = (isset($_GET['status'])) ? $_GET['status'] :'';
	        $status_list = enquiry_status_list();
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
	<th scope="col">Enquiry No.</th>
	<th scope="col">GA Number</th>
	<th scope="col">Spareparts</th>
	<th scope="col">Status</th>
	<th scope="col">Created Date</th>
	<th scope="col">Action</th>
</tr>
</thead>
<tbody>
   
</tbody>
</table>
</div>
</div>
</div>
</div>

</div>



<?php $this->load->view('common/footer');  ?>
<script src="<?php echo base_url('assets/dist-assets/js/plugins/datatables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/custom.js'); ?>"></script>

<script type="text/javascript">

  $(document).ready(function(){

    	var table =  $('#zero_configuration_table').DataTable({
	            'processing': true,
	            "oLanguage": {'sProcessing': '<div class="dt_spinner"> <span class="spinner spinner-primary mr-3"></span></div>'},
		        "stripeClasses": [],
		        "lengthMenu": [10, 25, 75, 100,200],
		        "pageLength": 10,
		        "sDom": 'lrtip',
		        "bInfo":true,
		        "searching": true,
		        "ordering": false,
	         	"columnDefs": [{
	              	"defaultContent": "-",
	              	"targets": "_all"
	            }], 
	            serverSide: true,

			     ajax: {
			        url: "<?php echo site_url('customer/enquiry/list') ?>",
			        // dataSrc :'data',
			        dataFilter: function(data){
			            var json = jQuery.parseJSON( data );
			            json.recordsTotal = json.data.totalRecords;
			            json.recordsFiltered = json.data.totalRecordwithFilter;
			            json.data = json.data.aaData;
			            return JSON.stringify( json ); 
			        },
			        data: function ( d ) {
				        return $.extend( {}, d, {
				           "ga_no": $("#ga_no").val().toLowerCase(),
				           "enquiry_no": $("#enquiry_no").val(),
				           "status": $("#status").val(),
				        } );
				    }
			    },

	            columns: [
	                    {
					      "render": function(data, type, full, meta) {
					      	return meta.row + meta.settings._iDisplayStart + 1;
					      }
					    },
					    { data: 'enquiry_no' },
	                    { data: 'ga_no' },
	                    { data: 'spareparts' },
	                    {
					      "render": function(data, type, full, meta) {
					        switch (full.status) {
			    				case '2': $status ='Open'; $class = 'badge-info '; break; //open
			    				case '3': $status ='Ongoing'; $class = 'badge-warning '; break; //ongoing
			    				case '4': $status ='Closed'; $class = 'badge-success '; break;//closed
			    				default: $status =''; $class = ''; break;
			    			}

					        $html = '<span class="badge '+$class+'">'+$status+'</span>';
					        return $html;
					      }
					    },
	                    { data: 'created_at' },
	                    {
					      "render": function(data, type, full, meta) {
					        $html = '<a class="text-success mr-2 text-18" href="<?php echo site_url() ?>customer/enquiry/'+full.id+'" data-toggle="tooltip" data-placement="top" title="View"><i class="nav-icon i-Eye font-weight-bold"></i></a>';
							return $html;
					      }
					    },
                 ],
                 "drawCallback": function(settings) {
				   stopLoading($('#search_filter'));
				},
        });   //end

        $('#search_filter').click(function(){
    		showLoading($(this));
    		table.draw();
    	});


    }); //end doc ready
</script>