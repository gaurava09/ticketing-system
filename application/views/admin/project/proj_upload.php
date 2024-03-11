<?php
defined('BASEPATH') OR exit('No direct script access allowed');


  $back_url = site_url('project');

?>



<div class="">
  <div class="float-left breadcrumb"><h1 class="mr-2">Upload Projects</h1></div>
  <div class="float-right">
  <a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo $back_url; ?>"><i class="i-Left-3"></i> Back</a>
  </div>
  <div class="clearfix"></div>
</div>


<div class="separator-breadcrumb border-top"></div>

<?php $this->load->view('common/flashmsg'); ?>



<div class="row">
  <div class="col-12">
      <div class="card">
          <div class="card-body">

          <?php echo form_open_multipart('project/upload',array('id' => 'uploadFile','autocomplete' => 'off') ); ?>


             <!-- <form action="<?php echo site_url('project/upload'); ?>" class="form-horizontal" method="post" id="uploadFile" enctype="multipart/form-data"> -->

               <div class=" row mb-3">
                  <label  class="col-md-3 col-sm-12 col-form-label">Upload Excel (.xlsx)</label>
                  <div class="col-md-9 col-sm-12 input-group form-group">
                      <div class="input-group-prepend">
                          <span class="input-group-text clear_img" title="Clear File">X</span>
                      </div>

                      <div class="custom-file">
                          <input type="file" class="custom-file-input" id="file" name='file' >
                          <label class="custom-file-label" for="photo">Choose File</label>
                      </div>
                  </div>
              </div>
               <div class=" row mb-3">
                  <label  class="col-md-3 col-sm-12 col-form-label">(<a href="<?php echo base_url('documents/projects.xlsx'); ?>" donwload>Download format</a>)</label>

                  <div class="col-md-9 mt-4">
                    <button class="btn btn-primary float-right" id="submit_form" type="submit">Upload</button>    
                  </div>

                 <!--  <div class="col-12 text-center">
                      <button id="submit_edit" type="submit" class="btn btn-secondary btn-success btn-rounded waves-effect btn-lg">Upload</button>
                  </div> -->
              </div>
          </form>

          </div>

    </div>
  </div>
</div>


<?php
             
if( $this->session->flashdata('errors') !== null){ 

$errors = $this->session->flashdata('errors');

?>
<div class="row">
  <div class="col-12">
      <div class="card">
          <div class="card-body">
            <h4 class="header-title">Errors in Project Sheet</h4>

            <table id="error_table">
              <tr>
                <th>Sr. No</th>
                <th>Column Name</th>
                <th>Error</th>
                <th>Current Value</th>
                <!-- <th>Expected Value</th> -->
                <th>Row No.</th>
                <th>Col No.</th>
              </tr>

              <?php
                foreach ($errors as $key => $value) {
                  if($value[5] != ""){
                    $value[5] = $value[5]+1;
                  }
                  echo "
                    <tr>
                     <td> ".($key+1)."</td>
                     <td> ".$value[3]."</td>
                     <td> ".$value[0]."</td>
                     <td> ".$value[1]."</td>
                     <!-- <td> ".$value[2]."</td>-->
                     <td> ".($value[4]+1)."</td>
                     <td> ". $value[5] ."</td>
                    </tr>
                  ";

                }
              ?>
            </table>

          </div>

    </div>
  </div>
</div>
<?php 
}
?>



<style>
#error_table {
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#error_table td, #error_table th {
  border: 1px solid #ddd;
  padding: 8px;
}

/*#error_table tr:nth-child(even){background-color: #f2f2f2;}*/
/*#error_table tr:hover {background-color: #ddd;}*/

#error_table th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #c82020;
  color: white;
}
</style>

<?php $this->load->view('common/footer');  ?>
<script src="<?php echo base_url('assets/libs/jquery-validation/jquery.validate.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/libs/flatpickr/flatpickr.min.js');?>"></script>
<script src="<?php echo base_url('assets/custom.js'); ?>"></script>

<script type="text/javascript">

  $("#file").change(function(){
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            $('.custom-file-label').html(this.files[0].name);
            reader.readAsDataURL(this.files[0]);
         }
  });

  $(document).on('click','.clear_img',function(){
      $('.custom-file-input').val('');
      $('.custom-file-label').html('Choose File');
  });



    $('#uploadFile').validate({
        ignore: [],
        // debug: true,
        rules: {
            file: {
                required: true,
                extension: "xlsx",
                filesize: 1, //1MB
              },
        },
        messages: {
          file: {
            required: "Please upload file",
            extension: "only upload xlsx format file",
            filesize: "Filesize can not be greater than 1 MB"
          },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
          $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        },

    });


  
</script>

<script type="text/javascript">
  menuActive('menu-managelevel');
</script>