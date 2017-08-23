<?php

/*
08/22/2017 changed instructions to be about speakers rather than transcribers
08/02/2017 rewrote this to be blocked

GETS DATA FROM
"NC2speaker_lists/sublists" folder 

SENDS DATA TO
done2.php

AUTHOR
Rachel Ryskin
*/

// Assigning random subject ID
	$subjID = mt_rand().mt_rand();
	//echo $subjID;

// Randomly assign participant to counterbalancing list
	$list = mt_rand(1,8);
	//echo 'list: '.$list;

// Read in noError test materials from that list 
	$noError_test_materials = array();
	$noError_test_materials_file = fopen("NC2speaker_lists/sublists/noError_test_list".$list.".csv", "r");
	$noError_test_header = fgetcsv($noError_test_materials_file);
	//var_dump($header) ;
	while ($noError_test_row = fgetcsv($noError_test_materials_file)) {
		$noError_test_materials[] = array_combine($noError_test_header, $noError_test_row);
	}
	fclose($noError_test_materials_file);
	
// Read in Error test materials from that list 
	$Error_test_materials = array();
	$Error_test_materials_file = fopen("NC2speaker_lists/sublists/Error_test_list".$list.".csv", "r");
	$Error_test_header = fgetcsv($Error_test_materials_file);
	while ($Error_test_row = fgetcsv($Error_test_materials_file)) {
		$Error_test_materials[] = array_combine($Error_test_header, $Error_test_row);
	}
	fclose($Error_test_materials_file);
	
// Read in noError exposure materials from that list 
	$noError_exposure_materials = array();
	$noError_exposure_materials_file = fopen("NC2speaker_lists/sublists/noError_exposure_list".$list.".csv", "r");
	$noError_exposure_header = fgetcsv($noError_exposure_materials_file);
	//var_dump($header) ;
	while ($noError_exposure_row = fgetcsv($noError_exposure_materials_file)) {
		$noError_exposure_materials[] = array_combine($noError_exposure_header, $noError_exposure_row);
	}
	fclose($noError_exposure_materials_file);
	
// Read in Error exposure materials from that list 
	$Error_exposure_materials = array();
	$Error_exposure_materials_file = fopen("NC2speaker_lists/sublists/Error_exposure_list".$list.".csv", "r");
	$Error_exposure_header = fgetcsv($Error_exposure_materials_file);
	while ($Error_exposure_row = fgetcsv($Error_exposure_materials_file)) {
		$Error_exposure_materials[] = array_combine($Error_exposure_header, $Error_exposure_row);
	}
	fclose($Error_exposure_materials_file);

// Read in noError filler materials from that list 
	$noError_filler_materials = array();
	$noError_filler_materials_file = fopen("NC2speaker_lists/sublists/noError_fillers_list".$list.".csv", "r");
	$noError_filler_header = fgetcsv($noError_filler_materials_file);
	//var_dump($header) ;
	while ($noError_filler_row = fgetcsv($noError_filler_materials_file)) {
		$noError_filler_materials[] = array_combine($noError_filler_header, $noError_filler_row);
	}
	fclose($noError_filler_materials_file);
	
// Read in Error filler materials from that list 
	$Error_filler_materials = array();
	$Error_filler_materials_file = fopen("NC2speaker_lists/sublists/Error_fillers_list".$list.".csv", "r");
	$Error_filler_header = fgetcsv($Error_filler_materials_file);
	while ($Error_filler_row = fgetcsv($Error_filler_materials_file)) {
		$Error_filler_materials[] = array_combine($Error_filler_header, $Error_filler_row);
	}
	fclose($Error_filler_materials_file);

// Split filler trials into 2 halves: one for exposure block and one for test block

	shuffle($noError_filler_materials);
	$noError_filler_exposure = array_slice($noError_filler_materials,0,15);
	$noError_filler_test = array_slice($noError_filler_materials,15);

	shuffle($Error_filler_materials);
	$Error_filler_exposure = array_slice($Error_filler_materials,0,15);
	$Error_filler_test = array_slice($Error_filler_materials,15);

