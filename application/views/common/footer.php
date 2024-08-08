            </div> 
            <!-- end main content -->
            <!--========================= Footer Start =========================-->
            <div class="flex-grow-1"></div>
            <div class="app-footer"><div class="row"><div class="col-md-12">
            <div class="float-left"><p>@2024 AGENCY09 | All rights reserved</p></div>
            <div class="float-right"><a href="#" data-toggle="modal" data-target="#tandcModal">Terms & Conditions</a></div>
            </div></div></div>
            <!--========================= fotter end =========================-->

        
    </div>
</div>


<?php $this->load->view('pages/terms');  ?>
        
<!-- Danger Alert Modal -->
<div id="danger-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content modal-filled bg-danger">
            <div class="modal-body p-4">
                <div class="text-center">
                    <i class="dripicons-wrong h1 text-white"></i>
                    <h4 class="mt-2 text-white">Error!</h4>
                    <p class="mt-3 text-white msg"> </p>
                    <button type="button" class="btn btn-light my-2" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

 <!-- Success Alert Modal -->
<div id="success-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content modal-filled bg-success">
            <div class="modal-body p-4">
                <div class="text-center">
                    <i class="dripicons-checkmark h1 text-white"></i>
                    <h4 class="mt-2 text-white">Success!</h4>
                    <p class="mt-3 text-white msg"></p>
                    <button type="button" class="btn btn-light my-2" data-dismiss="modal">Continue</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<!-- Top - confirm modal content -->
<div id="confirm-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-top">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="topModalLabel"> Confirm</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <h5> </h5>
                <p> </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary confirm_submit">Yes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- warning modal -->
<div id="warning-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body p-4">
                <div class="text-center">
                    <i class="dripicons-warning h1 text-warning"></i>
                    <!-- <h4 class="mt-2">Incorrect Information</h4> -->
                    <p class="mt-3 msg"></p>
                    <button type="button" class="btn btn-warning my-2" data-dismiss="modal">Ok</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<!-- Top - confirm modal content -->
<div id="addstore-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-top">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="topModalLabel"> Confirm</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <h5> </h5>
                <p> </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary confirm_addstore">Yes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--=========================JS=========================-->
<!-- <script src="<?php //echo base_url('assets/dist-assets/js/plugins/jquery-3.3.1.min.js'); ?>"></script>
<script src="<?php //echo base_url('assets/dist-assets/js/plugins/bootstrap.bundle.min.js'); ?>"></script> -->

<?php
    echo getScript('jquery');
    echo getScript('bootstrap');
    echo getScript('perfect-scrollbar');
?>
<script src="<?php echo base_url('assets/dist-assets/js/scripts/script.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/dist-assets/js/scripts/sidebar.large.script.min.js'); ?>"></script>

<!-- ACCORDI0N JS -->
<script src="<?php echo base_url('assets/libs/woco.accordion.min.js'); ?>"></script>
<script>
$(".accordion").accordion();
$(".accordion_tab").accordion_tab();

if($(".accordion_faq").length){
    $(".accordion_faq").accordion_faq();
}

</script>
<!-- ACCORDI0N JS END -->

<!--=========================JS END=========================-->
