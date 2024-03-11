<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?php echo base_url('assets/dist-assets/css/plugins/datatables.min.css') ?>" />
<link href="<?php echo base_url('assets/libs/jquery-ui/jquery-ui.min.css'); ?>" rel="stylesheet" type="text/css">

    
<div class="">
	<div class="float-left breadcrumb"><h1 class="mr-2">All Feedback</h1></div>
	<div class="float-right">
		<a class="btn btn-primary export" type="button" href="<?php echo site_url('feedback/export'); ?>">Export</a>
	</div>

<div class="clearfix"></div>
</div>


<div class="separator-breadcrumb border-top"></div>



<?php $this->load->view('common/flashmsg'); ?>
<?php $this->load->view('common/ajaxerror'); ?>



<div class="row mb-12 filter-div search_div">
	<div class="col-md-3 mb-3"><input class="form-control" id="ticket_no" type="text" placeholder="Ticket No."></div>
	<div class="col-md-3 mb-3"><input class="form-control" id="ga_no" type="text" placeholder="GA No."></div>

	<div class="col-md-3 mb-3">
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
 	<select class="form-control" id="classification" >
    	<option value="">Select Classification</option>
        <?php
        	$classifications = classifications();
        	foreach ($classifications as $key => $value) {
        		echo '<option value="'.$key.'">'.$key.' - '.$value.'</option>';
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
<th scope="col">Ticket No.</th>
<th scope="col">GA Number</th>
<th scope="col">Complaint Type</th>
<th scope="col">Classification</th>
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
  		$('.filter-div input').val('');

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
			        url: "<?php echo site_url('feedback/list') ?>",
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
				           "ga_no": 	$("#ga_no").val().toLowerCase(),
				           "ticket_no": $("#ticket_no").val().toLowerCase(),
				           "complaint_type": $('#complaint_type').find(':selected').val(),
				           "classification": $("#classification").val(),
				        } );
				    }
			    },

	            columns: [
	                    {
					      "render": function(data, type, full, meta) {
					      	return meta.row + meta.settings._iDisplayStart + 1;
					      }
					    },
	                    { data: 'ticket_no' },
	                    { data: 'ga_no' },
	                    { data: 'complaint_type' },
	                    { data: 'classification' },
	                    { data: 'created_at' },
	                    {
					      "render": function(data, type, full, meta) {
					        $html = '<a class="text-success mr-2 text-18" href="<?php echo site_url() ?>feedback/'+full.complaint_id+'" data-toggle="tooltip" data-placement="top" title="View"><i class="nav-icon i-Eye font-weight-bold"></i></a>';

					        $html += '<a class="text-success mr-2 text-18" href="<?php echo site_url('feedback/pdf/'); ?>'+full.complaint_id+'" data-toggle="tooltip" data-placement="top" title="Download PDF"><i class="nav-icon i-Download font-weight-bold"></i></a>';
							return $html;
					      }
					    },
                 ]
        });   


    	$('#search_filter').click(function(){
    		table.draw();
    	});

    	$('.export').click(function(e){
    		e.preventDefault();
    		$url = $(this).attr('href');
    		var params = { 
    			ga_no : $("#ga_no").val().toLowerCase(), 
    			ticket_no : $("#ticket_no").val().toLowerCase(),
    			complaint_type : $('#complaint_type').find(':selected').val(),
    			classification : $("#classification").val(),
    		};

    		$url = $url+'?'+$.param( params );
    		window.location.href= $url;
    	});
    });
</script>