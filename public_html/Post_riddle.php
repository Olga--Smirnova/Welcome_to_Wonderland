<?php
/**
  * Post_riddle.php
  * Author: Olga Smirnova
  * November 2014
  * 1 -writes a form for creating new riddle
  * 2 -filters and validates all inputs
  * 3 -puts data of new riddle into DB
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

/*preparation for writing form:
 *field_name=>array(
 *                  'valid'=>array(list of all methods to validate this field input),
 *                  'msg'=>'vlidation message for this field')
*/
    $aFormpostriddle = array(
                            'mypost_title'=>array(
                                            'valid'=>array('checkEmpty', 'checkName', 'checkLength_60'),
                                            'msg'=>''
                            ),
                            'mypost'=>array(
                                        'valid'=>array('checkEmpty', 'checkTextarea', 'checkLength_2000'),
                                        'msg'=>''
                            ),
                            'deadline'=>array(
                                        'valid'=>array('checkEmpty', 'checkLength_60', 'checkName'),
                                        'msg'=>''
                            ),
                            'riddle_answer'=>array(
                                        'valid'=>array('checkEmpty', 'checkTextarea', 'checkLength_2000'),
                                        'msg'=>''
                            )
                            
                        );


    $oHTML = new makeHTML(); //create an obj of page
    $oForm = new makeHTML($aFormpostriddle);   //form to post a riddle

// if "Post_btn" was pressed    
    if(isset($_POST['post_rid']))
    {
        if($oForm->checkPOSTdata($_POST))
        {
            array_pop($oForm->aFiltered); //in order to do not put submit_btn into DB
        
            $db->bindMore($oForm->aFiltered);
            $db->bind('username_id', $_SESSION['current_id']);
            $db->bind('mypost_title', $oForm->aFiltered['mypost_title']);
            $db->bind('mypost', $oForm->aFiltered['mypost']);
            $db->bind('deadline', $oForm->aFiltered['deadline']);
            $db->bind('riddle_answer', $oForm->aFiltered['riddle_answer']);
        
            //inset new post info into DB
            $insert = $db->query("INSERT INTO tbl_posts SET
                                    username_id =:username_id,
                                    post_title =:mypost_title,
                                    post =:mypost,
                                    dead_line =:deadline,
                                    riddle_answer =:riddle_answer");
            
            if($insert==1)
            {   
                $error = '*Done!';
            }else{
                $error = '*Sorry, could you please try again';
            }

        }else{
            $error = '*Sorry, could you please try again';
        }
    }

$formHTML_postriddle = $oForm->openForm()
                        .$oForm->makeInputRowVal('mypost_title', 'Title:', 'text')
                        .$oForm->makeTextareaRowVal('mypost', 'Post:')
                        .$oForm->makeInputRowVal('deadline', 'Deadline:', 'text')
                        .$oForm->makeTextareaRowVal('riddle_answer', 'Answer:')
                        .$oForm->submitButton('post_rid', 'Post')
                        .$oForm->closeForm();
    
$message = (isset($error))? $error: '*Read the directions and directly you will be directed in the right direction';

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
                        <?php echo $oHTML->makeNavigationAdm($admNav, $_SERVER['PHP_SELF']); ?>
                        </ul>
                    </aside>

                    <section class="admin_section">
                    <p class="validation_msg validation_msg_grey" id="nomarg_valid_msg"><?php echo $message; ?></p>
                    <?php echo $formHTML_postriddle; ?>

                    </section>
    
                </main>
                
 
    <?php echo $oHTML->makeFooter($script_common, $script_scroll1, $script_scroll2, $script_wrpages);
//EOF wr_postriddle.php   