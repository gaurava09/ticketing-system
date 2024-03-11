<?php
defined('BASEPATH') OR exit('No direct script access allowed');

 $status_list = status_list();
?>
<link rel="stylesheet" href="<?php echo base_url('assets/dist-assets/css/plugins/datatables.min.css') ?>" />
    

<div class="">
<div class="float-left breadcrumb"><h1 class="mr-2">FAQ Sub-Category List</h1></div>
<div class="float-right"><a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo site_url('faq/subcategory/create'); ?>"><i class="i-Add-File"></i> Add</a></div>
<div class="clearfix"></div>
</div>

<div class="separator-breadcrumb border-top"></div>



<?php $this->load->view('common/flashmsg'); ?>



<!--row-->
<div class="row mb-12">
<div class="col-md-12 mb-3">
<div class=""><div class="">
<div class="table-responsive">
<table  class="table table-striped" id="zero_configuration_table" style="width:100%">
<thead>
<tr>
<th scope="col">Sr. No.</th>
<th scope="col">Name</th>
<th scope="col">Category</th>
<th scope="col">Status</th>
<th scope="col">Created Date</th>
<th scope="col">Action</th>
</tr>
</thead>
<tbody>
    <?php
    	if($subcategory){
    		foreach ($subcategory as $key => $value) {
    			echo '<tr>
					<td scope="row">'.($key+1).'</td>
					<td>'.$value['title'].'</td>

                    <td>'.ps($category,$value['parent']).'</td>
					
					<td><span class="badge badge-info">'.$status_list[$value['status']].'</span></td>
					<td>'.custDate($value['created_at']).'</td>
					<td><a class="text-success mr-2" href="'.site_url('faq/subcategory/'.$value['id'].'/edit').'"><i class="nav-icon i-File-Edit font-weight-bold"></i></a></td>
					</tr>';
    		}
    	}
    ?>
    
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
    	$dataList = $('#zero_configuration_table').DataTable();

    });
</script>