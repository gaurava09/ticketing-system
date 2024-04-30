<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;

class AdFeedbackPdf extends My_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->is_logged_in(1);
		$this->is_a_admin(1,$this->CUST_LOGIN);
		
		$this->load->model('Customer_model');
		$this->load->model('Company_model');
		$this->load->model('Project_model');
		$this->load->model('Complaint_model');
		$this->load->model('Equipment_model');
		$this->load->model('Feedback_model');
	}

	public function dompdf($complaintId) {	
		// $complaintId = 37;

		$complaint = $this->Complaint_model->get_complaint(['id' => $complaintId]);

		if($complaint){
			$feedback = $this->Feedback_model->get_feedback(['complaint_id' => $complaintId]);

			if(!$feedback){
				$this->sendFlashMsg(0,'Feedback details not found', 'feedback');
			}
			$project = $this->Project_model->get_project_details(array('p.ga_no' => $complaint['ga_no']));

			$customer = $this->Customer_model->get_customer_details(['c.id' => $complaint['customer_id']]);

		}else{
			$this->sendFlashMsg(0,'Complaint details not found', 'feedback');
		}
		$ratings = json_decode($feedback['rating'],true);

		$dompdf = new Dompdf();


$die = 0;

$path = base_url('assets/images/mahindra_tsubaki.png');
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$logo = 'data:image/' . $type . ';base64,' . base64_encode($data);

		$html = '
		<style>
			.inl{ margin: 35px 0;clear:both;width: 100%;}		
.d-flex{
	//display: inline-flex;
}
			//.table{ margin-bottom: 25px; clear:both;}
			.fourth{padding-left:20px;}

			.table .row{text-align:left;}
			.table .col{text-align:center;}
			.text-center input{margin-left:45%;}

			.col-1 {width: 100%;}
			//.col-2 {width: 60%;float: right;}
			.col-2 {width: 100%;}
			textarea{width:100%;}
			.text-left{text-align:left;}
		</style>

<div class="row">

<p style="text-align: center;margin: 0;"><img src="'.$logo.'"></p>

<h2 style="text-align: center;border-bottom: 1px solid #ccc;padding-bottom: 10px;margin-top: 3px;">
Tsubaki Conveyor Systems Private Limited
</h2>

<h3 class="mr-2">Customer Feedback Form [Ticket No. '.$complaint['ticket_no'].']</h3>

<div class="inl">
<table class="table">
	<tbody>
		<tr>
			<td><b>Company Name</b></td>
			<td> : </td>
			<td>'.cap(ps($customer,'company_name')).'</td>
	
			<td class="fourth"><b>Customer Name</b></td>
			<td> : </td>
			<td>'.ucfirst(ps($customer,'first_name')).' '.ucfirst(ps($customer,'last_name')).'</td>
		</tr>
		<tr>
			<td><b>GA No.</b></td>
			<td> : </td>
			<td>'.ps($project,'ga_no').'</td>
	
			<td class="fourth"><b>Equipment</b></td>
			<td> : </td>
			<td>'.ps($project,'equipment_name').'</td>
		</tr>
		<tr>';

	$mtc_class = '';
	if($complaint['complaint_type'] == 1) {
		if (strstr($feedback['period'], 'to')) {
	     $dateArr = explode("to", $feedback['period']);
	     if(isset($dateArr[0]) && $dateArr[0] != ''){
	        $from = custDate(trim($dateArr[0]));
	     }
	     if(isset($dateArr[1]) && $dateArr[1] != ''){
	        $to = custDate(trim($dateArr[1]));
	     }
	     $visit_date = $from.' to '.$to;
	  }else{
	    $visit_date = custDate($feedback['period']);
	  }
  
		$mtc_class = 'fourth';
		$html .= '<td><b>Period</b></td>
			<td> : </td>
			<td>'.$visit_date.'</td>';
	}
			
		
	$html .= '<td class="'.$mtc_class.'"><b>MTC Representative Name</b></td>
			<td> : </td>
			<td>'.$feedback['representative_name'].'</td>
		</tr>

	</tbody>
</table>
</div>


<div class="inl d-flex">

<div class="feedback col-1">  
<b>1) Was the visit timing suitable to your requirement?</b>
</div>';


 $yes = ($feedback['suitable_time'] == 'yes') ? 'checked' : '';
 $no = ($feedback['suitable_time'] == 'no') ? 'checked' : '';
 $na = ($feedback['suitable_time'] == 'na') ? 'checked' : '';



$html .= '<div class=" col-2"> 
<table class="table">
	<tbody>
		<tr>
			<td>
				<input class="form-check-input" id="gridRadios1" type="radio" name="suitable_time" value="yes" '. $yes.' >
				<label class="form-check-label ml-1" for="gridRadios1">Yes</label>
			</td>

			<td>
				<input class="form-check-input" id="gridRadios1" type="radio" name="suitable_time" value="no" '. $no.' >
				<label class="form-check-label ml-1" for="gridRadios1">No</label>
			</td>

			<td>
				<input class="form-check-input" id="gridRadios1" type="radio" name="suitable_time" value="na" '. $na.' >
				<label class="form-check-label ml-1" for="gridRadios1">NA</label>
			</td>

		</tr>
	</tbody>
