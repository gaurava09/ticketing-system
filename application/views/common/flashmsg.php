<div class="row p-2">
    <div class="col-lg-12">
           <?php 
            if( $this->session->flashdata('message') !== null){
                
                $response = $this->session->flashdata('message');

                if($response['status'] == 1 ){
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            '.$response['message'].'
                        </div>';
                }else{
                    echo ' <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                </button>
                                '.$response['message'].'
                        </div>';
   
                }
            }
        ?>
    </div>
</div>