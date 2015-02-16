<?php
/**
  * leavecomment.php
  * Author: Olga Smirnova
  * November 2014
  * 1 -display the post, user aims to comment
  * 2 -display all comments that have been recieved for that post
  * 3 -writes a form for adding new comment
  * 4 -after validating and filtering all input data, put the new comment into DB and
  * 5 -sends an email to post author, telling him/her, that new comment was recieved to his/her post
*/

session_start();

//only users can visit this page
if(!isset($_SESSION['logged']) || !isset($_SESSION['current_id']))
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

    $id = 6; //id of page from tbl_pages
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

/* preparation for writing form:
 * field_name=>array(
 *                  'valid'=>array(list of all methods to validate this field input),
 *                  'msg'=>'vlidation message for this field')
*/  
    $aForm = array(
                   'comment'=>array(
                                    'valid'=>array('checkEmpty', 'checkName', 'checkLength_2000'),
                                    'msg'=>''
                                    ),
                   );  
    
    $oHTML = new makeHTML(); //create an obj of page
    $oForm = new makeHTML($aForm); //form to write comment

    $formHTML = $oForm->openForm()
                        .$oForm->makeTextareaRowVal('comment', 'Comment:', '', 'leave_comment')
                        .$oForm->submitButton('comment_it', 'Submit')
                        .$oForm->closeForm();

    if(is_numeric($_GET['id'])) //to filter $_GET['id']
    {
        $curr_postid = $_GET['id'];
    }

// if "Submit_btn" was pressed 
    if(!empty($_POST))
    {
        if($oForm->checkPOSTdata($_POST))
        {
            array_pop($oForm->aFiltered); //in order to do not put submit_btn into DB
            $db->bind('username_id', $_SESSION['current_id']);
            $db->bind('post_id', $curr_postid);
            $db->bind('comment',$oForm->aFiltered['comment']);
            
            $insert = $db->query("INSERT INTO tbl_comments SET
                                username_id =:username_id,
                                post_id =:post_id,
                                comment =:comment");
            //insert new comment into DB
            if($insert==1)
            {
                $add_to_sum = $db->query('UPDATE tbl_posts SET comment_num = comment_num+1 WHERE id="'. $curr_postid. '"');

                //email post's author to let him/her know, that someone have commented his/her post
                $subject = 'From:noreply@yourwebsite.com Wonderland';
                $msg = 'Hi, '. ucfirst($username_mail).'!'. "\n". "\n"
                            .'Someone just have left a comment to your post. You can check it by following the link below:'. "\n"
                            .'http://olga.smirnova.yoobee.net.nz/_Assignments/WE03/public_html/leavecomment.php?id='. $curr_postid
                            ."\n". "\n"
                            .'King regards,'. "\n"
                            .'White Rabbit' . "\n". "\n"
                            .'Reply-To: ladiez.os@gmail.com';

                // use wordwrap() if lines are longer than 70 characters
                $msg = wordwrap($msg,70);
                mail($mailname, $subject ,$msg);
            }
        }
    }

//variables from DB for displaying that particular post, which user aims to comment/ to read it's comments
    $nolink = 1; //use it in function, that writes the post, if ==1 => make "Leave comment" and "Read comments" links unactive

    $arr_DB = $db->row('SELECT img_name, post_title, post, comment_num, username_id, riddle_answer, dead_line FROM tbl_posts WHERE id="'. $curr_postid. '"');
    $post_title = strtoupper($arr_DB['post_title']);
    $username_id = $arr_DB['username_id'];
    $timestamp = date('g:ia, j F Y', strtotime($arr_DB['dead_line']));

    $arr_userDB = $db->row('SELECT username, statement, avatar, email FROM tbl_users WHERE id ="'. $username_id. '"');
    $username = strtoupper($arr_userDB['username']); 
    $statement = strtolower($arr_userDB['statement']);


//all comments, recieved for that post
    $aComments = $db->query('SELECT id FROM tbl_comments WHERE post_id="'. $curr_postid. '" ORDER BY date_entered ASC');
    $_SESSION['comments'] = array(); //create array of $_SESSION['comments'] (need that later)
    foreach($aComments as $index=>$arr)
    {
        foreach($arr as $id=>$num) //$num - is an id of comment as it is in tbl_comments
        {
            $username_com_id = $db->single('SELECT username_id FROM tbl_comments WHERE id="'. $num. '"');
            $username_com = strtoupper($db->single('SELECT username FROM tbl_users WHERE id="'. $username_com_id. '"'));
            $timestamp_com = date('g:ia, j F Y', strtotime($db->single('SELECT date_entered FROM tbl_comments WHERE id="'. $num. '"')));
            $post_com = $db->single('SELECT comment FROM tbl_comments WHERE id="'. $num. '"');
            array_push($_SESSION['comments'], $oHTML->commentRecieved($username_com_id, $username_com, $timestamp_com, $post_com));
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
                <section class="basic" id="grey">

                    
                    <?php echo $oHTML->posts($post_id, $username, $statement, $timestamp, $arr_userDB['avatar'], $arr_DB['img_name'], $post_title, $arr_DB['post'], $arr_DB['comment_num'], $username_id, $arr_DB['riddle_answer'], $arr_DB['dead_line'], $nolink); ?>

                    <?php echo $oHTML->drawRibbon(); ?>
                </section> <!-- end of basic grey -->

                <main>
                    <section class="basic">
                            <?php echo $formHTML; //form for sending a comment ?>
                    </section>

                    <?php // write all comments, that have been recieved to that post
                        foreach($_SESSION['comments'] as $ind=>$item)
                        {
                            echo $item;
                        }
                    ?>
                </main>
 
    <?php echo $oHTML->makeFooter($script_common, $script_scroll1, $script_scroll2, $script_leavecomment);
//EOF leavecomment.php