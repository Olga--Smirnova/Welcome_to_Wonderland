<?php
/**
  * signin.php
  * Author: Olga Smirnova
  * November 2014
  * 1 -writes form for signing in
  * 2 -validates, filters all input data
  * 3 -put info about new user into DB (tbl_user)	
  * 4 -sends new user email with link to his/her profile page
  * 5 -sends email to admin, telling that new user was registered
  * 6 -redirect new user to thankyou page
*/


session_start();

#############   REQIERED      #################################################
require_once "../conf/config.php";
require_once CLASS_PATH.'/pdo.class.php';
require_once CLASS_PATH.'/security.class.php';
require_once CLASS_PATH.'/html.class.php';
require_once CLASS_PATH.'/uploadimage.class.php';

	
	
############    PREPARATION   #################################################
	$db = new DB(); //create PDO obj

	$id = 2; //id of page from tbl_pages
	$db->bind("id", $id);

//create short vars to use in the template
	$aContent = $db->row("SELECT page_title, page_description, page_headline, page_subline FROM tbl_pages WHERE id = :id");	
	$title = $aContent['page_title'];
	$description = $aContent['page_description'];
	$headline = $aContent['page_headline'];
	$subline = $aContent['page_subline'];
	
/* navigation - base on tbl_pages data (menu_name, id, page_name)
 * if you are admin, you will have "Admin" link in nav
 * if you are user, you will have "My Profile" link in nav
 * on this page nav is hidden
*/
	 $aNavigation = $db->query("SELECT id, menu_name, page_name FROM tbl_pages WHERE hide=3", null, PDO::FETCH_NUM);

/* preparation for writing form:
 * field_name=>array(
 *                  'valid'=>array(list of all methods to validate this field input),
 *                  'msg'=>'vlidation message for this field')
*/ 	
	$aForm = array(
    				'new_username'=>array(
        							'valid'=>array('checkEmpty', 'checkLength_16', 'checkName'),
        							'msg'=>''
       			 	),
   					'new_password'=>array(
        						'valid'=>array('checkEmpty', 'checkLength_18', 'checkName'),
        						'msg'=>'*8 characters at least'
        			),
        			'mail'=>array(
        						'valid'=>array('checkEmpty', 'checkMail'),
        						'msg'=>''
        			),
        			'statement'=>array(
        						'valid'=>array('checkEmpty', 'checkTextarea', 'checkLength_100'),
        						'msg'=>''
        			),
        			'answer'=>array(
        						'valid'=>array('checkEmpty'),
        						'msg'=>''
        			)
    		);


	$oHTML = new makeHTML(); //create an obj of a page
	$oForm = new makeHTML($aForm); //form for sign in procedure

