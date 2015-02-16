<?php
/**
  * riddles.php
  * Author: Olga Smirnova
  * November 2014
  * display the feed of all admin's posts 
*/


session_start();

//only users can visit this page
if(!isset($_SESSION['logged']) && !isset($_SESSION['current_id']))
{
    header('Location: index.php');
}

#############   REQIERED      #################################################
require_once "../conf/config.php";
require_once CLASS_PATH.'/pdo.class.php';
require_once CLASS_PATH.'/security.class.php';
require_once CLASS_PATH.'/html.class.php';

   
    
############    PREPARATION   #################################################
    $db = new DB(); //create PDO obj

    $id = 4; //id of page from tbl_pages
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
    if($_SESSION['current_id']==1)
    {   
        $aNavigation = $db->query("SELECT id, menu_name, page_name FROM tbl_pages WHERE hide=1 OR hide=4", null, PDO::FETCH_NUM);
    }else{
        $aNavigation = $db->query("SELECT id, menu_name, page_name FROM tbl_pages WHERE hide=3 OR hide=4", null, PDO::FETCH_NUM);
    }
    
    $oHTML = new makeHTML(); //create an obj of page

//select all admin's posts
    $aPosts = $db->query('SELECT id FROM tbl_posts WHERE hide="2" AND username_id="1" ORDER BY date_entered DESC');
    $_SESSION['admin_posts'] = array(); //create array of $_SESSION['admin_posts'] (need that later to save post's info)
    foreach($aPosts as $index=>$arr)
    {
        foreach($arr as $id=>$num)
        {
            $user_id = $db->single('SELECT username_id FROM tbl_posts WHERE id ="'. $num. '"');
            $username = strtoupper($db->single('SELECT username FROM tbl_users WHERE id ="'. $user_id. '"'));
            $post_title = strtoupper($db->single('SELECT post_title FROM tbl_posts WHERE id="'. $num. '"'));
            $post = $db->single('SELECT post FROM tbl_posts WHERE id="'. $num. '"');
            $comment_num = $db->single('SELECT comment_num FROM tbl_posts WHERE id="'. $num. '"');
            $date = $db->single('SELECT date_entered FROM tbl_posts WHERE id="'. $num. '"');
            $timestamp = date('g:ia, j F Y', strtotime($date));
            $picture = $db->single('SELECT img_name FROM tbl_posts WHERE id="'. $num. '"');
            $answer = $db->single('SELECT riddle_answer FROM tbl_posts WHERE id="'. $num. '"');
            $deadline = $db->single('SELECT dead_line FROM tbl_posts WHERE id="'. $num. '"');
           
            array_push($_SESSION['admin_posts'], $oHTML->postRiddle($username, $timestamp, $post_title, $post, $comment_num, $picture, $answer, $deadline, $num));
        }
    }

/* listens search_btn
 * if pressed => go to search.php with saved $_GET['search']
*/
    if(!empty($_GET['search_btn']))
    {
        $_SESSION['search'] = $_GET['search'];
        header('Location: search.php');
    }

############    PRESENTATION   #################################################
    echo $oHTML->makeHeader($title, $description, $headline, $subline);
    echo $oHTML->drawRibbon();
?>
        <nav>
            <ul class="cf">
                <?php echo $oHTML->makeNavigation($aNavigation, "Riddles"); ?>
            </ul>
        </nav>
        
    </header>
        
        
<?php   echo "\n".
'<!--******************************************************************************************************************-->'. "\n".
    '<!-- MAIN CONTENT -->'. "\n".
'<!--******************************************************************************************************************-->'. "\n";
?>
        <div id="container">
                <a data-scroll href="#top" class="scrollToTop"><i class="fa fa-arrow-up"></i> Back to top</a>
           
                <?php //display all posts of admin
                    foreach($_SESSION['admin_posts'] as $ind=>$item)
                    {
                        echo $item;
                    }
                ?>
 
    <?php echo $oHTML->makeFooter($script_common, $script_scroll1, $script_scroll2, $script_friends);
//EOF riddles.php