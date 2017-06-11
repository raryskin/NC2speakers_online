<?php

/*

06/10/2017 Starting to write code for NC2Speaker

GETS DATA FROM
"NC2speaker_lists" folder which contains:

NC2speaker_list1.csv
NC2speaker_list2.csv
NC2speaker_list3.csv
NC2speaker_list4.csv
NC2speaker_list5.csv
NC2speaker_list6.csv
NC2speaker_list7.csv
NC2speaker_list8.csv

SENDS DATA TO
done.php

AUTHOR
Rachel Ryskin
*/

// Assigning random subject ID
	$subjID = mt_rand().mt_rand();
	//echo $subjID;

// Randomly assign participant to counterbalancing list
	$list = mt_rand(1,8);
	//echo 'list: '.$list;

// Read in materials from that list 
	$materials = array();
	
	$materials_file = fopen("NC2speaker_lists/NC2speaker_list".$list.".csv", "r");

	$header = fgetcsv($materials_file);
	//var_dump($header) ;
	while ($row = fgetcsv($materials_file)) {
		$materials[] = array_combine($header, $row);
	}

	fclose($materials_file);
	

	shuffle($materials);

	
	//Build the big ol' Presentation Matrix
	//This matrix is basically the "materials" array, but is in the order we want to present the trials
	$TotalTrials = 130;
	$PresMatrix = $materials;
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
<form name="Typing" method="post" action="done.php">	
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
	<h4>The following sentences are transcriptions of sentences spoken by two speakers, <font color="red">Jason</font> and <font color="blue">Thomas</font>. Some of these sentences may contain errors. Please RETYPE each sentence and fix any errors. It is fine to copy and paste the sentence and then edit it if you see an error.</h4>

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
		
		if (strcmp($PresMatrix[$TrialNum]['Condition'], "Speaker_Error") ) {
		    $Name = "Jason";
		    $color = "red";
		} else {
		    $Name = "Thomas";
		    $color = "blue";
		}
		//The sentence and the comprehension question which get displayed
		echo '<input type="hidden" name="Sentence_'		 	. $index . '" value="' . $PresMatrix[$TrialNum]['Sentence']	. '">';
		echo '<div class="sentence"><font color="'.$color.'"> ' .$Name.": ". $PresMatrix[$TrialNum]['Sentence'] . '</font></div>';
		
		//Input box for answers
		echo '<input type="text" name="Response_' . $index  . '" size= "100" "' .'/>';
		
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
	<!--Submit-->
	<input name="submit" type="submit" value="Submit">
</div>
</div>

</div> <!-- wrapper -->

</form>

</body>

</html>