// while "Sign in_btn" is not pressed
	
					
// if "Sign in_btn" was pressed
	if(!empty($_POST))
	{
	
		if($oForm->checkPOSTdata($_POST))
		{	
			array_pop($oForm->aFiltered); //in order to do not put submit_btn into DB
		
			$db->bindMore($oForm->aFiltered);
			
			//check if this username already exists in DB
			$username = strtolower($oForm->aFiltered['new_username']);
			$name = $db->single('SELECT id FROM tbl_users WHERE username="'. $username. '"');
			if($name==1) //such username already exists
			{
				$oForm->nameExist('new_username');
				$error = '*Sorry, could you please try again';
			}else{	//such username not exists yet
				
				// to check if such email already exists in DB
				$mailname = strtolower($oForm->aFiltered['mail']);
				$mail = $db->single('SELECT id FROM tbl_users WHERE email="'. $mailname. '"');
				if($mail==1) //such email already exists
				{
					$oForm->nameExist('mail');
					$error = '*Sorry, could you please try again';
				}else{ //such email not exists yet
					
					$oImg = new uploadImage(); //create an obj of an uploading file 
					$new_imagename = $oImg->upload();
					if($oForm->checkPic($new_imagename, 'avatar')) //check if image has been uploaded successfully
					{
						$db->bind('new_username', $username);
					
						$pass = $oForm->createPassword($oForm->aFiltered['new_password']); //to hash new_password input
						$db->bind('new_password', $pass);
						$db->bind('mail', $mailname);
						$db->bind('avatar', $new_imagename);
						$db->bind('statement', $oForm->aFiltered['statement']);
						$db->bind('answer', $oForm->aFiltered['answer']);
						
						//insert new user info into DB
						$insert = $db->query("INSERT INTO tbl_users SET
                                								username =:new_username,
                                								password =:new_password,
                                								email =:mail,
                                								avatar =:avatar,
                                								statement =:statement,
                                								logic_attitude =:answer");
						if($insert==1)
						{	
							$DB_row = $db->row($oForm->PrepStatement('username', 'tbl_users'));
							$_SESSION['logged'] = $DB_row;

							$DB_id = $db->single('SELECT id FROM tbl_users WHERE username="'. $username. '" AND password="'. $pass. '"');
							$_SESSION['current_id'] = $DB_id;
						
							//email a link to new_profile page
							$subject = 'From:noreply@yourwebsite.com Wonderland registration';
							$msg = 'Hi, '. ucfirst($username).'!'. "\n". "\n"
							.'There is a link to your personal profile page:'
							.'http://olga.smirnova.yoobee.net.nz/_Assignments/WE03/public_html/profile.php?id='. $_SESSION['current_id']
							."\n". "\n"
							.'King regards,'. "\n"
							.'White Rabbit' . "\n". "\n";

							// use wordwrap() if lines are longer than 70 characters
							$msg = wordwrap($msg,70);

							mail($mailname, $subject ,$msg);
							
							//email admin abou new user
							mail('ladiez.os@gmail.com', 'new_user', $username);
							
							//send user to thankyou page
							header('Location: thankyou.php');
						}else{ 
							$error = '*Sorry, could you please try again';
						} 
					}else{
						$error = '*Sorry, could you please try again';
					} 
				}
			}
		}else{
			$error = '*Sorry, could you please try again';
		}
	}
	$formHTML = $oForm->openFormImg('signin_proc')
								.$oForm->makeInputRowVal('new_username', 'Username:', 'text')
								.$oForm->makeInputRowVal('new_password', 'Password:', 'password', '', '', ' validation_msg_grey')
								.$oForm->makeInputRowVal('mail', 'email:', 'text')
								.$oForm->makeInputRowVal('avatar', 'Avatar:', 'file', 'step2')
								.$oForm->makeTextareaRowVal('statement', 'About you:')
								.$oForm->makeRadioRowVal('answer', 'Are you crazy about logic?', 'yey', 'step3', 'answer')
								.$oForm->makeRadioVal('answer', 'nay')
								.$oForm->submitButton('true_sign_btn', 'Sign in')
								.$oForm->closeForm();

	$message = (isset($error))? $error: '*Read the directions and directly you will be directed in the right direction';
	
############    PRESENTATION   #################################################
	echo $oHTML->makeHeader($title, $description, $headline, $subline);
	echo $oHTML->drawRibbon();
?>
        <nav>
			<ul class="cf">
				<?php echo $oHTML->makeNavigationHidden($aNavigation); ?>
			</ul>
		</nav>
		
	</header>
		
		
<?php	echo "\n".
'<!--******************************************************************************************************************-->'. "\n".
	'<!-- MAIN CONTENT -->'. "\n".
'<!--******************************************************************************************************************-->'. "\n";
?>
		<div id="container">
			<main class="cf">

				<section class="basic">
					<div id="rabbit_path">

						<div class="green_line_long"></div>
						<div id="rabbit1"></div>
						<div class="green_line_long"></div>
						<div id="rabbit2"></div>
						<div class="green_line_longest"></div>
						<div id="rabbit3"></div>
					</div>

					<div id="signin_form">

						<h3>CREATE NEW ACCOUNT</h3>
						<p class="validation_msg validation_msg_grey" id="nomarg_valid_msg"><?php echo $message; ?></p>
						
						<?php echo "\n". $formHTML. "\n"; ?>

					</div> <!-- end of div id="signin_form" -->
					
				</section>

			</main>
  
 
	<?php echo $oHTML->makeFooter($script_common, $script_signin);
	
// EOF signin.php