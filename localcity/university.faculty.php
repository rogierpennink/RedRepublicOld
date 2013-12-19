<?
$nav = "localcity";

$AUTH_LEVEL = 0;
$CHARNEEDED = true;
$REDIRECT = true;
include_once "../includes/utility.inc.php";
$char = new Player( getUserID() );

/* Check if this city has a university at all! */
$q = $db->query( "SELECT * FROM localcity INNER JOIN businesses ON localcity.business_id=businesses.id WHERE business_type=5 AND location_id=". $db->prepval( $char->location_nr ) );
if ( $db->getRowCount( $q ) == 0 )
{
	$_SESSION['error'] = "This city does not have a university you can go to!";
	header( "Location: index.php" );
	exit;
}
$business = $db->fetch_array( $q );

/* Check if a valid faculty has been selected. */
$q = $db->query( "SELECT * FROM university_faculties AS l INNER JOIN university_faculty AS f ON l.faculty_id = f.faculty_id WHERE f.abbreviation=". $db->prepval( $_GET['faculty'] ) ." AND business_id=". $business['business_id'] );
if ( $db->getRowCount( $q ) == 0 )
{
	$_SESSION['error'] = "This university doesn't have the faculty you were trying to get into!";
	header( "Location: university.php" );
	exit;
}
$faculty = $db->fetch_array( $q );

/* Check if the character is not studying somewhere else already. */
if ( $db->getRowCount( $db->query( "SELECT * FROM char_degrees WHERE character_id=". $char->character_id ." AND business_id<>". $business['business_id'] ." AND degree_type=". $db->prepval( $faculty['abbreviation'] ) ) ) > 0 )
{
	$_SESSION['error'] = "You are already studying for your ". $faculty['study_name'] ." Degree at another university!";
	header( "Location: university.php" );
	exit;
}

include_once "../includes/header.php";
?>

<h1>The <?=$char->location ." ". $faculty['full_name'];?></h1>
<p><?=str_replace( "$(CITY)$", $char->location, $faculty['description2'] );?></p>

<?
if ( isset( $_POST['subscribe'] ) )
{
	/* Check if there isn't a subscription for this player already. */
	$q = $db->query( "SELECT * FROM char_degrees WHERE character_id=". $char->character_id ." AND degree_type=". $db->prepval( $faculty['abbreviation'] ) );
	if ( $db->getRowCount( $q ) > 0 )
	{
		?><div style="width: 90%; margin-left: auto; margin-right: auto;">
			<p><strong>You have already subscribed to a course in <?=$faculty['study_name'];?>!</strong></p>
		</div><?
	}
	else
	{
		/* Check fields. */
		if ( strlen( $_POST['fname'] ) < 3 )
		{
			?><div style="width: 90%; margin-left: auto; margin-right: auto;">
				<p><strong>You must enter a first name larger than or equal to three characters!</strong></p>
			</div><?
		}
		elseif ( strlen( $_POST['lname'] ) < 3 )
		{
			?><div style="width: 90%; margin-left: auto; margin-right: auto;">
				<p><strong>You must enter a last name larger than or equal to three characters!</strong></p>
			</div><?
		}
		elseif ( !is_numeric( $_POST['banknum'] ) && strlen( $_POST['banknum'] ) != 7 )
		{
			?><div style="width: 90%; margin-left: auto; margin-right: auto;">
				<p><strong>Please enter your seven-digit bank account number!</strong></p>
			</div><?
		}
		elseif ( isset( $_POST['nopay'] ) && strlen( $_POST['afname'] ) < 3 )
		{
			?><div style="width: 90%; margin-left: auto; margin-right: auto;">
				<p><strong>You must enter an authorized person's first name larger than or equal to three characters!</strong></p>
			</div><?
		}
		elseif ( isset( $_POST['nopay'] ) && strlen( $_POST['alname'] ) < 3 )
		{
			?><div style="width: 90%; margin-left: auto; margin-right: auto;">
				<p><strong>You must enter an authorized person's last name larger than or equal to three characters!</strong></p>
			</div><?
		}
		elseif ( isset( $_POST['nopay'] ) && !is_numeric( $_POST['abanknum'] ) && strlen( $_POST['abanknum'] ) != 7 )
		{
			?><div style="width: 90%; margin-left: auto; margin-right: auto;">
				<p><strong>Please enter your authorized person's seven-digit bank account number!</strong></p>
			</div><?
		}
		else
		{
			/* Add the new degree. */
			$db->query( "INSERT INTO char_degrees SET character_id=". $char->character_id .", degree_type='". $faculty['abbreviation'] ."', degree_exp=0, degree_status=0, business_id=". $business['business_id'] .", fname=". $db->prepval( $_POST['fname'] ) .", lname=". $db->prepval( $_POST['lname'] ) .", bank_account=". $db->prepval( $_POST['banknum'] ) .", authed=". ( isset( $_POST['nopay'] ) ? 1 : 0 ) .", auth_fname=". $db->prepval( $_POST['afname'] ) .", auth_lname=". $db->prepval( $_POST['alname'] ) .", auth_bank_account=". $db->prepval( $_POST['abanknum'] ) );

			?><div style="width: 90%; margin-left: auto; margin-right: auto;">
				<p><strong>Your subscription request has been sent to the <?=$faculty['full_name'];?> of <?=$char->location;?>'s University. Now you will have to wait for the university to decide whether you qualify for studying here!</strong></p>
			</div><?
		}
	}
}

