<?php

if($history){
	$complaint_status_list = complaint_status_list();
	/*$user_map = [];
	foreach ($history as $key => $value) {
		$user_map[$value['emp_id']] = $value['emp_name'];
	}*/

	
	foreach ($history as $key => $value) {
		$suffix  = '';
		if($this->userid == $value['emp_id']){
			$suffix = ' (You)';
		}

		$asn_suffix  = '';
		if($this->userid == $value['created_by']){
			$asn_suffix = ' (You)';
		}

      	if($value['type'] == 'remark'){ ?>

      	<div class="row"> <div class="col-md-12 mt-3"><div class="separator-breadcrumb border-top"></div></div> </div>

          <div class="row">

            <div class="col-md-8">   
            <div class="row">
            <div class="col-md-6"><p><b>Remark By:</b></p> </div>  
            <div class="col-md-6 text-capitalize"><p><?php echo $value['emp_name'].$suffix; ?></p></div>
            </div>
            </div>

            <div class="col-md-4">   
            <div class="row">
            <div class="col-md-4"><p><b>Date:</b></p> </div>  
            <div class="col-md-8 text-capitalize"><p><?php echo custDate($value['created_at']); ?></p></div>
            </div>
            </div>

            <?php if($value['visit_date']){ ?>
            <div class="col-md-8">   
            <div class="row">
            <div class="col-md-6"><p><b>Visit Date:</b></p> </div>  
            <div class="col-md-6 text-capitalize"><p><?php echo custDate($value['visit_date']); ?></p></div>
            </div>
            </div>
        	<?php } ?>

            <div class="col-md-12">   
            <div class="row">
            <div class="col-md-4"><p><b>Remark: </b></p> </div>  
            <div class="col-md-8"><p><?php echo $value['remark']; ?></p></div>
            </div>
            </div>

          </div>
      <?php } //end if remark

		else if($value['assigned_by'] == 'admin'){ ?>

			<div class="row">
		        <div class="col-md-12 mt-3"><div class="separator-breadcrumb border-top"></div></div>
		      </div>

		      <div class="row">

		        <div class="col-md-4">   
		          <div class="row">
		          <div class="col-md-5"><p><b>Assigned To:</b></p> </div>  
		          <div class="col-md-7 text-capitalize"><p><?php echo ps($value,'dept_name'); ?></p></div>
		          </div>
		        </div>


		        <div class="col-md-4">   
		        <div class="row">
		        <div class="col-md-4"><p><b>Employee:</b></p> </div>  
		        <div class="col-md-8 text-capitalize"><p><?php echo $value['emp_name'].$suffix; ?></p></div>
		        </div>
		        </div>

		        <div class="col-md-4">   
		        <div class="row">
		        <div class="col-md-4"><p><b>Date:</b></p> </div>  
		        <div class="col-md-8 text-capitalize"><p><?php echo custDate($value['created_at']); ?></p></div>
		        </div>
		        </div>


		        <div class="col-md-12">   
		        <div class="row">
		        <div class="col-md-4"><p><b>Remark:</b></p> </div>  
		        <div class="col-md-8 "><p><?php echo $value['remark']; ?></p></div>
		        </div>
		        </div>

		      </div>

		    <?php
		    } else if($value['assigned_by'] == 'employee'){

		    	

		    ?>
			<!-- start 2nd level assignee -->
			<div class="row">
				<div class="col-md-12 mt-3"><div class="separator-breadcrumb border-top"></div></div>
			</div>

			<div class="row">
				<div class="col-md-12 mt-4 mb-4">
				<p><b>Added respective team to resolve the complaint. Assigned By : <?php echo ps($value,'assignee_name').$asn_suffix; ?></b></p>    
				</div>
			</div>


			<div class="row">
				<div class="col-md-4">   
				<div class="row">
				<div class="col-md-4"><p><b>Department:</b></p> </div>  
				<div class="col-md-8 text-capitalize"><p><?php echo ps($value,'dept_name'); ?></p></div>
				</div>
				</div>

				<div class="col-md-4">   
				<div class="row">
				<div class="col-md-4"><p><b>Employee:</b></p> </div>  
				<div class="col-md-8 text-capitalize"><p><?php echo $value['emp_name'].$suffix; ?></p></div>
				</div>
				</div>

				<div class="col-md-4">   
			        <div class="row">
			        <div class="col-md-4"><p><b>Date:</b></p> </div>  
			        <div class="col-md-8 text-capitalize"><p><?php echo custDate($value['created_at']); ?></p></div>
			        </div>
		        </div>

				<div class="col-md-12">   
					<div class="row">
					<div class="col-md-4"><p><b>Remark:</b></p> </div>  
					<div class="col-md-8"><p><?php echo $value['remark']; ?></p></div>
					</div>
				</div>
				<?php if($value['solution']){ ?>
					<div class="col-md-12">   
						<div class="row">
						<div class="col-md-4"><p><b>Solution:</b></p> </div>  
						<div class="col-md-8"><p><?php echo $value['solution']; ?></p></div>
						</div>
					</div>
				<?php } ?>
				<?php if($value['mom_text']){ ?>
					<div class="col-md-12">   
						<div class="row">
						<div class="col-md-4"><p><b>MOM:</b></p> </div>  
						<div class="col-md-8"><p><?php echo $value['mom_text']; ?></p></div>
						</div>
					</div>
				<?php } ?>

				<?php if($value['mom_doc']){ ?>
					<div class="col-md-12">   
						<div class="row">
						<div class="col-md-4"><p><b>MOM Attachment:</b></p> </div>  
						<div class="col-md-8"><p>
							<?php
							// $ext = strtolower(pathinfo($value['mom_doc'], PATHINFO_EXTENSION));
							// $img_ext = ['jpg','jpeg','png'];
							
								echo '<a href="'.base_url($value['mom_doc']).'" data-fancybox="gallery" rel="gallery'.$value['id'].'"> View Attachment</a>';
							
							?></p></div>
						</div>
					</div>
				<?php } ?>

			</div>
			<!-- End 2nd level assignee -->
		<?php
		}
		else if($value['type'] == 'customer'){?>
			<!-- start 2nd level assignee -->
			<div class="row">
				<div class="col-md-12 mt-3"><div class="separator-breadcrumb border-top"></div></div>
			</div>

			<div class="row">
				<div class="col-md-8 mt-4 mb-4">
				<p><b>Status changed by customer : <?php echo ps($complaint_status_list,$value['new_status']); ?></b></p>    
				</div>

				<div class="col-md-4 mt-4 mb-4">   
			        <div class="row">
			        <div class="col-md-4"><p><b>Date:</b></p> </div>  
			        <div class="col-md-8 text-capitalize"><p><?php echo custDate($value['created_at']); ?></p></div>
			        </div>
		        </div>
			</div>
			<!-- End 2nd level assignee -->
		<?php
		}

	}
}

?>



