<?php

/*

05/23/2017 - Editing to randomly assign participants to different lists and put 3 filler trials first
05/11/2017 - Taking Zach's old code and making it into a  one-page survey to test Noisy Channel sentences. I don't know PHP so this should get weird. Gonna leave a lot of Zach's stuff commented for now so I know what's going on.

GETS DATA FROM
"noisy_scz_lists" folder which contains:
noisy_scz_fillers.csv
noisy_scz_list1.csv
noisy_scz_list2.csv
noisy_scz_list3.csv
noisy_scz_list4.csv

SENDS DATA TO
done.php

AUTHOR
Rachel Ryskin
*/

// Assigning random subject ID
	$subjID = mt_rand().mt_rand();
	//echo $subjID;
	$uniqueID = $_POST['uniqueID'];
	//echo $uniqueID;

// Randomly assign participant to counterbalancing list
	$list = mt_rand(1,4);
	//echo 'list: '.$list;

// Read in materials from that list 
	$materials = array();
	
	$materials_file = fopen("noisy_scz_lists/noisy_scz_list".$list.".csv", "r");

	$header = fgetcsv($materials_file);
	
	while ($row = fgetcsv($materials_file)) {
		$materials[] = array_combine($header, $row);
	}

	fclose($materials_file);
	

// Read in filler items
	$fillers = array();
	
	$fillers_file = fopen("noisy_scz_lists/noisy_scz_fillers.csv", "r");

	$fillers_header = fgetcsv($fillers_file);
	
	while ($f_row = fgetcsv($fillers_file)) {
		$fillers[] = array_combine($fillers_header, $f_row);
	}

	fclose($fillers_file);

	shuffle($fillers);

	$first_3_fillers = array_slice($fillers,0,3);
	//echo 'first3: ' . sizeof($first_3_fillers);
	//var_dump($first_3_fillers); 
	$remaining_fillers = array_slice($fillers, 3);
	$materials2 = array_merge($materials,$remaining_fillers);
	//echo '**MATERIALS2**: ';
	//var_dump($materials2) ;
	
	//echo $materials[0]{'Condition'};
	//echo $materials[1]{'Sentence'};
	
	//echo $materials[0,1];

	shuffle($materials2);

	
	//Build the big ol' Presentation Matrix
	//This matrix is basically the "materials" array, but is in the order we want to present the trials
	$TotalTrials = 80;
	$PresMatrix = array_merge($first_3_fillers, $materials2);
	//echo '**PRES MATRIX**: '. sizeof($PresMatrix);
	//var_dump($PresMatrix) ;


	
	//Get the date and time
	date_default_timezone_set('UTC');	//Set the timezone to UTC
	$start_date =  date('n\/d\/Y');		//month/day/year
	$start_time = date('H:i:s');		//24hour:minutes:seconds

?>


<html>
<head>
	<title>Survey</title>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
	<link rel="stylesheet" type="text/css" href="survey_style.css">
	
	<script src="jquery-3.2.1.min.js"></script>
	<script src="survey_functions.js"></script>
</head>

<body>
<form name="Survey1" method="post" action="done.php">	
<!-- Store time and date -->
<input type="hidden" name="start_date" value="<?php echo $start_date;?>">
<input type="hidden" name="start_time" value="<?php echo $start_time;?>">

<div class="wrapper">



<!-- Survey page -->
<div class="page firstpage" id="1">
<div class="cell">
	<p class="bold_underline">
	Instructions
	</p>
	
	<p class= bold> 
	Please read each sentence, and then answer the question immediately following the sentence by clicking "Yes" or "No." For any given question, if you are not sure about the answer, just make your best guess.
	</p>
	<br />
	<br />


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
		echo '<input type="hidden" name="uniqueID" value="' . $uniqueID . '">';
		echo '<input type="hidden" name="ItemNum_'	 	. $index . '" value="' . $PresMatrix[$TrialNum]['Item_Number']	. '">';
		echo '<input type="hidden" name="Condition_'	. $index . '" value="' . $PresMatrix[$TrialNum]['Condition']	. '">';
		echo '<input type="hidden" name="Type_'		 	. $index . '" value="' . $PresMatrix[$TrialNum]['Type']	. '">';
		echo '<input type="hidden" name="comp_corr_' . $index . '" value="' . $PresMatrix[$TrialNum]['Literal_Answer']	. '">';
		echo '<input type="hidden" name="List_' . $index . '" value="' . $PresMatrix[$TrialNum]['List']	. '">';
		
		//The sentence and the comprehension question which get displayed
		echo '<input type="hidden" name="Sentence_'		 	. $index . '" value="' . $PresMatrix[$TrialNum]['Sentence']	. '">';
		echo '<div class="sentence">' . $PresMatrix[$TrialNum]['Sentence'] . '</div>';
		echo '<div class="comp_q">' . $PresMatrix[$TrialNum]['Question'] . '</div>';
		echo '<input type="hidden" name="Question_'		 	. $index . '" value="' . $PresMatrix[$TrialNum]['Question']	. '">';
		
		//Possible answers to the comprehension question
		echo '<div class="answer_choices">';
			echo '<label><input type="radio" name="comp_resp_' . $index  . '" value= "YES" "' .'></span> YES </span></label>';
			echo '<label><input type="radio" name="comp_resp_' . $index  . '" value= "NO" "' .'></span> NO </span></label>';
		echo '</div>';

		//echo '<br />';
		//Page number
		//echo '<div class="page_number">' . $TrialNum . ' / ' . $TotalTrials . '</div>';
		
	echo '</div>';
	echo '</div>';
}


?>

<div class="cell">
	<!--Please press submit to record your responses.-->
	<span>Please press submit to record your responses.</span>
	<br />
	<br />
	<!--Сохранить = Submit-->
	<input name="submit" type="submit" value="Submit">
</div>
</div>

</div> <!-- wrapper -->

</form>

</body>

</html>