<?php
/**
  * Update_comment.php
  * Author: Olga Smirnova
  * November 2014
  * 1 -make a list of all comments
  * 2 -after choosing a comment which admin aims to update, save id of that comment as $_GET['update'] and writes sticky form, filled with data from DB
  * realisation: if(isset($_GET['update']) && is_numeric($_GET['update'])=>show sticky form, filled with data from DB }else{ => show the list of all comments
*/



session_start();

//only admin can visit this page
if(!isset($_SESSION['logged']) || ($_SESSION['current_id']!=1))
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

    $id = 7; //id of page from tbl_pages
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
    $aNavigation = $db->query("SELECT id, menu_name, page_name FROM tbl_pages WHERE hide=1 OR hide=4", null, PDO::FETCH_NUM);

//admin's options menu
    $admNav = array(
                    'Post_riddle',
                    'Load_img_riddle',
                    'Update_riddle',
                    'Update_user',
                    'Update_post',
                    'Update_comment'
                    );

/* preparation for writing form:
 * field_name=>array(
 *                  'valid'=>array(list of all methods to validate this field input),
 *                  'msg'=>'vlidation message for this field')
*/
    $aFormcomment = array(
                   'comment'=>array(
                                    'valid'=>array('checkEmpty', 'checkTextarea', 'checkLength_2000'),
                                    'msg'=>''
                                    ),
                   ); 


    $oHTML = new makeHTML();
    $oForm = new makeHTML($aFormcomment);   //form to update comment
   
    if(isset($_GET['update']) && is_numeric($_GET['update']))
    {   
        $error = '*Read the directions and directly you will be directed in the right direction';
        $row = $db->row('SELECT * FROM tbl_comments WHERE id="'. $_GET['update']. '"');
        $oForm->filterEverything($row); // filter external data
        $formHTML_updatecom = $oForm->openForm()
                            .$oForm->makeTextareaRowVal('comment', 'Comment:', '', 'leave_comment')
                            .$oForm->makeRadioRowVal('hide', 'Hide:', '1', 'adm_update_com', 'hide', 'hidden')
                            .$oForm->makeRadioVal('hide', '2', 'visible')
                            .$oForm->submitButton('update_com', 'Update')
                            .$oForm->closeForm();

// if "Update_btn" was pressed        
        if(isset($_POST['update_com']))
        {
            if($oForm->checkPOSTdata($_POST))
            {  
            
                array_pop($oForm->aFiltered); //in order to do not put submit_btn into DB
               
                $db->bindMore($oForm->aFiltered);
                $db->bind('comment', $oForm->aFiltered['comment']);
                $db->bind('hide', $oForm->aFiltered['hide']);
                
                //insert updated comment into DB
                $insert = $db->query('UPDATE `tbl_comments` SET 
                                                `comment`=:comment,
                                                `hide`=:hide
                                                WHERE id="'. $_GET['update']. '"');
                
                if($insert==1)
                {   
                    $error = '*Done!';
                    // show the form filled with renewed data
                    $formHTML_updatecom = $oForm->openForm()
                                        .$oForm->makeTextareaRowVal('comment', 'Comment:', '', 'leave_comment')
                                        .$oForm->makeRadioRowVal('hide', 'Hide:', '1', 'adm_update_com', 'hide', 'hidden')
                                        .$oForm->makeRadioVal('hide', '2', 'visible')
                                        .$oForm->submitButton('update_com', 'Update')
                                        .$oForm->closeForm();
                }else{
                    $error = '*Sorry, comment was not updated, because you made no changes in it';
                }
            }else{
                $error = '*Sorry, could you please try again';
            }
        }
// $_GET['update'] is not set
    }else{
        $_SESSION['list'] = array(); //create array of $_SESSION['list'] (need that later)
        $aWRComms = $db->query('SELECT id FROM tbl_comments'); //target all comments from DB
        foreach($aWRComms as $ind=>$arr)
        {
            foreach($arr as $id=>$num) //$num - id of comment in tbl_comments
            {
                $title = $db->single('SELECT comment FROM tbl_comments WHERE id="'. $num. '"');
                $title = mb_substr($title, 0, 50). '...'; // to prevent having a very long link in the list, if the comment is long
                array_push($_SESSION['list'], $oHTML->adminoptList($num,  $title));
            }
        }
    }
    
$message = (isset($error))? $error: '*Choose comment to update';

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
                <?php echo $oHTML->makeNavigation($aNavigation, "Admin"); ?>
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
                <main class="cf">
                    <aside>
                        <ul id="admin_options">
                        <?php echo $oHTML->makeNavigationAdm($admNav, $_SERVER['PHP_SELF']);?>
                        </ul>
                    </aside>

                    <section class="admin_section">
                    <p class="validation_msg validation_msg_grey" id="nomarg_valid_msg"><?php echo $message; ?></p>

                    <?php //write the list of links to all comments
                        if(isset($_GET['update']) && is_numeric($_GET['update']))
                        {
                            echo $formHTML_updatecom;
                        }else{
                            echo "\n". '<ul class="update">'. "\n". "\t";
                            foreach($_SESSION['list'] as $ind=>$item)
                            {
                                echo $item;
                            }
                            echo "\n". '</ul>';
                        }
                    ?>


                    </section>
    
                </main>
                
 
    <?php echo $oHTML->makeFooter($script_common, $script_scroll1, $script_scroll2, $script_wrpages);
//EOF Update_comment.php   