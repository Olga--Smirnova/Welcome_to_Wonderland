<?php
/** thankyou.php
 * Author: Olga Smirnova
 * November 2014
 * 1 -writes the "Thank you"-message after new user registred into blog
 */


session_start();

//only registred user can see this page
if(!isset($_SESSION['current_id']))
{
	header('Location: signin.php');
}

#############   REQIERED      #################################################
require_once "../conf/config.php";
require_once CLASS_PATH.'/pdo.class.php';
require_once CLASS_PATH.'/security.class.php';
require_once CLASS_PATH.'/html.class.php';

	
	
############    PREPARATION   #################################################
	$db = new DB();

	$id = 8; //id of page from tbl_pages
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
*/
	$aNavigation = $db->query("SELECT id, menu_name, page_name FROM tbl_pages WHERE hide=3", null, PDO::FETCH_NUM);
	
	$username = strtoupper($db->single('SELECT username FROM tbl_users WHERE id ="'. $_SESSION['current_id']. '"'));
	$email = $db->single('SELECT email FROM tbl_users WHERE id ="'. $_SESSION['current_id']. '"');

	$oHTML = new makeHTML(); // create an obj of a page

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
				
					<div class="grey_bcgr">
						<p class="timestamp"><?php echo date('g:ia, j F Y', strtotime('now')) ?></p>
					</div>
				
					<h3>THANK YOU, <?php echo $username; ?></h3>
					<p class="post">... and congratulations! You have been registered into Wonderland successfully. We sent you a welcome message, where you can find a link to your personal profile page. Please, check your email (<?php echo '<span>'. $email. '</span>'; ?>, spam folder including).<br><br>
					Wish you to have a lovely day.</p>

					<div class="grey_bcgr">
					</div>
				
				</section>
			</main>	
  
 
	<?php echo $oHTML->makeFooter($script_common, $script_signin);	
// EOF thankyou.php