/* Check if the subscription form should appear or not. */
$q = $db->query( "SELECT * FROM char_degrees WHERE degree_type='". $faculty['abbreviation'] ."' AND character_id=". $char->character_id );
if ( $db->getRowCount( $q ) == 0 )	// Make the subscription form appear
{
	?>
	<div style="width: 90%; margin-left: auto; margin-right: auto;">
		<div class="title" style="padding-left: 10px;">
			<?=$faculty['full_name'];?>: Subscription Form
		</div>
		<div class="content">
			<form action="university.faculty.php?faculty=<?=$faculty['abbreviation'];?>" method="post">
			<p><strong>Please note:</strong> It is required that you fill in all fields of the subscription form truthfully. Your credentials may be subject to investigation prior to accepting your subscription as a student at the <?=$faculty['full_name'];?> of the University of <?=$char->location;?></p>
			<table style="width: 100%;">
				<tr>
					<td style="width: 25%;" valign="middle">First Name:</td>
					<td style="padding-left: 30px;"><input type="text" name="fname" class="std_input" style="width: 150px;" /></td>
				</tr>
				<tr>
					<td style="width: 25%;" valign="middle">Last Name:</td>
					<td style="padding-left: 30px;"><input type="text" name="lname" class="std_input" style="width: 150px;" /></td>
				</tr>				
				<tr>
					<td style="width: 25%;" valign="middle">Bank Account: *</td>
					<td style="padding-left: 30px;"><input type="text" name="banknum" class="std_input" style="width: 150px;" /></td>
				</tr>
				<tr>
					<td style="width: 25%;" valign="middle">Payment Option:</td>
					<td style="padding-left: 30px;"><input type="checkbox" name="nopay" /> Someone else pays for my education (enter details below).</td>						
				</tr>
				<tr>
					<td style="width: 25%;" valign="middle">Authorized First Name: **</td>
					<td style="padding-left: 30px;"><input type="text" name="afname" class="std_input" style="width: 150px;" /></td>
				</tr>
				<tr>
					<td style="width: 25%;" valign="middle">Authorized Last Name: **</td>
					<td style="padding-left: 30px;"><input type="text" name="alname" class="std_input" style="width: 150px;" /></td>
				</tr>
				<tr>
					<td style="width: 25%;" valign="middle">Authorized Bank Account: **</td>
					<td style="padding-left: 30px;"><input type="text" name="abanknum" class="std_input" style="width: 150px;" /></td>
				</tr>
				<tr>
					<td style="width: 25%; margin-right: 20px;" valign="middle">Previous Education:</td>
					<td style="padding-left: 30px;">
					<?
					$q = $db->query( "SELECT * FROM university_faculties AS l INNER JOIN university_faculty AS f ON f.faculty_id=l.faculty_id WHERE business_id=". $business['business_id'] );
					while ( $r = $db->fetch_array( $q ) )
						echo "<input type=\"checkbox\" name=\"". $r['abbreviation'] ."\" /> Degree in ". $r['study_name'] ."<br />"
					?>
					</td>
				</tr>
				<tr><td style="height: 15px;" colspan="2"></td></tr>
				<tr>
					<td style="width: 100%;" colspan="2">
						<p><i>* Subscribing to a course at the University of <?=$char->location;?> means you agree to a one-time payment from your bank account to ours. In case of the student failing an exam, another payment covering the extra study costs will be made.</i></p>
						<p><i>** This information only needs to be supplied if someone else pays for your education.</i></p>
					</td>
				</tr>
				
				<tr>
					<td style="width: 100%;" colspan="2">
						<p><input type="submit" name="subscribe" value="Subscribe Now" class="std_input" /></p>
					</td>
				</tr>
			</table>
			</form>
		</div>
	</div>
	<?
}
else
{
	$r = $db->fetch_array( $q );

	/* Depending on the status of the degree, show stuff... */
	if ( $r['degree_status'] == 0 ) // Application not yet reviewed
	{
		?>
		<div style="width: 90%; margin-left: auto; margin-right: auto;">
			<div class="title" style="padding-left: 10px;">
				<?=$faculty['full_name'];?>: Your subscription status
			</div>
			<div class="content">
				<p style="text-align: center;">
				You have recently subscribed to this course in <?=$faculty['study_name'];?> but your subscription request has not yet been reviewed or accepted by the Rector Magnificus of <?=$char->location;?>'s University. Please note that you will be notified as soon as your subscription request has been accepted or declined.
				</p>
			</div>
		</div>
		<?
	}
	if ( $r['degree_status'] == 1 ) // Currently studying
	{
		if ( isset( $_POST['study'] ) && $char->study_timer <= time() )
		{
			/* Calculate the exp that is gained. */
			$exp_gained = mt_rand( $faculty['exp_per_study'] - 2, $faculty['exp_per_study'] + 2 );
			
			/* If the new exp exceeds the necessary exp, also change the status of the degree. */
			$add = "";
			$exam = false;
			if ( $r['degree_exp'] + $exp_gained >= $faculty['exp_needed'] ) 
			{
				$add = ", degree_status=2";
				$exam = true;
			}

			/* Update the degree. */
			$db->query( "UPDATE char_degrees SET degree_exp=degree_exp+$exp_gained". $add ." WHERE character_id=". $char->character_id );
			
			/* Update the character's timer and add intelligence to the character. */
			$char->setStudyTimer();
			$char->setIntellect( $char->getIntellect() + mt_rand( 80, 120 ) );

			/* Select a random fact from the database. */
			$fact = $db->getRowsFor( "SELECT fact FROM university_facts WHERE faculty_id=". $faculty['faculty_id'] ." ORDER BY RAND() LIMIT 1" );

			?>
			<div style="width: 90%; margin-left: auto; margin-right: auto;">
				<p style="text-align: center;"><strong>You successfully studied further towards getting your degree. You also learned something:</strong></p>
				<p style="text-align: center;"><i>"<?=$fact['fact'];?>"</i></p>
			</div>
			<?
		}
		?>
		<div style="width: 90%; margin-left: auto; margin-right: auto;">
			<div class="title" style="padding-left: 10px;">
				<?=$faculty['full_name'];?>: Studying
			</div>
			<div class="content">
				<p style="text-align: center;">
				You are currently studying towards getting your degree in <?=$faculty['study_name'];?>. While the University of <?=$char->location;?> is happy to help you with any problems regarding your study, it should be noted that you won't succeed without putting in motivation, discipline and effort! You aren't quite ready to test your knowledge and take the exams, but you know that in due time you will make it!
				</p>
				<form action="university.faculty.php?faculty=<?=$faculty['abbreviation'];?>" method="post">
				<p style="text-align: center;">
				<?
				if ( $exam == true )
				{
					?>You are now ready to face your exam! <a href="university.faculty.php?faculty=<?=$faculty['abbreviation'];?>">Click here</a> if you want to take your exam now!<?
				}
				elseif ( $char->study_timer > time() ) 
				{ 
					?><strong>You have recently studied further towards your degree. You cannot yet study again!</strong><? 
				}
				else
				{
					?><strong>You are eligible to study further towards your degree!</strong><br /><br/>
					<input type="submit" class="std_input" value="Study!" name="study" />
					<?
				}
				?>
				</p>
				</form>
			</div>
		</div>
		<?
	}
	if ( $r['degree_status'] == 2 ) // Examination!
	{
		if ( isset( $_POST['exam_done'] ) )
		{
			$i = 1;
			$wrong = 0; $right = 0;
			while ( isset( $_POST['question'.$i] ) )
			{
				$ans = $db->getRowsFor( "SELECT * FROM university_questions WHERE question_id=". $db->prepval( $_POST['question_id'.$i] ) );			
				if ( substr( $_POST['question'.$i], 3 ) == $ans['correct_answer'] )
				{
					$right++;
				}
				else
				{
					$wrong++;
				}

				$i++;
			}
			$score = ( $right / 10 ) * 100;
			$pass = $score >= 60;

			/* update the degree. */
			if ( $pass )
			{
				$db->query( "UPDATE char_degrees SET degree_status=3 WHERE character_id=". $char->character_id ." AND degree_type=". $db->prepval( $faculty['abbreviation'] ) );
			}
			else
			{
				$db->query( "UPDATE char_degrees SET degree_exp=". ( $faculty['exp_needed'] - $faculty['exp_per_study'] * 3 ) .", degree_status=1 WHERE character_id=". $char->character_id ." AND degree_type=". $db->prepval( $faculty['abbreviation'] ) );
			}
			?>
			<div style="width: 90%; margin-left: auto; margin-right: auto;">
				<div class="title" style="padding-left: 10px;">
					The <?=$faculty['study_name'];?> Degree Examination Results
				</div>
				<div class="content">
					<p>You have sat your exam and your results have been processed and verified. After careful examination of your answers we have concluded the following:</p>
					<p><strong>Out of 10 questions, you have answered <?=$right;?> questions correctly.</strong></p>
					<p>This leads to a score of <strong><?=$score;?>%!</strong></p>
					<p>
					<?
					if ( $pass == true ) { echo "This result is sufficient! Congratulations, you have passed your ". $faculty['study_name'] ." Exams! You can now start taking advantage of your degree in your daily job pattern."; }
					else { echo "Unfortunately, this result is not sufficient. In order to pass this exam, the University of ". $char->location ." has decided that you should have additional practise."; }
					?>
					</p>
				</div>
			</div>
			<?
		}
		else
		{
		?>
		<div style="width: 90%; margin-left: auto; margin-right: auto;">
			<div class="title" style="padding-left: 10px;">
				A <?=$faculty['full_name'];?> Examination: <?=$faculty['study_name'];?> Degree
			</div>
			<div class="content">
				<p><strong>Important:</strong> This is the cover page of your exam.</p>
				<p>You have unlimited time to complete the exam, but you may not take it home with you - you must finish it here. In order to pass you must receive a score of at least 60% - otherwise you get to do some more studying.</p>
				<form action="university.faculty.php?faculty=<?=$faculty['abbreviation'];?>" method="post">
				<?
				$q = $db->query( "SELECT * FROM university_questions WHERE faculty_id=". $faculty['faculty_id'] ." ORDER BY RAND() LIMIT 10" );
				$i = 1;
				while ( $question = $db->fetch_array( $q ) )
				{
					?>
					<p><strong>Question <?=$i;?>:</strong> <?=$question['question'];?></p>
					<p>
					<input type="radio" name="question<?=$i;?>" value="ans1" /> <?=$question['answer_1'];?><br />
					<input type="radio" name="question<?=$i;?>" value="ans2" /> <?=$question['answer_2'];?><br />
					<input type="radio" name="question<?=$i;?>" value="ans3" /> <?=$question['answer_3'];?><br />
					<input type="radio" name="question<?=$i;?>" value="ans4" /> <?=$question['answer_4'];?><br />
					<input type="hidden" name="question_id<?=$i;?>" value="<?=$question['question_id'];?>" />
					</p>
					<?
					$i++;
				}
				?>
				<p>
					<input type="submit" name="exam_done" value="Hand it in" class="std_input" />
				</p>
				</form>
			</div>
		</div>
		<?
		}
	}
	if ( $r['degree_status'] == 3 )
	{
		?>
		<div style="width: 90%; margin-left: auto; margin-right: auto;">
			<div class="title" style="padding-left: 10px;">
				You have already completed this degree!
			</div>
			<div class="content">
				<p style="text-align: center;">
				We are a little confused as to what you are doing here... Last time we checked your personal information, we were under the impression you already had a degree in <?=$faculty['study_name'];?>! You may want to return to our main hall to see if there are other studies that appeal to you.</p>
				<p style="text-align: center;"><a href="university.php">Click here to go back</a></p>
			</div>
		</div>
		<?
	}

}

include_once "../includes/footer.php";
?>