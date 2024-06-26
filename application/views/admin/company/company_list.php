<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?php echo base_url('assets/dist-assets/css/plugins/datatables.min.css') ?>" />
<link href="<?php echo base_url('assets/libs/jquery-ui/jquery-ui.min.css'); ?>" rel="stylesheet" type="text/css">

    

<div class="">
<div class="float-left breadcrumb"><h1 class="mr-2">All Companies</h1></div>
<div class="float-right"><a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo site_url('company/create'); ?>"><i class="i-Add-File"></i> Add</a></div>
<div class="clearfix"></div>
</div>

<div class="separator-breadcrumb border-top"></div>



<?php $this->load->view('common/flashmsg'); ?>


<div class="row mb-12 search_div">
	<div class="col-md-3 mb-3"><input class="form-control" id="name" type="text" placeholder="Company Name"></div>
	<div class="col-md-3 mb-3"><input class="form-control" id="location" type="text" placeholder="Location"></div>

	<div class="col-md-3 mb-3">
		<select class="form-control" id="status">
			<option value="">Select Status</option>
			<?php
	        $status_list = status_list();
	        	foreach ($status_list as $key => $value) {
	        		echo '<option value="'.$key.'">'.$value.'</option>';
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
<th scope="col">Company Name</th>
<!-- <th scope="col">Domain</th> -->
<th scope="col">Location</th>
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
	        "ordering": false,
	        "bInfo":true,
	         "columnDefs": [{
	              "defaultContent": "-",
	              "targets": "_all"
	            }], 
	            serverSide: true,
	            ajax: {
			        url: "<?php echo site_url('company/list') ?>",
			        // dataSrc :'data',
			        dataFilter: function(data){
			            var json = jQuery.parseJSON( data );
			            json.recordsTotal = json.data.totalRecords;
			            json.recordsFiltered = json.data.totalRecordwithFilter;

			            json.data = json.data.aaData;
			 			
			            return JSON.stringify( json ); // return JSON string
			        },
			       data: function ( d ) {
				        return $.extend( {}, d, {
				           "name": $("#name").val().toLowerCase(),
				           "location": $("#location").val().toLowerCase(),
				           "status": $("#status").val()
				        } );
				    }
			    },

	           columns: [
	                     {
					      "render": function(data, type, full, meta) {
					      	return meta.row + meta.settings._iDisplayStart + 1;
					      }
					    },

	                    { data: 'name' },
	                    //{ data: 'domain' },
	                    { data: 'location' },
	                     {
					      "render": function(data, type, full, meta) {
					        $html = '';
					        if(full.status == 0){
					        	$html += '<span class="badge badge-danger">Deactive</span>'
					        }
					        else if(full.status == 1){
					        	$html += '<span class="badge badge-success">Active</span>'
					        }
							return $html;
					      }
					    },
	                    { data: 'created_at' },
	                    {
					      "render": function(data, type, full, meta) {
					        console.log(full.name);
					        $html = '<a class="text-success mr-2 text-18" href="<?php echo site_url() ?>company/'+full.id+'" data-toggle="tooltip" data-placement="top" title="View"><i class="nav-icon i-Eye font-weight-bold"></i></a>';

							$html += '<a class="text-success mr-2 text-18" href="<?php echo site_url() ?>company/'+full.id+'/edit" data-toggle="tooltip" data-placement="top" title="Edit"><i class="nav-icon i-File-Edit"></i></a>';

							// $html += '<a class="text-danger mr-2  text-18" href="#" data-toggle="tooltip" data-placement="top" title="Delete" ><i class="nav-icon i-Close-Window font-weight-bold"></i></a>';

							return $html;
					      }
					    },
                 ],
                 "drawCallback": function(settings) {
						   stopLoading($('#search_filter'));
						}
        });   

        $('#search_filter').click(function(){
        	showLoading($(this));
    		table.draw();
    	});

    });
</script>