// Create Exposure blocks and test blocks and then shuffle all of them so fillers are mixed in

	$noError_exposure_block = array_merge($noError_filler_exposure,$noError_exposure_materials);
	$Error_exposure_block = array_merge($Error_filler_exposure,$Error_exposure_materials);

	$noError_test_block = array_merge($noError_filler_test,$noError_test_materials);
	$Error_test_block = array_merge($Error_filler_test,$Error_test_materials);

	shuffle($noError_exposure_block);
	shuffle($Error_exposure_block);
	shuffle($noError_test_block);
	shuffle($Error_test_block);

// assign counterbalancing order
	$block_order = mt_rand(1,4);

//Build the big ol' Presentation Matrix with the order we want to present the trials
	$TotalTrials = 130;

	if ($block_order == 1){
		$PresMatrix = array_merge($noError_exposure_block,$Error_exposure_block,$noError_test_block,$Error_test_block);
	} elseif ($block_order ==2){
		$PresMatrix = array_merge($noError_exposure_block,$Error_exposure_block,$Error_test_block,$noError_test_block);
	} elseif ($block_order ==3){
		$PresMatrix = array_merge($Error_exposure_block,$noError_exposure_block,$Error_test_block,$noError_test_block);
	} elseif ($block_order ==4){
		$PresMatrix = array_merge($Error_exposure_block,$noError_exposure_block,$noError_test_block,$Error_test_block);
	}

// assign which color/name is tied to Error vs. No Error
	$color_assignment = mt_rand(1,2);
	// 1 = Speaker_Error is A
	// 2 = Speaker_Error is B
	
	//echo '**PRES MATRIX**: '. sizeof($PresMatrix);
	//var_dump($PresMatrix) ;


	
	//Get the date and time
	date_default_timezone_set('UTC');	//Set the timezone to UTC
	$start_date =  date('n\/d\/Y');		//month/day/year
	$start_time = date('H:i:s');		//24hour:minutes:seconds

?>


<html>
<head>
	<title>Typing task</title>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
	<link rel="stylesheet" type="text/css" href="survey_style.css">
	
	<script src="jquery-3.2.1.min.js"></script>
	<script src="survey_functions.js"></script>
</head>

<body>
<form name="Typing" method="post" action="done2.php">	
<!-- Store time and date -->
<input type="hidden" name="start_date" value="<?php echo $start_date;?>">
<input type="hidden" name="start_time" value="<?php echo $start_time;?>">

<!-- Survey page -->
<div class="page firstpage" id="1">
<div class="cell">
	<p class="bold_underline">
	Instructions
	</p>
	
	<p class= bold> 
	<h4>The following sentences are transcriptions of spoken material from 2 different speakers, <font color="red">Speaker A</font> and <font color="blue">Speaker B</font>. Some of these sentences may contain errors. Please RETYPE each sentence and fix any errors. It is fine to copy and paste the sentence and then edit it if you see an error. </h4>
	</p>

