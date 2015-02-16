<?php
/**
  * search.php
  * Author: Olga Smirnova
  * November 2014
  * 1 -filter and process the input from search field
  * 2 -aks DB for match to serch_input
  * 3 -writes the posts with match
*/


session_start();

//only registred users can visit this page
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

// when "Search_btn" was pressed
    if(isset($_SESSION['search']))
    {
        if(empty($_SESSION['search'])) // if user pressed search_btn without typing the word tosearch
        {
            $error = "<h4>You forgot to enter a search term.</h4>";
        }else{
            $str_to_find = strtoupper($_SESSION['search']); //need it to make searching procedure case-insensitive
            $str_to_find = strip_tags($str_to_find); //to take out any code user may have tried to enter into searchbox
            $str_to_find = trim ($str_to_find); //to take out all whitespaces that may have been put by accident

            $db = new DB(); //create PDO obj

            //ask DB to select id of those posts, that contain searching word
            $searchData = $db->query('SELECT id FROM tbl_posts WHERE upper(`post`) LIKE "%'. $str_to_find. '%"');
        
            //create array of $_SESSION['search_results'] (need that later)
            $_SESSION['search_results'] = array();

            if(!empty($searchData))
            {
                foreach($searchData as $index=>$arr)
                {
                    foreach($arr as $id=>$num) //$num - id of the post with match as it is in tbl_posts
                    {
                        $username_id = $db->single('SELECT username_id FROM tbl_posts WHERE id ="'. $num. '"');
                        $username = strtoupper($db->single('SELECT username FROM tbl_users WHERE id ="'. $username_id. '"'));
                        $post_title = strtoupper($db->single('SELECT post_title FROM tbl_posts WHERE id="'. $num. '"'));
                        $post = $db->single('SELECT post FROM tbl_posts WHERE id="'. $num. '"');
                        $comment_num = $db->single('SELECT comment_num FROM tbl_posts WHERE id="'. $num. '"');
                        $date = $db->single('SELECT date_entered FROM tbl_posts WHERE id="'. $num. '"');
                        $timestamp = date('g:ia, j F Y', strtotime($date));
                        $picture = $db->single('SELECT img_name FROM tbl_posts WHERE id="'. $num. '"');
                        $post_id = $num;
                        $statement = $db->single('SELECT statement FROM tbl_users WHERE id ="'. $username_id. '"');
                        $avatar = $db->single('SELECT avatar FROM tbl_users WHERE id ="'. $username_id. '"');
                        $comment_num = $db->single('SELECT comment_num FROM tbl_posts WHERE id ="'. $num. '"');

                        $answer = $db->single('SELECT riddle_answer FROM tbl_posts WHERE id="'. $num. '"');
                        $deadline = $db->single('SELECT dead_line FROM tbl_posts WHERE id="'. $num. '"');

                        //replace words, that matches serching words to red-colored and uppercase one
                        $post =str_ireplace($str_to_find, '<span style="color: red; font-weight:900; text-decoration: none;">'. strtoupper($str_to_find). '</span>', $post);

                        //add id of selected posts to $_SESSION['search_results'] array
                        array_push($_SESSION['search_results'], $num);
                        $results[$num] = array($post_id, $username, $statement, $timestamp, $avatar, $picture, $post_title, $post, $comment_num, $username_id, $answer, $deadline);
                    }
                }
            }else{
                $error = '<h4>No results for searhing '. $str_to_find. '</h4>'. "\n";
            }
        }
    }

    $message = (isset($error))? $error: '<h4>Results for serching: '. $str_to_find. '</h4>';

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
                <?php echo $oHTML->makeNavigation($aNavigation); ?>
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
                
                <?php
                        echo '<h3>'. $message .'</h3>';
                        foreach($_SESSION['search_results'] as $ind=>$postid)
                        {
                            //write selected posts
                            echo  $oHTML->posts($results[$postid][0], $results[$postid][1], $results[$postid][2], $results[$postid][3], $results[$postid][4], $results[$postid][5], $results[$postid][6], $results[$postid][7], $results[$postid][8], $results[$postid][9], $results[$postid][10], $results[$postid][11]);
                        }
                        
                ?>
 
    <?php echo $oHTML->makeFooter($script_common, $script_scroll1, $script_scroll2, $script_friends);
//EOF search.php