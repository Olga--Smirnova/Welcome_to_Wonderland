<?php
/**
  * index page for Wonderland blog
  * Author: Olga Smirnova
  * November 2014
  * 1 -writes form for loging in
  * 	validates, filters all input data, if the input matches username+password in DB => send user to his/her profile page
  * 	if there is no match => write a validation_msg
  * 2 -writes CTA_btn, that leeds to signin page
*/


session_start();


#############   REQIERED      #################################################
	require_once "../conf/config.php";
	require_once CLASS_PATH.'/pdo.class.php';
	require_once CLASS_PATH.'/security.class.php';
	require_once CLASS_PATH.'/html.class.php';

	
	
############    PREPARATION   #################################################
	$db = new DB(); //create PDO obj

	$id = 1; //id of page from tbl_pages
	$db->bind("id", $id);

//create short vars to use in the template
	$aContent = $db->row("SELECT page_title, page_description, page_headline, page_subline FROM tbl_pages WHERE id = :id");
	$title = $aContent['page_title'];
	$description = $aContent['page_description'];
	$headline = $aContent['page_headline'];
	$subline = $aContent['page_subline'];

	$message = '';
	
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
    				'username'=>array(
        					'valid'=>array('checkEmpty')
       			 	),
   					'password'=>array(
	        					'valid'=>array('checkEmpty')
        			),
    		);
    		
	$oHTML = new makeHTML(); //create an obj of page
	$oForm = new makeHTML($aForm); // form for logging in


// if input data didn't pass validaion methods
	if(!$oForm->checkPOSTdata())
	{
		$formHTML = $oForm->openForm('login')
						.$oForm->makeInputRow('username', 'Username:', 'text' )
						.$oForm->makeInputRow('password', 'Password:', 'password' )
						.'<p class="validation_msg top_validation">'. $message. '</p>'
						.$oForm->submitButton('log_btn', 'Log in')
						.$oForm->closeForm();
// if input data did pass validaion methods
	}else{
		if(in_array(false, $oForm->testArray))
		{
			$message = '*field can\'t be empty';
			$formHTML = $oForm->openForm('login')
						.$oForm->makeInputRow('username', 'Username:', 'text' )
						.$oForm->makeInputRow('password', 'Password:', 'password' )
						.'<p class="validation_msg top_validation">'. $message. '</p>'
						.$oForm->submitButton('log_btn', 'Log in')
						.$oForm->closeForm();
		}else{	
			$DB_pass = $oForm->PrepStatement('password', 'tbl_users');
			$hashed_pass = $db->single($DB_pass); // returns the users's password from DB user's row
			
			// check if the input password = hashed password from DB
			if($oForm->logIn($oForm->aFiltered['password'], $hashed_pass))
			{	
				$DB_row = $db->row('SELECT username FROM tbl_users WHERE username ="'. strtolower($oForm->aFiltered['username']). '" AND password="'. $hashed_pass. '"');
				if(is_array($DB_row))
				{
					//to check if user's profile was deleted by user, if so => redirect to delete page, have a look at hide in tbl_users
					$what_hide = $db->single('SELECT hide FROM tbl_users WHERE username ="'. $oForm->aFiltered['username']. '" AND password="'. $hashed_pass. '"');
					if($what_hide==1) //deleted profiles have hide=1, the rest have hide=2
					{
						header('Location: deleted.php');
					}else{

						$_SESSION['logged'] = $DB_row;
						$DB_id = $db->single('SELECT id FROM tbl_users WHERE username ="'. $oForm->aFiltered['username']. '" AND password="'. $hashed_pass. '"');
						$_SESSION['current_id'] = $DB_id;
					
						if($_SESSION['current_id']==1) 
						{
							header('Location: Post_riddle.php'); // if you are admin, after success in log in procedure, you'll be directed to admin area page
						}else{
							header('Location: profile.php?id='. $_SESSION['current_id']); // if you are user, after success in log in procedure, you'll be directed to your profile page
						}	
					}				
				}
			}else{
				$message = '*wrong Username or Password, try again.';
				// show sticky form
				$formHTML = $oForm->openForm('login')
							.$oForm->makeInputRow('username', 'Username', 'text' )
							.$oForm->makeInputRow('password', 'Password', 'password' )
							.'<p class="validation_msg top_validation">'. $message. '</p>'
							.$oForm->submitButton('log_btn', 'Log in')
							.$oForm->closeForm();
			}
		}
	}



############    PRESENTATION   #################################################
	echo $oHTML->makeHeader($title, $description, $headline, $subline);
	echo $oHTML->drawRibbon();
?>
        
    <nav>
		<ul class="cf">
			<?php echo $oHTML->makeNavigationHidden($aNavigation, $_SERVER['SCRIPT_NAME']); ?>
		</ul>
	</nav>
		
	</header>
		
		
<?php	echo "\n".
'<!--******************************************************************************************************************-->'. "\n".
	'<!-- MAIN CONTENT -->'. "\n".
'<!--******************************************************************************************************************-->'. "\n";
?>
	<div id="container">	
		<main class="cf grey_main">

			<section id="landing_login">  <!-- log in form -->
				<div class="grey_bcgr">
					<h4>Have an account?</h4>
				</div>
				<h3>JUMP TO THE RABBIT HOLE</h3>
				<p class="bubble">You can use Queen_of_Hearts as username</p>
				<?php echo "\n". $formHTML. "\n"; ?>
				<p class="bubble_up">and queenofhearts as password ...or register ;)</p>
			</section>

			<section id="landing_signin"> <!-- sign in form -->
				<div class="grey_bcgr">
					<h4>Don't have an account?</h4>
				</div>
				<h3>FOLLOW THE WHITE RABBIT</h3>
				<div id="path">
					<div id="rabbit"></div>
					<div class="green_line"></div>
				</div>

				<a href="signin.php"><button id="sign_btn">Sign in</button></a>
			</section>


		</main>
  
 
	<?php echo $oHTML->makeFooter($script_common, $script_index);
	
//EOF index.php