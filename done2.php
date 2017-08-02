<?php
/*
DESCRIPTION
Writes the inputs from form.php to a data file called 'data_[subjID].csv'
The inputs are
	* subjID
	* TrialNum
	* Condition
	* ItemNum
	* Sentence
	* Response
	* List
	* BlockOrder

Writes the onset information to 'onsets.csv'
The inputs are
	* subjID
	* start_date
	* start_time
	* end_date	(calculated in this file)
	* end_time	(calculated in this file)


RECEIVES DATA FROM
form.php


AUTHOR
Zach Mineroff (zmineroff@gmail.com)
Edited by Rachel Ryskin
*/


	//ONSETS FILE
	//Get the date and time
	date_default_timezone_set('UTC');	//Set the timezone to UTC
	$end_date =  date('n\/d\/Y');		//month/day/year
	$end_time = date('H:i:s');			//24hour:minutes:seconds
	//Name of the onsets file is the same for all subjects.
	$onsets_file = 'results/onsets.csv';

	$code = $_POST['subjID'];
	
	//Open the onsets file
	$handle = fopen($onsets_file, 'a') or die('Cannot open file: '.$onsets_file);
	
	//Write the onsets info to the file
	fwrite($handle, $_POST['subjID'] . ',');
	fwrite($handle, $_POST['start_date'] . ',');
	fwrite($handle, $_POST['start_time'] . ',');
	fwrite($handle, $end_date . ',');
	fwrite($handle, $end_time );
	fwrite($handle, PHP_EOL);
	
	fclose($handle);
	
	//echo $_POST['Response_1'];
	//echo $_POST['Response_130'];
	
	//DATA FILE
	//Name the data file. If it already exists, add a -1, -2, -3, etc to the name
	$data_file = 'results2/data_'.$_POST['subjID'].'.csv';
	
	$info = pathinfo($data_file);
	$additional = 1;
	while (file_exists($data_file)) {
		$data_file = $info['dirname'] . '/'
					 . $info['filename'] . '-' . $additional
					 . '.' . $info['extension'];
		
		++$additional;
	}
	
	//Open the data file
	$handle = fopen($data_file, 'a') or die('Cannot open file: '.$data_file);
	
	//Create the header for the data file
	fwrite($handle, 'subjID' . ',');
	fwrite($handle, 'TrialNum' . ',');
	fwrite($handle, 'List' . ',');
	fwrite($handle, 'BlockOrder' . ',');
	fwrite($handle, 'ItemNum' . ',');
	fwrite($handle, 'Condition' . ',');
	fwrite($handle, 'TrialType' . ',');
	fwrite($handle, 'SentenceType' . ',');
	fwrite($handle, 'Sentence' . ',');
	fwrite($handle, 'Response');
	fwrite($handle, PHP_EOL);
	
	//Write the data to the file
	$NumTrials = 130;
	for ($trial = 1; $trial <= $NumTrials; ++$trial) {
		//Determine the field names (fieldName_trial)
		$field1 = 'subjID'					;
		$field2 = 'TrialNum_'		. $trial;
		$field3 = 'List_'			. $trial;
		$field4 = 'BlockOrder'				;
		$field5 = 'ItemNum_'		. $trial;
		$field6 = 'Condition_'		. $trial;
		$field7 = 'TrialType_'		. $trial;
		$field8 = 'SentenceType_'	. $trial;
		$field9 = 'Sentence_'		. $trial;
		$field10 = 'Response_'		. $trial;
		
		//Write results
		fwrite($handle, $_POST[$field1] . ',');
		fwrite($handle, $_POST[$field2] . ',');
		fwrite($handle, $_POST[$field3] . ',');
		fwrite($handle, $_POST[$field4] . ',');
		fwrite($handle, $_POST[$field5] . ',');
		fwrite($handle, $_POST[$field6] . ',');
		fwrite($handle, $_POST[$field7] . ',');
		fwrite($handle, $_POST[$field8] . ',');
		fwrite($handle, $_POST[$field9] . ',');
		fwrite($handle, $_POST[$field10]);
		fwrite($handle, PHP_EOL);
	}
	
	fclose($handle);
	
?>

<html>
	<head>
		<title>Survey</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	</head>
	<body>
		<!--Thank you, your responses have been stored!-->
		Thank you, your responses have been stored!
		</br>

	</body>
</html>
<?php

	echo "Please enter the following code on Amazon Mechanical Turk: ". substr(strval($code),0,5)

?>