<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$status_list = enquiry_status_list();
?>


<div class="">
<div class="float-left breadcrumb"><h1 class="mr-2">Enquiry View</h1></div>
<div class="float-right"><a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo site_url('customer/enquiry'); ?>"><i class="i-Left-3"></i> Back</a></div>
<div class="clearfix"></div>
</div>

<div class="separator-breadcrumb border-top"></div>

<?php $this->load->view('common/flashmsg'); ?>
<?php $this->load->view('common/ajaxerror'); ?>



<div class="row">
<div class="col-md-12">
<div class="card mb-4">

<div class="modal-body">
<div class="card-body">



<div class="form-group">

<div class="row">

<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Enquiry No.:</b></p> </div>
<div class="col-md-8"><p><?php echo cap(ps($enquiry,'enquiry_no')); ?></p></div>
</div>
</div>


<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>GA No.:</b></p> </div>
<div class="col-md-8"><p><?php echo ps($enquiry,'ga_no'); ?></p></div>
</div>
</div>


<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Equipment:</b></p> </div>  
<div class="col-md-8"><p><?php echo cap(ps($project,'equipment_name')); ?></p></div>
</div>
</div>


<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Equipment Model:</b></p> </div>  
<div class="col-md-8"><p><?php echo ps($enquiry,'model'); ?></p></div>
</div>
</div>

<?php  if(is_array($spareparts) && !empty($spareparts)){ ?>
<div class="col-md-12">   
<div class="row">
<div class="col-md-2"><p><b>Spareparts:</b></p> </div>  
<div class="col-md-10">

    <table class="enq_sparepart_table">
        <thead>
            <tr>
                <th>Sr No.</th>
                <th>Name of Spare parts</th>
                <th>Measurement Unit</th>
                <th>Quantity</th>
            </tr>
        </thead>
        
        <tbody>

		<?php 
			if(is_array($spareparts)){
				foreach ($spareparts as $key => $value) {
					$unit = isset($value['unit']) ? $value['unit'] : '';
					
					if(isset($value['name'])){
						echo '<tr>';
						echo '<td>'.($key+1).'</td>';
						echo '<td>'.cap($value['name']).'</td>';
						echo '<td>'.cap($unit).'</td>';
						echo '<td>'.$value['qty'].'</td>';
						echo '</tr>';

						// echo '<span>'.cap($value['name']).' ['.$value['qty'].']</span> <br>';
					}
				}
			}else{
				echo $spareparts;
			}
		?>
		</tbody>
    </table>
	
</div>
</div>
</div>

<?php  } ?>
<?php  if($enquiry['query'] != ''){ ?>

<div class="col-md-12 ">   
<div class="row">
<div class="col-md-2"><p><b>Additional spare:</b></p> </div>  
<div class="col-md-10"><p><?php echo ps($enquiry,'query'); ?></p></div>
</div>
</div>
<?php  } ?>

<?php /*  if($enquiry['remark'] != ''){ ?>
<div class="col-md-12">   
<div class="row">
<div class="col-md-2"><p><b>Admin Remark:</b></p> </div>  
<div class="col-md-10"><p><?php echo $enquiry['remark']; ?></p></div>
</div>
</div>
<?php  } ?>

<?php  if($enquiry['document'] != ''){ ?>
<div class="col-md-12">   
<div class="row">
<div class="col-md-2"><p><b>Attachment:</b></p> </div>  
<div class="col-md-10">
<?php
echo '<a href="'.base_url($enquiry['document']).'" class="single_download" download>Download Attachment <i class="i-Download" aria-hidden="true"> </i></a> ';
?></div>
</div>
</div>
<?php  } */ ?>

<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Status:</b></p> </div>  
<div class="col-md-8"><p><?php echo ps($status_list,$enquiry['status']); ?></p></div>
</div>
</div>

<div class="col-md-6">  
<div class="row">
<div class="col-md-4"><p><b>Enquiry Date: </b></p></div> 
<div class="col-md-8"><p><?php echo custDate($enquiry['created_at']); ?></p></div>
</div>
</div>


</div><!--  end row -->




<!-- START Enquiry History -->
<?php
  if($history){
      foreach ($history as $key => $value) {
          if($value['status']){
            continue;
          }
          echo '<div class="row">
            <div class="col-md-12 mt-3"><div class="separator-breadcrumb border-top"></div></div>
          </div>';

          echo '<div class="row">';
         if($value['remark']){
              echo '<div class="col-md-12">   
                    <div class="row">

                      <div class="col-md-8">
                      <p><b>Remark By Sales Dept.</b></p> </div>  
                        <div class="col-md-4 text-right">
                          <p>('.custDate($value['created_at']).')</p>
                        </div>
                    </div>
                    </div>

                    <div class="col-md-12">   
                    <div class="row">
                      <div class="col-md-4"><p><b>Remark:</b></p> </div>  
                      <div class="col-md-8 text-capitalize"><p>'.$value['remark'].'</p></div>
                    </div>
                    </div>

                  ';
         }
         if($value['document']){
              echo '<div class="col-md-12">   
                    <div class="row">
                    <div class="col-md-4">
                    <p><b>Attachment:</b></p> </div>  
                    <div class="col-md-8"><a href="'.base_url($value['document']).'" class="single_download" download>Download Attachment <i class="i-Download" aria-hidden="true"> </i></a></p></div>
                    </div>
                </div>';
         }
         echo '</div>';


      } //end foreach
  }//end if history
?>

<!-- END Enquiry History -->



</div>
</div>
</div><!-- modal body -->
</div>
</div>
</div> <!-- main row -->





<?php $this->load->view('common/footer');  ?>

