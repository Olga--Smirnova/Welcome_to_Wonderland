<?php
/**
  * profile.php
  * Author: Olga Smirnova
  * November 2014
  * 1 -displays user options form (to write a post, to upload an image, to delete an account)
  * 2 -validates, filters all inputs, after that put it into DB
  *     option#1, option#2 => inputs go into tbl_posts
  *     option#3 => input goes to tbl_reasons_of_leaving + in tbl_users changes hide from 2 to 1 (so that user  profile is not visible anymore) 
  * 3 -displays defaul admin's post
  * 4 - displays all posts of that user
*/


session_start();

//only users can visit this page
if(!isset($_SESSION['logged']) && !isset($_SESSION['current_id']))
{
    header('Location: index.php');
}

//if you are an admin, go to admin area page (profile page analog for admin, with more options)
if($_SESSION['current_id']==1)
{
    header('Location: Post_riddle.php');
}

#############   REQIERED      #################################################
require_once "../conf/config.php";
require_once CLASS_PATH.'/pdo.class.php';
require_once CLASS_PATH.'/security.class.php';
require_once CLASS_PATH.'/html.class.php';
require_once CLASS_PATH.'/uploadimage.class.php';

    
    
############    PREPARATION   #################################################
    $db = new DB(); //create PDO obj

    $id = 3; //id of page from tbl_pages
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
    $aNavigation = $db->query("SELECT id, menu_name, page_name FROM tbl_pages WHERE hide=3 OR hide=4", null, PDO::FETCH_NUM);

/* preparation for writing form:
 * field_name=>array(
 *                  'valid'=>array(list of all methods to validate this field input),
 *                  'msg'=>'vlidation message for this field')
*/
//form for user's option#1 (post)
$aFormpost = array(
                    'mypost_title'=>array(
                                    'valid'=>array('checkEmpty', 'checkName', 'checkLength_60'),
                                    'msg'=>''
                    ),
                    'mypost'=>array(
                                'valid'=>array('checkEmpty', 'checkTextarea', 'checkLength_2000'),
                                'msg'=>''
                    )
            );

//form for user's option#2 (upload)
$aFormupload = array(
                    'img_title'=>array(
                                'valid'=>array('checkEmpty', 'checkName', 'checkLength_60'),
                                'msg'=>''
                    ),
                    'capture'=>array(
                                'valid'=>array('checkEmpty', 'checkTextarea', 'checkLength_2000'),
                                'msg'=>''
                    )
            );

//form for user's option#3 (delete account)
$aFormdelete = array(
                    'suicide'=>array(
                                'valid'=>array('checkEmpty')
                    )
            );
    

    $oHTML = new makeHTML(); //create an obj of page
    $oForm = new makeHTML($aFormpost); // create an obj of a form, user option#1 (post)
    $oForm2 = new makeHTML($aFormupload); // create an obj of a form, user option#2 (upload)
    $oForm3 = new makeHTML($aFormdelete); // create an obj of a form, user option#3 (delete)