<!-- The actual trials -->
<?php
// Create all the trials
for ($TrialNum = 0; $TrialNum < $TotalTrials; ++$TrialNum) {
	$index = $TrialNum+1;

	echo '<div class="cell">';
	echo '<br />';

		//Inputs that are not based on user input and get passed through to the output						. '">';
		echo '<input type="hidden" name="TrialNum_'		. $index . '" value="' . $index 	. '">';
		echo '<input type="hidden" name="subjID" value="' . $subjID . '">';
		echo '<input type="hidden" name="ItemNum_'	 	. $index . '" value="' . $PresMatrix[$TrialNum]['Item_Number']	. '">';
		echo '<input type="hidden" name="Condition_'	. $index . '" value="' . $PresMatrix[$TrialNum]['Condition']	. '">';
		echo '<input type="hidden" name="TrialType_'	. $index . '" value="' . $PresMatrix[$TrialNum]['TrialType']	. '">';
		echo '<input type="hidden" name="SentenceType_'	. $index . '" value="' . $PresMatrix[$TrialNum]['SentenceType']	. '">';
		echo '<input type="hidden" name="List_' . $index . '" value="' . $PresMatrix[$TrialNum]['List']	. '">';
		echo '<input type="hidden" name="BlockOrder" value="' . $block_order . '">';
		
		if ($color_assignment == 1){
			if (strcmp($PresMatrix[$TrialNum]['Condition'], "Speaker_Error") == 0) {
				$Name = "Speaker A";
		    	$color = "red";
		    } 
		    else {
			    $Name = "Speaker B";
			    $color = "blue";
			} 
		} elseif ($color_assignment == 2){
		if (strcmp($PresMatrix[$TrialNum]['Condition'], "Speaker_Error") == 0) {
				$Name = "Speaker B";
		    	$color = "blue";
		    } 
		    else {
			    $Name = "Speaker A";
			    $color = "red";
			} 
		}
		echo '<input type="hidden" name="SpeakerName_'	. $index . '" value="' . $Name	. '">';


		//The sentence and the comprehension question which get displayed
		echo '<input type="hidden" name="Sentence_'		 	. $index . '" value="' . $PresMatrix[$TrialNum]['Sentence']	. '">';
		echo '<div class="sentence"><font color="'.$color.'"> ' .$Name.": ". $PresMatrix[$TrialNum]['Sentence'] . '</font></div>';
		
		//Input box for answers
		echo '<input type="text" name="Response_' . $index  . '" size= "100" "' .'/>';
		
		//echo '<br />';
		//Page number
		//echo '<div class="page_number">' . $TrialNum . ' / ' . $TotalTrials . '</div>';

		//echo '<div class="answer_choices">';
		//	echo '<label><input type="radio" name="comp_resp_' . $index  . '" value= "YES" "' .'></span> YES </span></label>';
		//	echo '<label><input type="radio" name="comp_resp_' . $index  . '" value= "NO" "' .'></span> NO </span></label>';
		//echo '</div>';
	echo '</div>';
	echo '</div>';
}

?>
<div class="cell">
	<span>Please rate the quality of <font color="red">Speaker A's</font> transcriptions. (1 = Very Poor, 5 = Excellent)</span>
	<br />
	<br />
	<input type="radio" name="A_rating" value="1"></span> 1 </span>&nbsp&nbsp
	<input type="radio" name="A_rating" value="2"></span> 2 </span>&nbsp&nbsp
	<input type="radio" name="A_rating" value="3"></span> 3 </span>&nbsp&nbsp
	<input type="radio" name="A_rating" value="4"></span> 4 </span>&nbsp&nbsp
	<input type="radio" name="A_rating" value="5"></span> 5 </span>

	<br />
	<br />
	<br />
	<span>Please rate the quality of <font color="blue">Speaker B's</font> transcriptions. (1 = Very Poor, 5 = Excellent)</span>
	<br />
	<br />
	<label><input name="B_rating" type="radio" value="1"></span> 1 </span></label>&nbsp&nbsp
	<label><input name="B_rating" type="radio" value="2"></span> 2 </span></label>&nbsp&nbsp
	<label><input name="B_rating" type="radio" value="3"></span> 3 </span></label>&nbsp&nbsp
	<label><input name="B_rating" type="radio" value="4"></span> 4 </span></label>&nbsp&nbsp
	<label><input name="B_rating" type="radio" value="5"></span> 5 </span></label>
	<br />
	<br />
	<br />

<span>Please press submit to record your responses.</span>
<br />
<br />
<!--Submit-->
<input name="submit" type="submit" value="Submit">
</div>


</form>

</body>

</html>