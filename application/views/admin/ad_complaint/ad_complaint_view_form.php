<?php 
$can_action = 1;
if($this->role == 'admin' || $this->role == 'super_admin'){
  $allowed_status = ['0','1','2','3','4'];
}else{
  $allowed_status = ['1','3'];

  if($complaint['status'] == 4){
     $can_action = 0;
  }
}
/*
 if($this->role == 'admin'){
    echo form_open_multipart('complaint/classification',array('id' => 'classForm','autocomplete' => 'off') ); ?>
      <div class="row">
        <div class="col-md-12 mt-3"><div class="separator-breadcrumb border-top"></div></div>
      </div>

      <div class="row mb-3">
          <div class="col-md-4 d-none">
              <label>Complaint Classification</label>
          </div>
          <div class="col-md-4">
              <input type="hidden" name="complaint_id" value="<?php echo $complaint['id']; ?>">
            
              <select class="form-control"  name="class">
                  <option value="">Select Classification</option>
                <?php
                  $classifications = classifications();
                    foreach ($classifications as $key => $value) {
                        echo '<option value="'.$key.'">'.$key.' - '.$value.'</option>';
                    }
                  ?>
            </select>

          </div>
          <div class="col-md-4">
              <button class="btn btn-primary" id="classForm_submit" type="submit">Save</button> 
          </div>
      </div>
      </form>
<?php }*/  
// xx admin or employee assigned by admin
//admin or top_dept 1
if($topLevel == 1){ ?>

<div class="row">
  <div class="col-md-12 mt-3"><div class="separator-breadcrumb border-top"></div></div>
</div>


<?php if($can_action){ ?>
<div class="row mb-3">    
  <div class="col-md-4 col-sm-12 form-group mb-3">
  <label for=" ">Action</label>
    <select class="form-control selectType" id='selectType' >
      <option value="">Select</option>
      <?php if($this->role == 'admin' || $this->role == 'super_admin'){ ?>
        <option value="1">Assign</option>
        <option value="2">Reply To Customer</option>
        <option value="4">Reply To Employee</option>
      <?php } ?>
      <?php if($this->role == 'employee'){ ?>
        <option value="3">Reply To Admin</option>
      <?php } ?>
      <?php
        // if($this->role == 'admin'){
        //   echo '<option value="3">Complaint Classification</option>';
        // }
      ?>
    </select>
  </div>
</div>
<?php  }  ?>

<?php echo form_open_multipart('complaint/assign',array('id' => 'assignForm','class' => 'd-none','autocomplete' => 'off') ); ?>

<input type="hidden" name="complaint_id" value="<?php echo $complaint['id']; ?>">
<div class="row assign_wrapper">

  <div class="col-md-3 assign_div">
    <div class="row">  

        <div class="col-md-10 form-group mb-3">
        <label for=" ">Assign To</label>

        <select class="form-control department" name="department[]">
          <option value="">Select Department</option>
          <?php
            foreach ($department as $key => $value) {
              if($value['status'] == 1){
                  // if($this->role == 'admin' && $value['top_dept'] == 1){
                  if( $value['top_dept'] == 1){
                      echo '<option value="'.$value['id'].'">'.$value['name'].' [TOP]</option>';
                  }
                  else{
                      echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
                  }
              }
            }
          ?>
        </select>
        </div>

        <div class="col-md-2 form-group mb-3 pt-4 mt-2"><a href="#"><i class="text-20 i-Add add_assign_div"></i></a></div>
    </div>


      <div class="row">    
        <div class="col-md-10 form-group mb-3">
        <label for=" ">Employee</label>
          <select class="form-control employee" name="employee[]">
            <option value="">Select Employee</option>
          </select>
        </div>
      </div>

    <div class="row">    
        <div class="col-md-10 form-group mb-3"><label for=" ">Remark</label>
          <textarea class="form-control remark" name="remark[]"></textarea>
        </div>
    </div>
</div>

</div>

<div class="row">
  <div class="col-md-12 mt-4">
    <button class="btn btn-primary" id="assignForm_submit" type="submit">Submit</button> 
  </div>
</div>
</form>


<?php echo form_open_multipart('complaint/remark',array('id' => 'remarkForm','class' => 'd-none','autocomplete' => 'off') ); ?>

  <input type="hidden" name="complaint_id" value="<?php echo $complaint['id']; ?>">
  <input type="hidden" name="action_type" id="action_type" value="">
  <div class="row ">

      <?php
        // if(strtolower($complaint['complaint_type']) == 'request for engineer visit'){ ?>
          <!-- <div class="col-md-12 form-group mb-3">
            <label for=" ">Visit Date</label>
            <input type="text" name="visit_date" id="visit_date">
        </div> -->
      <?php // }  ?>

      <div class="col-md-12 form-group mb-3">
          <label for=" ">Remark</label>
          <textarea class="form-control" name="remark"></textarea>
      </div>

        <div class="col-md-12 form-group mb-3 mom_div">
            <label for=" ">Upload supporting file and comment </label>

            <div class="col-12 input-group inp-group ">
                <div class="input-group-prepend delete_doc" style="cursor: pointer;">
                    <span class="input-group-text " title="Delete">X</span>
                </div>

                <div class="custom-file">
                    <input type="file" class="custom-file-input photo mom_doc" name='mom_doc'  accept="image/jpeg,image/gif,image/png,application/pdf">
                    <label class="custom-file-label" for="photo">Choose File (jpg,jpeg,png,pdf,xlsx,doc,docx format)</label>
                </div>
            </div>

            <div class="col-12 preview_div img_thumb " style="display: none;">
                <img src="" class="thumbnail image_preview" />
            </div>
        </div>

      <div class="col-md-4 form-group mb-3">
          <label for=" ">Status</label>
            <select class="form-control"  name="status">
            <option value="">Select Status</option>
            <?php
                $status_list = complaint_status_list();
                  foreach ($status_list as $key => $value) {

                    if( in_array($key, $allowed_status)){
                      echo '<option value="'.$key.'">'.$value.'</option>';
                    }
                    
                  }
                ?>
          </select>
      </div>

  </div>


  <div class="row">
    <div class="col-md-12 mt-4">
      <button class="btn btn-primary" id="remarkForm_submit" type="submit">Submit</button> 
    </div>
  </div>
</form>


<?php echo form_open_multipart('complaint/remarkEmp',array('id' => 'remarkForm_emp','class' => 'd-none','autocomplete' => 'off') ); ?>

  <input type="hidden" name="complaint_id" value="<?php echo $complaint['id']; ?>">
  <div class="row ">

      <?php
        // if(strtolower($complaint['complaint_type']) == 'request for engineer visit'){ ?>
          <!-- <div class="col-md-12 form-group mb-3">
            <label for=" ">Visit Date</label>
            <input type="text" name="visit_date" id="visit_date">
        </div> -->
      <?php // }  ?>

      <div class="col-md-12 form-group mb-3">
          <label for=" ">Remark</label>
          <textarea class="form-control" name="remark"></textarea>
      </div>

        <div class="col-md-12 form-group mb-3 mom_div">
            <label for=" ">Upload supporting file and comment </label>

            <div class="col-12 input-group inp-group ">
                <div class="input-group-prepend delete_doc" style="cursor: pointer;">
                    <span class="input-group-text " title="Delete">X</span>
                </div>

                <div class="custom-file">
                    <input type="file" class="custom-file-input photo mom_doc" name='mom_doc'  accept="image/jpeg,image/gif,image/png,application/pdf">
                    <label class="custom-file-label" for="photo">Choose File (jpg,jpeg,png,pdf,xlsx,doc,docx format)</label>
                </div>
            </div>

            <div class="col-12 preview_div img_thumb " style="display: none;">
                <img src="" class="thumbnail image_preview" />
            </div>
        </div>

      <div class="col-md-4 form-group mb-3">
          <label for=" ">Status</label>
            <select class="form-control"  name="status">
            <option value="">Select Status</option>
            <?php
                $status_list = complaint_status_list();
                  foreach ($status_list as $key => $value) {

                    if( in_array($key, $allowed_status)){
                      echo '<option value="'.$key.'">'.$value.'</option>';
                    }
                    
                  }
                ?>
          </select>
      </div>

  </div>


  <div class="row">
    <div class="col-md-12 mt-4">
      <button class="btn btn-primary" id="remarkForm_emp_submit" type="submit">Submit</button> 
    </div>
  </div>
</form>

<?php /* if($this->role == 'admin') { ?>
<!-- classification -->
  <?php echo form_open_multipart('complaint/classification',array('id' => 'classForm','class' => 'd-none','autocomplete' => 'off') ); ?>

  <input type="hidden" name="complaint_id" value="<?php echo $complaint['id']; ?>">
  <div class="row ">

      <div class="col-md-4 form-group mb-3">
          <!-- <label for=" ">Classification</label> -->
            <select class="form-control"  name="class">
            <option value="">Select Classification</option>
            <?php
                $classifications = classifications();
                  foreach ($classifications as $key => $value) {
                      echo '<option value="'.$key.'">'.$key.' - '.$value.'</option>';
                  }
                ?>
          </select>
      </div>

  </div>

  <div class="row">
    <div class="col-md-12 mt-4">
      <button class="btn btn-primary" id="classForm_submit" type="submit">Submit</button> 
    </div>
  </div>
</form>

<?php } */ ?>




<!-- </div> -->
<!-- </div> -->
<!-- </div> --><!-- modal body -->
<!-- </div> -->
<!-- </div> -->
<!-- </div> --> <!-- main row -->


<div class="assign_div_html d-none">

    <div class="col-md-3 assign_div">
      <div class="row">  
          <div class="col-10 form-group mb-3">
            <label for=" ">Assign To</label>
            <select class="form-control department" name="department[]">
              <option value="">Select Department</option>
              <?php
                foreach ($department as $key => $value) {
                  if($value['status'] == 1){
                        // if($this->role == 'admin' && $value['top_dept'] == 1){
                        if( $value['top_dept'] == 1){
                            echo '<option value="'.$value['id'].'">'.cap($value['name']).' [TOP]</option>';
                        }
                        else{
                            echo '<option value="'.$value['id'].'">'.cap($value['name']).'</option>';
                        }
                    }
                }
              ?>
            </select>
          </div>

          <div class="col-2 form-group mb-3 pt-4 mt-2"><a href="#"><i class="text-20 i-Remove remove_assign_div"></i></a></div>
      </div>

      <div class="row">    
        <div class="col-10 form-group mb-3">
        <label for=" ">Employee</label>
          <select class="form-control employee" name="employee[]">
            <option value="">Select Employee</option>
          </select>
        </div>
      </div>

      <div class="row">    
          <div class="col-10 form-group mb-3"><label for=" ">Remark</label>
            <textarea class="form-control remark" name="remark[]"></textarea>
          </div>
      </div>
  </div>

</div> <!-- end assign_div_html -->

<?php }
//start if assigned by employee
 else if($topLevel == 2 && !in_array($complaint['status'], [0,4])){ 
  ?>

  <div class="row">
      <div class="col-md-12 mt-3 mb-3"><div class="separator-breadcrumb border-top"></div></div>
  </div>

  <?php echo form_open_multipart('complaint/solution',array('id' => 'solutionForm','autocomplete' => 'off') ); ?>

    <input type="hidden" name="complaint_id" value="<?php echo $complaint['id']; ?>">
    <div class="row ">
        <div class="col-md-12 form-group mb-3">
            <label for=" ">Remark / Solution </label>
            <textarea class="form-control" name="remark"></textarea>
        </div>
    </div>

    <!-- <div class="row ">
        <div class="col-md-12 form-group mb-3">
            <label for=" ">Create MOM</label>
            <textarea class="form-control" name="mom_text"></textarea>
        </div>
    </div> -->

    <div class="row ">
        <div class="col-md-12 form-group mb-3 mom_div">
            <label for=" ">Upload supporting file and comment </label>

            <div class="col-12 input-group inp-group ">
                <div class="input-group-prepend delete_doc" style="cursor: pointer;">
                    <span class="input-group-text " title="Delete">X</span>
                </div>

                <div class="custom-file">
                    <input type="file" class="custom-file-input photo mom_doc" name='mom_doc'  accept="image/jpeg,image/gif,image/png,application/pdf">
                    <label class="custom-file-label" for="photo">Choose File (jpg,jpeg,png,pdf,xlsx,doc,docx format)</label>
                </div>
            </div>

            <div class="col-12 preview_div img_thumb " style="display: none;">
                <img src="" class="thumbnail image_preview" />
            </div>
        </div>
    </div>

    <div class="row">
      <div class="col-md-12 mt-4">
        <button class="btn btn-primary" id="solutionForm_submit" type="submit">Submit</button> 
      </div>
    </div>
  </form>


<?php } ?>