//if "Post_btn" for option#1 was pressed
    if(isset($_POST['post_it']))
    {
        if($oForm->checkPOSTdata($_POST))
        {   
            array_pop($oForm->aFiltered); //in order to do not put submit_btn into DB

            //bind all necessary data
            $db->bindMore($oForm->aFiltered);
            $db->bind('username_id', $_SESSION['current_id']);
            $db->bind('mypost_title', $oForm->aFiltered['mypost_title']);
            $db->bind('mypost', $oForm->aFiltered['mypost']);
        
            //inset new post info into DB
            $insert = $db->query("INSERT INTO tbl_posts SET
                                    username_id =:username_id,
                                    post_title =:mypost_title,
                                    post =:mypost");
        }
    }

$formHTML = $oForm->openForm()
            .$oForm->makeInputRowVal('mypost_title', 'Title:', 'text')
            .$oForm->makeTextareaRowVal('mypost', 'Post:')
            .$oForm->submitButton('post_it', 'Post')
            .$oForm->closeForm();


//if "Post_btn" for option#2 was pressed
    if(isset($_POST['upload_it']))
    {
        if($oForm2->checkPOSTdata($_POST))
        {
            array_pop($oForm2->aFiltered); //in order to do not put submit_btn into DB
        
            $db->bindMore($oForm2->aFiltered);
            $oImg = new uploadImage(); //create an obj of an uploading file 
            $new_imagename = $oImg->upload();
            if($oForm2->checkPic($new_imagename, 'filename')) //check if image has been uploaded successfully
            {   
               //bind all necessary data
               $db->bind('username_id', $_SESSION['current_id']);
               $db->bind('img_title', $oForm2->aFiltered['img_title']);
               $db->bind('img_name', $new_imagename);
               $db->bind('capture', $oForm2->aFiltered['capture']);
        
               //inset new post info into DB
               $insert = $db->query("INSERT INTO tbl_posts SET
                                        username_id =:username_id,
                                        post_title =:img_title,
                                        img_name =:img_name,
                                        post =:capture");
                
           }            
        }

    }
$formHTML2 = $oForm2->openFormImg()
            .$oForm2->makeInputRowVal('filename', 'Image:', 'file')
            .$oForm2->makeInputRowVal('img_title', 'Title:', 'text')
            .$oForm2->makeTextareaRowVal('capture', 'Post:')
            .$oForm2->submitButton('upload_it', 'Post')
            .$oForm2->closeForm();

//if "Delete_btn" for option#3 was pressed
    if(isset($_POST['delete_it']))
    {
        if($oForm3->checkPOSTdata($_POST))
        {
            array_pop($oForm3->aFiltered); //in order to do not put submit_btn into DB

            //collect the sum of reasons of leaving for statistics
            switch($oForm3->aFiltered['suicide'])
            {
                case 1: $insert = $db->query("UPDATE tbl_reasons_of_leaving SET like_btn=like_btn+1");
                        break;
                case 2: $insert = $db->query("UPDATE tbl_reasons_of_leaving SET too_simple=too_simple+1");
                        break;
                case 3: $insert = $db->query("UPDATE tbl_reasons_of_leaving SET too_difficult=too_difficult+1");
                        break;
            }

            //change the hide for user, so only admin can see that user
            $delete = $db->query('UPDATE tbl_users SET hide = 1 WHERE id="'. $_SESSION['current_id']. '"');
            header('Location: goodbye.php');
        }
    }
$formHTML3 = $oForm3->openForm('kill_me')
                        .$oForm3->makeLabelNaked('suicide', 'What\'s the reason of your leave?')
                        .$oForm3->makeRadio('suicide', '1', 'I don\'t like this blog, because there is no like button.')
                        .$oForm3->makeRadio('suicide', '2', 'Riddles are too simple.')
                        .$oForm3->makeRadio('suicide', '3', 'Riddles are too difficult.')
                        .$oForm3->makeRadio('suicide', '4', 'I don\'t have any reason to do anything without having a reason for doing that.')
                        .'<p class="validation_msg top_validation">'. $message. '</p>'
                        .$oForm3->submitButton('delete_it', 'Delete')
                        .$oForm3->closeForm();

    $username = strtoupper($db->single('SELECT username FROM tbl_users WHERE id ="'. $_SESSION['current_id']. '"'));
    $statement = strtolower($db->single('SELECT statement FROM tbl_users WHERE id ="'. $_SESSION['current_id']. '"'));
    $avatar = $db->single('SELECT avatar FROM tbl_users WHERE id ="'. $_SESSION['current_id']. '"');

//prepared vars for admin's default post
    $adminname = strtoupper($db->single('SELECT username FROM tbl_users WHERE id=1'));
    $admin_statement = $db->single('SELECT statement FROM tbl_users WHERE id=1');
    $regist = $db->single('SELECT date_entered FROM tbl_users WHERE id="'. $_SESSION['current_id']. '"');
    $date_regist = date('g:ia, j F Y', strtotime($regist));

//target all posts of that user in order to display them
    $aPosts = $db->query('SELECT id FROM tbl_posts WHERE username_id="'. $_SESSION['current_id']. '" ORDER BY date_entered DESC');
    $_SESSION['user_posts'] = array(); //create array of $_SESSION['user_posts'] (need that later to save each post's info)
    foreach($aPosts as $index=>$arr)
    {
        foreach($arr as $id=>$num) //$num - is an id of post as it is in tbl_posts
        {
            $post_title = strtoupper($db->single('SELECT post_title FROM tbl_posts WHERE id="'. $num. '"'));
            $post = $db->single('SELECT post FROM tbl_posts WHERE id="'. $num. '"');
            $comment_num = $db->single('SELECT comment_num FROM tbl_posts WHERE id="'. $num. '"');
            $date = $db->single('SELECT date_entered FROM tbl_posts WHERE id="'. $num. '"');
            $timestamp = date('g:ia, j F Y', strtotime($date));
            $picture = $db->single('SELECT img_name FROM tbl_posts WHERE id="'. $num. '"');
           
            array_push($_SESSION['user_posts'], $oHTML->post($username, $timestamp, $post_title, $post, $comment_num, $picture, $num));
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
                <?php echo $oHTML->makeNavigation($aNavigation, "My profile"); ?>
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
                <section class="basic cf" id="grey">
                    <div class="avatar_img"><?php echo '<img src="'. IMG_THUMBS_PATH. $avatar. '" alt="userpic"/>'; ?></div>

                    <div class="top_post">
                        <i class="fa fa-user"></i><h4><?php echo $username; ?></h4>
                        <h4><?php echo $statement; ?></h4>
                    </div>
                    
                    <?php echo $oHTML->drawRibbon(); ?>
                    

                        <div id="tab-group">
    <!-- write a post -->   <h3 class="tab-header active" id="tab-header-1">WRITE A POST<i class="fa fa-pencil user_options"></i></h3>
                            <div class="tab-content active" id="tab-content-1">
                            <?php echo "\n". $formHTML. "\n"; ?>
                            </div>

    <!-- upload an img <--> <h3 class="tab-header" id="tab-header-2">UPLOAD AN IMAGE<i class="fa fa-paint-brush user_options"></i></h3>
                            <div class="tab-content" id="tab-content-2">
                            <?php echo "\n". $formHTML2. "\n"; ?>
                            </div>

    <!-- delete account --> <h3 class="tab-header" id="tab-header-3">DELETE MY ACCOUNT<i class="fa fa-trash user_options"></i></h3>
                            <div class="tab-content" id="tab-content-3">
                            <?php echo "\n". $formHTML3. "\n"; ?>
                            </div>
                        </div> <!-- end of div id="tab-group" -->

                </section>

                <?php //display all posts of that user
                    foreach($_SESSION['user_posts'] as $ind=>$item)
                    {
                        echo $item;
                    }
                ?>
                
                <?php  //display default admin's post
                        echo $oHTML->defaultPost($adminname, $username, $date_regist, $admin_statement);
                ?>
 
    <?php echo $oHTML->makeFooter($script_common, $script_scroll1, $script_scroll2, $script_profile);
//EOF profile.php   