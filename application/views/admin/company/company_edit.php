<?php
defined('BASEPATH') OR exit('No direct script access allowed');


// if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '') {
//   	$back_url = $_SERVER['HTTP_REFERER'];
// }else{
	$back_url = site_url('company');
// }
?>


<div class="">
	<div class="float-left breadcrumb"><h1 class="mr-2">Edit Company</h1></div>
	<div class="float-right">
	<a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo $back_url; ?>"><i class="i-Left-3"></i> Back</a>
	</div>
	<div class="clearfix"></div>
</div>



<div class="separator-breadcrumb border-top"></div>


<?php $this->load->view('common/flashmsg'); ?>



<div class="errors"></div>

<?php echo form_open_multipart('company/'.$data['id'].'/update',array('id' => 'CompanyForm','autocomplete' => 'off') ); ?>
<div class="mt-4 mb-4">  

<div class="row">

    <div class="col-md-4 form-group mb-3"><label for=" ">Company Name</label>
    <input class="form-control"  type="text" placeholder=" " name="name" value="<?php echo $data['name']; ?>" /></div>

    <!-- <div class="col-md-4 form-group mb-3"><label for=" ">Domain</label>
        <input class="form-control"  type="text" placeholder=" " name="domain" value="<?php echo $data['domain']; ?>" />
        <p>Add domain name without protocol i.e http:// or https:// and www</p>
    </div> -->


    <div class="col-md-4 form-group mb-3"><label for=" ">Location</label>
    <input class="form-control"  type="text" placeholder=" " name="location" value="<?php echo $data['location']; ?>" /></div>



    <div class="col-md-4 form-group mb-3"><label for=" ">Status</label>
    	<select class="form-control" id="status" name="status">
    		<option value="">Select Status</option>
    		<option value="1" <?php echo ($data['status'] == 1) ? "selected" : ""; ?> >Active</option>
    		<option value="0" <?php echo ($data['status'] == 0) ? "selected" : ""; ?> >Deactive</option>
    	</select>
    </div>
    <div class="col-md-12 form-group mb-3 cc_div">
      <label>A09 Employees</label>
      <label>Already Added</label><br>
        <?php
        // Check if $first_names is not empty
        if (!empty($first_names)) {
            // Explode the data into an array based on commas
            $entries = explode(',', $first_names);

            // Initialize an array to hold the IDs
            $ids = [];

            // Iterate over each entry
            foreach ($entries as $entry) {
            // Extract name, ID, and role using regex
            preg_match('/\[(\d+)\]/', $entry, $matches);
            $id = $matches[1];
            //dd($entry);
            $emp = preg_replace('/\[\d+\]/', '', $entry);
            // Output the entry with a delete button
            echo '<div class="col-sm-4">';
            echo '<div id="entry-'.$id.'" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 5px;">';
            echo '<span>'.$emp.'</span>';
            echo '<a class="deleteBtn btn btn-danger" style="padding: 1px 5px 1px 5px;" data-id="'.$id.'"><i class="fa fa-minus-circle" aria-hidden="true"></i></a>';
            echo '</div>';
            echo '</div>';
            // Push ID to the array
            array_push($ids, $id);
        }

            // PHP array to JavaScript array
            echo '<script>var ids = ' . json_encode($ids) . ';</script>';
        }
        ?>
        <div class="col-6 form-group" style="display: flex;">
            <input type="hidden" class="form-control" id="employeeInput" name="already_emp" value="<?php echo isset($ids) ? implode(',', $ids) : ''; ?>"  readonly>
        </div>
        <!-- <div id="output"></div> -->
        <div class="row cc_row">  
            <!-- <div class="col-6 form-group">
                <input list="employees" class="form-control" type="text" placeholder="Add Employee" name="Employee_add[]"/>
                <datalist id="employees">
                    <?php  foreach ($admin as $key => $value) { ?>
                      <option value="<?php echo $value['id']  ?>"><?php echo $value['first_name']  ?> <?php echo $value['last_name']  ?></option>
                    <?php } ?>
                </datalist>
            </div> -->
            <div class="col-6 form-group">
                <select class="form-control employee-dropdown" name="Employee_add[]">
                    <option value="">Select Employee</option>
                    <?php foreach ($admin as $key => $value) { ?>
                        <option value="<?php echo $value['id']; ?>"><?php echo $value['first_name'] . ' ' . $value['last_name'] . ' (' . $value['role'] . ')'; ?></option>
                    <?php } ?>
                </select>
            </div>
          <div class="col-2 form-group  pt-1 "><a href="#"><i class="text-20 i-Add add_Employee"></i></a></div>
        </div>
    </div>
