<?php
/**
  * goodbye. php
  * Author: Olga Smirnova
  * November 2014
  * 1 -writes the "Goodbye"-message after user had deleted an account
*/

session_start();
unset($_SESSION['logged']);
unset($_SESSION['current_id']);

#############   REQIERED      #################################################

	require_once "../conf/config.php";
	require_once CLASS_PATH.'/pdo.class.php';
	require_once CLASS_PATH.'/security.class.php';
	require_once CLASS_PATH.'/html.class.php';
	
############    PREPARATION   #################################################
	$db = new DB(); //create PDO obj

	$id = 10; //id of page from tbl_pages
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
*/
	$aNavigation = $db->query("SELECT id, menu_name FROM tbl_pages WHERE hide=3", null, PDO::FETCH_NUM);
	
	$oHTML = new makeHTML(); // creating an obj of the page


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
						<p class="timestamp"><?php echo date('g:ia, j F Y', strtotime('now')) //current timestamp ?></p>
					</div>
				
					<h3>FAREWELL, DEAR FRIEND</h3>
					<p class="post">Hope, you've enjoyed spending time with us. Keep improving logical thinking and remember - you are always welcome in Wonderland.<br><br>
					In 30 days there is a possibility to restore your account. Just send a note to this address: <span>ladiez.os@gmail.com</span><br><br></p>
					
					<div id="bye">
						<p>Bye for now.</p>
						<div id="rabbit3"></div>
					</div>
					<div class="grey_bcgr">
					</div>
				
				</section>
			</main>	
  
 
	<?php echo $oHTML->makeFooter($script_common, $script_signin);	
//EOF goodbye.php   