</table>
</div>
</div>


<div class="inl d-flex">
<div class="feedback col-1">  
<b>2) Feedback on Engineer Competencies</b>
</div>

<table class="table col-2">
<thead>
<tr>
<th class="col">#</th>
<th class="col" class="text-center">POOR <br> 1</th>
<th class="col" class="text-center">AVERAGE <br> 2</th>
<th class="col" class="text-center">SATISFACTORY <br> 3</th>
<th class="col" class="text-center">GOOD <br> 4</th>
<th class="col" class="text-center">EXCELLENT <br> 5</th>
</tr>
</thead>
<tbody>

<tr class="form-group">
<th class="row">i) Technical knowledge</th>';

  for ($i=1; $i <=5; $i++) { 
    if($ratings['tech_knowledge'] == $i){ $checked = 'checked'; }else{  $checked = '';}

    $html .= '<td class="text-center"><input type="radio" name="tech_knowledge" value="'.$i.'" '.$checked.' disabled></td>';
  }

$html .= '</tr>

<tr class="form-group">
<th class="row">ii) Communication skills</th>';

  for ($i=1; $i <=5; $i++) { 
    if($ratings['comm_skill'] == $i){ $checked = 'checked'; }else{  $checked = '';}
    $html .= '<td class="text-center"><input type="radio" name="comm_skill" value="'.$i.'" '.$checked.' disabled></td>';
  }

$html .= '</tr>

<tr class="form-group">
<th class="row">iii) Punctuality</th>';

  for ($i=1; $i <=5; $i++) { 
    if($ratings['punctuality'] == $i){ $checked = 'checked'; }else{  $checked = '';}

    $html .= '<td class="text-center"><input type="radio" name="punctuality" value="'.$i.'" '.$checked.' disabled></td>';
  }

$html .= '</tr>

<tr class="form-group">
<th class="row ">iv) Commitment to Safety</th>';

  for ($i=1; $i <=5; $i++) { 
    if($ratings['safety'] == $i){ $checked = 'checked'; }else{  $checked = '';}
    $html .= '<td class="text-center"><input  type="radio" name="safety" value="'.$i.'" '.$checked.' disabled></td>';
  }

$html .= '</tr>
    
</tbody>
</table>
</div>

<div class="inl"></div>
<div class="inl d-flex">

<div class="feedback col-1">
<b>3) Feedback on Equipment Performance</b>
</div>

<table class="table col-2">
	<thead>
		<tr>
			<th class="col"> </th>
			<th class="col" class="text-center">POOR <br> 1</th>
			<th class="col" class="text-center">AVERAGE <br> 2</th>
			<th class="col" class="text-center">SATISFACTORY <br> 3</th>
			<th class="col" class="text-center">GOOD <br> 4</th>
			<th class="col" class="text-center">EXCELLENT <br> 5</th>
		</tr>
	</thead>
	<tbody>
		<tr>
		<th class="row"> </th>';


		  for ($i=1; $i <=5; $i++) { 
		    if($ratings['equipment_performance'] == $i){ $checked = 'checked'; }else{  $checked = '';}
		    $html .= '<td class="text-center"><input  type="radio" name="equipment_performance" value="'.$i.'" '.$checked.' disabled></td>';
		  }


		$html .= '</tr>
	</tbody>
</table>

</div>


<div class="inl">    
<div><b>4) Any Suggestion for Improvement in Service</b></div>
<div><textarea class="form-control" name="suggestion" readonly="">'.$feedback['suggestion'].'</textarea></div>
</div>

<div class="inl">    
<div><b>5) Comments</b></div>
<div><textarea class="form-control" name="comment" readonly="">'.$feedback['comment'].'</textarea></div>
</div>

<div class="inl d-flex">   
<div class="col-1">  
<b>6) Please rate us on the scale of 1-10 for the service provided.</b>
</div>

<table class="table col-2">
<thead>
<tr>
<th class="col"> </th>';

  for ($i=1; $i <=10; $i++) { 
    if($ratings['service_rating'] == $i){ $checked = 'checked'; }else{  $checked = '';}
    $html .= '<th class="col" class="text-center1">
    <p class="mb-1 form-group text-left"> '.$i.'</p>
    <p><input type="radio" name="service_rating" value="'.$i.'" '.$checked.' disabled> </p>
    </th>';
  }


$html .= '</tr>
</thead>
</table>
</div>

</div>';

if($die == 1){
	echo $html;die;
}

		$filename = "feedback-".$complaint['ticket_no'].".pdf";

		$dompdf->loadHtml($html);

		// (Optional) Setup the paper size and orientation
		// $dompdf->setPaper('A4', 'landscape');
		$dompdf->setPaper('letter');

		// Render the HTML as PDF
		$dompdf->render();

		// Output the generated PDF to Browser
		$dompdf->stream($filename);

	}

}

?>