</div>

<div class="col-md-12 mt-4">
<button class="btn btn-primary float-right" id="submit_dept" type="submit">Submit</button>    
</div>

</div>    
 
</form>

<div class="cc_html d-none">
    <div class="row cc_row">  
        <div class="col-6 form-group">
            <select class="form-control employee-dropdown" name="Employee_add[]">
                <option value="">Select Employee</option>
                <?php foreach ($admin as $key => $value) { ?>
                    <option value="<?php echo $value['id']; ?>"><?php echo $value['first_name'] . ' ' . $value['last_name'] . ' (' . $value['role'] . ')'; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="col-2 form-group  pt-1 "><a href="#"><i class="text-20 i-Remove remove_cc"></i></a></div>
    </div>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<?php $this->load->view('common/footer');  ?>
<script src="<?php echo base_url('assets/libs/jquery-validation/jquery.validate.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/custom.js'); ?>"></script>


<script type="text/javascript">
    // Click event handler for delete buttons
    $('.deleteBtn').on('click', function() {
        var idToRemove = $(this).data('id'); // Get the ID to remove
        // Find the corresponding entry and extract the name
        var nameToRemove = $('#entry-' + idToRemove).text().trim().split(' ')[0]; // Extract the first word as the name
        // Remove the corresponding entry from the DOM
        $('#entry-' + idToRemove).remove();
        // Update the input field
        var currentValue = $('#employeeInput').val().split(','); // Get current values
        var updatedValue = currentValue.filter(function(id) {
            return id != idToRemove; // Filter out the ID to remove
        }).join(',');
        $('#employeeInput').val(updatedValue); // Update the input field
        // Show the alert with the name to be deleted
        alert('Deleting entry with name: ' + nameToRemove);
        // Send an AJAX request or handle deletion here
    });


    $('.add_Employee').click(function(e) {
        e.preventDefault();

        var allValid = true;
        var employeeData = []; // Array to store employee data

        // Check all existing dropdowns
        $('.cc_div .cc_row select.employee-dropdown').each(function() {
            var value = $(this).val();

            if (value === '') {
                alert('Please select an employee for all fields.');
                allValid = false;
                return false; // Exit the loop early
            }

            // Check for duplicate data
            if (employeeData.indexOf(value) !== -1) {
                alert('Duplicate data found.');
                $(this).parents('.cc_row').remove(); // Remove the duplicate row
                allValid = false;
                return false; // Exit the loop early
            }

            // Store the field value
            employeeData.push(value);
        });

        // If all existing fields are valid, proceed to add a new one
        if (allValid) {
            var $html = $('.cc_html .cc_row').clone();
            if ($('.cc_div .cc_row').length < 9) {
                $('.cc_div').append($html);
            } else {
                alert('You cannot add more than 10 employees.');
            }
        }
      });

      $(document).on('click','.cc_div .remove_cc', function(e) {
          e.preventDefault();
          
          if($('.cc_div .cc_row').length > 1){
              $(this).parents('.cc_row').remove();
          } else {
              // alert('you can not assign more than 4 at a time');
          }
      });
	$('#CompanyForm').validate({
        ignore: [],
        // debug: true,
        rules: {
            name: {required: true},
            //domain: {required: true},
            location: {required: true},
            status: {required: true},
        },
        messages: {

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

        submitHandler: function(form) {
            var formData = new FormData(form);
            $button = $("#submit_dept");
            showLoading($button);

            $.ajax({
              type: 'post',
                url: $(form).attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                success: function($res) {
      
                    if($res.status == 1){
                      window.location.href = "<?php echo site_url('company/'.$data['id'].'/edit')?>";
                      return false;
                    }
                    else{
                    	showError($res.message);
                        // errorPopup($res.message);
                    }
                    stopLoading();
                },
                error: function(error, textStatus, errorMessage) {
                    console.log(error);
                    showError('Request could not be completed');
                    stopLoading();
                }             
            });
        }

    });
</script>