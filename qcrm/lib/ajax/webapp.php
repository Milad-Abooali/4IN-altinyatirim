<?php

/**
 * Webapp Functions Class
 */

include_once '../webapp/config-over.php';

// on null call
function noF() {
    $output = new stdClass();
    $output->e = false;
    $output->res = $_REQUEST;
    echo json_encode($output);
}

// Time
function crmTime() {
    $output = new stdClass();
    $output->e = false;
    $output->res['time'] =  date('Y-m-d H:i:s');
    echo json_encode($output);
}

// Get Timezone
function getTimezone() {
    $output = new stdClass();
    $output->e = false;
    $output->res['timezone'] =  date_default_timezone_get();
    echo json_encode($output);
}

// Get Groups
function getGroups() {
    $output = new stdClass();
    $output->e = false;
    if(!$output->e) {
        global $db;
        $result = $db->selectAll('mt_groups');
        $output->res = $result;
    }
    echo json_encode($output);
}

// Get TimeZone
function getBroker() {
    $output = new stdClass();
    $output->e = false;
    $output->res =  Broker;
    echo json_encode($output);
}

// Get Session
function getClient() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['id']) ) $output->e = 'id expected';
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id']) || $_REQUEST['id']>0){
            if( $_SESSION['id'] != $_REQUEST['id'] )
                $output->e = 'session is not same as id';
            else
                $output->res = $_SESSION;
        }
        else {
            $output->res = $_SESSION;
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}

/** Notifications  */
// Notifications |  !~~~ Add
function addNotification() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if( !isset($_REQUEST['id']) ) $output->e = 'id expected';
    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id'])){
            $notification = webapp::notificaionAdd($_SESSION['id'],'secondary','log-in-outline','Login','WebApp','');
        }
        else {
            $output->e = 'Need to login first!';
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}
//  Notifications | Delete
function deleteNotification() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if( !isset($_REQUEST['id']) ) $output->e = 'id expected';
    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id'])){
            $notification = webapp::getNotification($_REQUEST['id']);
            if($notification){
                if($notification['user_id']){
                    webapp::notificaionStatus($_REQUEST['id'],-1);
                }else{
                    $output->e = 'Notification is not for your account!';
                }
            }else{
                $output->e = 'Notification ('.$_REQUEST['id'].') not found!';
            }
        }
        else {
            $output->e = 'Need to login first!';
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}
//  Notifications | Mark As Read
function readNotification() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if( !isset($_REQUEST['id']) ) $output->e = 'id expected';
    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id'])){
            $notification = webapp::getNotification($_REQUEST['id']);
            if($notification){
                if($notification['user_id']){
                    webapp::notificaionStatus($_REQUEST['id'],1);
                }else{
                    $output->e = 'Notification is not for your account!';
                }
            }else{
                $output->e = 'Notification ('.$_REQUEST['id'].') not found!';
            }
        }
        else {
            $output->e = 'Need to login first!';
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}
// Notifications | Count
function countNotifications() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id'])){
            $output->res = webapp::countUnreadNotifications($_SESSION['id']);
        }
        else {
            $output->e = 'Need to login first!';
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}
// Notifications | Get Last
function getUserNotifications() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if( !isset($_REQUEST['offset']) ) $output->e = 'offset expected';
    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id'])){
            $output->res = webapp::notificaionsList($_SESSION['id'], $_REQUEST['offset'], 5);
        }
        else {
            $output->e = 'Need to login first!';
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}
// Notifications | Mark All Seen
function seenAllNotifications() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id'])){
            $output->res = webapp::notificaionSeenAll($_SESSION['id']);
        }
        else {
            $output->e = 'Need to login first!';
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}
// Notifications | Delete All
function deleteAllNotifications() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id'])){
            $output->res = webapp::notificaionDeleteAll($_SESSION['id']);
        }
        else {
            $output->e = 'Need to login first!';
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}



// CRM Session
function crmLogin() {+
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if( !isset($_REQUEST['username']) ) $output->e = 'username expected';
    if( strlen($_REQUEST['username'])<7 ) $output->e = 'username is short';
    if( !isset($_REQUEST['password']) ) $output->e = 'password expected';
    if( strlen($_REQUEST['password'])<5 ) $output->e = 'password is short';
    if(!$output->e){
        webapp::sessionJump($_REQUEST['session']);
        global $db;
        global $sess;
        if(is_numeric($_REQUEST['username'])) {
            $_REQUEST['username'] = $sess->getUsernameByPhone($_REQUEST['username']);
        }
        $username = $db->escape($_REQUEST['username']);
        $password = $db->escape($_REQUEST['password']);
        if(!$sess->IS_LOGIN) $sess->login(180, $username, $password, true, false);
        if($sess->IS_LOGIN) {
            webapp::notificaionAdd($_SESSION['id'],'secondary','log-in-outline','Login','WebApp','');
            $output->res = $_SESSION['id'];
            webapp::checkSession();
        } else {
            $output->e = $sess->ERROR;
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}


// CRM Session Re Login
function crmReLogin() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if( !isset($_REQUEST['userId']) ) $output->e = 'user id expected';
    if(!$output->e){
        webapp::sessionJump($_REQUEST['session']);
        global $db;
        global $sess;
        if(!$sess->IS_LOGIN) $sess->relogin($_REQUEST['userId']);
        if($sess->IS_LOGIN) {
            $output->res = $_SESSION['id'];
            webapp::checkSession();
        } else {
            $output->e = $sess->ERROR;
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}


// CRM Recovery
function crmRecovery() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if( !isset($_REQUEST['username']) ) $output->e = 'username expected';
    if(!$output->e){
        webapp::sessionJump($_REQUEST['session']);
        global $db;
        global $sess;
        if(is_numeric($_REQUEST['username'])) {
            $_REQUEST['username'] = $sess->getUsernameByPhone($_REQUEST['username']);
        }
        $username = $db->escape($_REQUEST['username']);
        $where = "email='$username' AND unit IN (".Broker['units'].")";
        $user = $db->selectRow('users',$where);
        if($user) {
            $up_token['token'] = bin2hex(random_bytes(50));
            $db->updateId('users', $user['id'], $up_token);
            // Send Email
            global $_Email_M;
            $receivers[] = array (
                'id'    =>  $user['id'],
                'email' =>  $user['email'],
                'data'  =>  array(
                    'token' =>  $up_token['token']
                )
            );
            $theme = $subject = 'CRM_Reset_Password';
            try{
                $output->res = $_Email_M->send($receivers, $theme, $subject);
                webapp::notificaionAdd($_SESSION['id'],'warning','key','Login','WebApp','');
            } catch (Exception $e){
                $output->e = $e->getMessage();
            }
        } else {
            $output->e = "Sorry, no user exists on our system with that email";
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}

// CRM Register
function crmRegister() {

    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if( !isset($_REQUEST['fname']) ) $output->e = 'first name expected';
    if( !isset($_REQUEST['lname']) ) $output->e = 'last name expected';
    if( !isset($_REQUEST['email']) ) $output->e = 'email expected';
    if( !filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL)) $output->e = 'The email ('.$_REQUEST['email'].') is not valid';
    if( !isset($_REQUEST['phone']) ) $output->e   = 'phone expected';
    if( strlen($_REQUEST['phone'])<5 ) $output->e = 'phone is short';
    if( !isset($_REQUEST['country']) ) $output->e = 'country expected';
    if( !isset($_REQUEST['source']) ) $output->e  = 'source expected';
    if( !isset($_REQUEST['unit_id']) ) $output->e = 'unit expected';

    if(!$output->e){
        global $db;
        global $sess;
        // Check for duplicate phone
        $phone_10 = $db->escape(substr($_REQUEST['phone'],-10));
        $where = " RIGHT(phone,10)='$phone_10'";
        if($db->exist('user_extra', $where)) $output->e = "You have an other account in our site with this phone number (".$_REQUEST['phone'].")!";

        // Check for duplicate email
        $where = "email='".$_REQUEST['email']."' AND unit IN (".Broker['units'].")";
        if($db->exist('users',$where)) $output->e = "You have an other account in our site with this email address(".$_REQUEST['email'].")!";

        // Check if login
        if ($sess->IS_LOGIN) $output->e = 'You need logout first!';
    }

    if(!$output->e){
        webapp::sessionJump($_REQUEST['session']);

        $fname      = GF::charReplace('tr', $_REQUEST['fname']);
        $lname      = GF::charReplace('tr', $_REQUEST['lname']);
        $phone      = $_REQUEST['phone'];
        $country    = $_REQUEST['country'];
        $unit_id    = $_REQUEST['unit_id'];
        $unit_name  = $db->selectId('units', $unit_id,'name')['name'];
        $date       = date('Y-m-d H:i:s');
        $source     = $_REQUEST['source'];
        $campaign   = $_REQUEST['campaign'];
        $affiliate  = $_REQUEST['affiliate'];
        $ip         = GF::getIP();
        $email      = $_REQUEST['email'];
        $pass       = GF::genPass();
        $password   = password_hash($pass, PASSWORD_DEFAULT);

        // Insert to users
        $insert_user['username']    = $email;
        $insert_user['password']    = $password;
        $insert_user['email']       = $email;
        $insert_user['unit']        = $unit_name;
        $insert_user['type']        = 'Leads';
        $insert_user['pa']          = GF::encodeAm($pass);
        $insert_user['created_at']  = $date;
        $insert_user['pincode']     = rand(1001,9999);
        $user_id = $db->insert('users', $insert_user);

        if($user_id) {
            // Insert to users_extra
            $insert_extra['user_id']    = $user_id;
            $insert_extra['ip']         = $ip;
            $insert_extra['fname']      = $fname;
            $insert_extra['lname']      = $lname;
            $insert_extra['phone']      = $phone;
            $insert_extra['country']    = $country;
            $insert_extra['unit']       = $unit_id;
            if(isset($affiliate)){
                $where = "id=$affiliate";
                if($db->exist('staff_list', $where)){
                    $insert_extra['conversion'] = $affiliate;
                }
            }
            $insert_extra['status']     = 1;
            $insert_extra['type']       = 1;
            $insert_extra['followup']   = $date;
            $insert_extra['created_at'] = $date;
            $insert_extra['created_by'] = $user_id;
            $insert_extra['updated_at'] = $date;
            $insert_extra['updated_by'] = $user_id;
            $insert_extra['language'] = LANGUAGE_NAME;
            $db->insert('user_extra', $insert_extra);

            // Insert to user_fx
            $insert_fx['user_id']    = $user_id;
            $insert_fx['exp_fx']     = 1;
            $insert_fx['exp_cfd']    = 1;
            $insert_fx['created_at'] = $date;
            $insert_fx['created_by'] = $user_id;
            $insert_fx['updated_at'] = $date;
            $insert_fx['updated_by'] = $user_id;
            $db->insert('user_fx',$insert_fx);

            // Insert to user_gi
            $insert_gi['user_id']       = $user_id;
            $insert_gi['created_at']    = $date;
            $insert_gi['created_by']    = $user_id;
            $insert_gi['updated_at']    = $date;
            $insert_gi['updated_by']    = $user_id;
            $db->insert('user_gi',$insert_gi);

            // Insert to user_marketing
            $insert_marketing['user_id']       = $user_id;
            $insert_marketing['lead_src']      = $source;
            $insert_marketing['lead_camp']     = $campaign;
            $insert_marketing['affiliate']     = $affiliate;
            $insert_marketing['created_at']    = $date;
            $insert_marketing['created_by']    = $user_id;
            $insert_marketing['updated_at']    = $date;
            $insert_marketing['updated_by']    = $user_id;
            $db->insert('user_marketing',$insert_marketing);

            // Send Email
            try{
                global $_Email_M;
                $receivers[] = $act_detail = array (
                    'id'    =>  $user_id,
                    'email' =>  $email,
                    'data'  =>  array(
                        'fname'     =>  $fname,
                        'lname'     =>  $lname,
                        'email'     =>  $email,
                        'pass'      =>  $pass
                    )
                );
                $subject = $theme = 'CRM_New_Account';
                $_Email_M->send($receivers, $theme, $subject);

                // Add actLog
                global $actLog; $actLog->add('New Lead', $user_id, 1, json_encode($act_detail));
            }
            catch(Exception $e){
                $output->e = 'Error: '. $e->getMessage();
            } finally {
                webapp::notificaionAdd($_SESSION['id'],'warning','accessibility-outline','Register','WebApp','');
                // Autologin
                $username = $db->escape($email);
                $password = $db->escape($pass);
                if(!$sess->IS_LOGIN) $sess->login(180, $username, $password, true, false);
                if($sess->IS_LOGIN) {
                    webapp::checkSession();
                } else {
                    $output->e = $sess->ERROR;
                }
            }
        }
        else {
            $output->e = $db->log();
        }

        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}

// Get Screen
function getScreen() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if( !isset($_REQUEST['screen']) ) $output->e = 'screen expected';
    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id'])){
            ob_start();
            $check_permit = webapp::checkPermit($_REQUEST['screen'], 'view',1);
            if(!$check_permit) $output->e = ob_get_contents();
            ob_end_clean();
            if($check_permit){
                $output->res = \WEBAPP\html::screen($_REQUEST['screen'], $_REQUEST['params']);
            }
        }
        else {
            $output->e = 'Need to login first!';
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}

// Upload Doc
function crmUploadDoc() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if( !isset($_REQUEST['title']) ) $output->e = 'title expected';
    if( !isset($_REQUEST['type']) ) $output->e = 'type expected'; // ???
    if( !isset($_FILES["file"]) ) $output->e = 'file expected';
    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id'])){
            $file_ext        = pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION);
            $uniq_id         = uniqid();
            $file_path       = CRM_ROOT.DIRECTORY_SEPARATOR."media".DIRECTORY_SEPARATOR.'_'.$_REQUEST['title'].DIRECTORY_SEPARATOR.$uniq_id.".".$file_ext;

            if ( move_uploaded_file($_FILES["file"]["tmp_name"], $file_path) ) {
                global $db;
                $type = ($_REQUEST['title']==='id') ? 'ID' : 'Bill';

                $where = "type='$type' AND user_id=".$_SESSION['id'];
                $is_exist = $db->exist('media', $where);
                if($is_exist){
                    $update['media'] = "_".$_REQUEST['title'].DIRECTORY_SEPARATOR.$uniq_id.".".$file_ext;
                    $update['updated_at'] = date('Y-m-d H:i:s');
                    $update['updated_by'] = $_SESSION["id"];
                    $db->updateAny('media',$update, $where);
                } else {
                    $insert['media']      = "_".$_REQUEST['title'].DIRECTORY_SEPARATOR.$uniq_id.".".$file_ext;
                    $insert['type']       = $type;
                    $insert['user_id']    = $_SESSION["id"];
                    $insert['created_at'] = date('Y-m-d H:i:s');
                    $insert['created_by'] = $_SESSION["id"];
                    $db->insert('media', $insert);
                }
                $output->res = "media".DIRECTORY_SEPARATOR."_".$_REQUEST['title'].DIRECTORY_SEPARATOR.$uniq_id.".".$file_ext;
                webapp::notificaionAdd($_SESSION['id'],'info','person-outline','Doc Uploaded','WebApp','');
            } else {
                $output->e = 'Can not creat the new file!';
            }
        }
        else {
            $output->e = 'Need to login first!';
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}


// Upload Avatar
function crmUploadAvatar() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if( !isset($_REQUEST['avatar']) ) $output->e = 'avatar expected';
    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id'])){
            list($dataType, $imageData) = explode(';', $_REQUEST['avatar']);
            // image file extension
            $imageExtension = explode('/', $dataType)[1];
            // base64-encoded image data
            list(, $encodedImageData) = explode(',', $imageData);
            // decode base64-encoded image data
            $decodedImageData = base64_decode($encodedImageData);
            // save image data as file
            $uniq_id = uniqid();
            $file_path = CRM_ROOT.DIRECTORY_SEPARATOR."media".DIRECTORY_SEPARATOR."avatars".DIRECTORY_SEPARATOR.$uniq_id.".".$imageExtension;
            file_put_contents($file_path, $decodedImageData);
            if (file_exists($file_path)) {
                global $db;
                $where = "type='avatar' AND user_id=".$_SESSION['id'];
                $is_exist = $db->exist('media', $where);
                if($is_exist){
                    $update['media'] = "avatars".DIRECTORY_SEPARATOR.$uniq_id.".".$imageExtension;
                    $update['updated_at'] = date('Y-m-d H:i:s');
                    $update['updated_by'] = $_SESSION["id"];
                    $db->updateAny('media',$update, $where);
                } else {
                    $insert['media']      = "avatars".DIRECTORY_SEPARATOR.$uniq_id.".".$imageExtension;
                    $insert['type']       = 'avatar';
                    $insert['user_id']    = $_SESSION["id"];
                    $insert['created_at'] = date('Y-m-d H:i:s');
                    $insert['created_by'] = $_SESSION["id"];
                    $db->insert('media', $insert);
                }
                $output->res = "media".DIRECTORY_SEPARATOR."avatars".DIRECTORY_SEPARATOR.$uniq_id.".".$imageExtension;
                webapp::notificaionAdd($_SESSION['id'],'info','person-outline','Avatar Updated','WebApp','');
            } else {
                $output->e = 'Can not creat the new file!';
            }
        }
        else {
            $output->e = 'Need to login first!';
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}

// Transaction Request
function crmTransactionRequest() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if( !isset($_REQUEST['amount']) ) $output->e = 'amount expected';
    if( !isset($_REQUEST['type']) ) $output->e = 'type expected';
    if( !isset($_REQUEST['tp']) ) $output->e = 'account expected';
    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id'])){
            require_once "autoload/transaction.php";
            $Transaction = new Transaction();
            $pending_transaction = $Transaction->checkTransactionWaiting($_SESSION['id']);
            $output->e = $pending_transaction;
            if (!$output->e) {
                if ($_REQUEST['type'] == 'deposit') {
                    $source = $_REQUEST['gateway'];
                    $destination = $_REQUEST['tp'];
                } else if ($_REQUEST['type'] == 'withdraw') {
                    $source = $_REQUEST['tp'];
                    $destination = $_REQUEST['bankAccount'];
                }
                $transaction_id = $Transaction->add($_REQUEST['type'], $_REQUEST['amount'], $source, $destination, $_SESSION['id'], $_REQUEST['comment']);
                $output->res = $transaction_id;
                if ($transaction_id) {
                    if ($_REQUEST['docs']) {
                        $count_files = count($_REQUEST['docs']);
                        for ($i = 0; $i < $count_files; $i++) {
                            list($dataType, $imageData) = explode(';', $_REQUEST['docs'][$i]);
                            // image file extension
                            $imageExtension = explode('/', $dataType)[1];
                            // base64-encoded image data
                            list(, $encodedImageData) = explode(',', $imageData);
                            // decode base64-encoded image data
                            $decodedImageData = base64_decode($encodedImageData);
                            // save image data as file
                            $filename = $transaction_id.'__'.$i.'__'.uniqid().".".$imageExtension;
                            $file_path = "../media/transaction/" . $filename;
                            $valid_extensions = array("jpg", "jpeg", "png","pdf");
                            if(in_array(strtolower($imageExtension), $valid_extensions)){
                                file_put_contents($file_path, $decodedImageData);
                                if (file_exists($file_path)) {
                                    $Transaction->addDoc($transaction_id, $filename);
                                }
                                else {
                                    $output->e = 'Can not creat the new file!';
                                }
                            }else {
                                $output->test = $imageExtension;
                                $output->e = $imageExtension.' is not a valid type, accepted file type is "jpg", "jpeg", "png","pdf"';
                            }
                        }
                    }
                    webapp::notificaionAdd($_SESSION['id'],'primary','add',ucfirst($_REQUEST['type']),'$'.$_REQUEST['amount'],'Transaction Request');
                    global $db;
                    $user_unit = $db->selectId('users', $_SESSION['id'], 'unit')['unit'];
                    $where = "unit ='$user_unit' AND type='Manager'";
                    $agents = $db->select('users', $where, 'id');
                    if ($agents) {
                        foreach ($agents as $agent) $ids[] = $agent['id'];
                        $receivers = implode(",", $ids);
                        global $notify;
                        $notify->addMulti('User ' . $_SESSION["id"], 2, $transaction_id, $receivers);
                    }
                    global $actLog; $actLog->add('Transaction',(($transaction_id) ?? null),(($transaction_id) ? 1 : 0), json_encode($_REQUEST));
                }
            }
            else {
                $output->e = 'You have a waiting transaction: '.$pending_transaction;
            }
        }
        else {
            $output->e = 'Need to login first!';
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}

// Transaction Cancel
function crmTransactionCancel() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['transaction_id']) ) $output->e = 'Transaction is expected';
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id'])) {
            require_once "autoload/transaction.php";
            $Transaction = new Transaction();
            $requested_transaction = $Transaction->loadTransactionByID($_REQUEST['transaction_id']);
            if ($_SESSION['id'] != $requested_transaction['user_id']) {
                $output->e = 'Transaction owner is not same as you!';
            }
            if (!$output->e) {
                $Transaction->cancel($_REQUEST['transaction_id']);
                global $actLog;
                $actLog->add('Transaction', (($_REQUEST['transaction_id']) ?? null), (($_REQUEST['transaction_id']) ? 1 : 0), json_encode($_REQUEST));
                webapp::notificaionAdd($_SESSION['id'], 'danger', 'close-circle', ucfirst($requested_transaction['type']), '$' . $requested_transaction['amount'], 'Transaction Canceled');
            }
        }
        else {
            $output->e = 'Need to login first!';
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}

// Transactions History
function crmTransactionsHistory() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id'])) {
            require_once "autoload/transaction.php";
            $Transaction = new Transaction();
            $where = 'user_id='.$_SESSION['id'];
            $output->res = $Transaction->loadTransaction($where);
        }
        else {
            $output->e = 'Need to login first!';
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}


// Meta Account Summery
function metaAccountSummery() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['account']) ) $output->e = 'account is expected';
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id'])){

            $mt5api = new mt5API();

            $api_params['login']  = $_REQUEST['account'];
            $mt5api->get('/api/user/account/get', $api_params);
            $total  = array(
                'e'      => $mt5api->Error,
                'api'    => $mt5api->Response,
            );
            $api = $mt5api->Response;
            if($api->retcode==="0 Done"){
                $number_digit = $api->answer->CurrencyDigits;
                $output->res = array(
                    'Balance'           =>    GF::nf($api->answer->Balance, $number_digit),
                    'Equity'            =>    GF::nf($api->answer->Equity, $number_digit),
                    'Margin'            =>    GF::nf($api->answer->Margin, $number_digit),
                    'MarginLevel'       =>    GF::nf($api->answer->MarginLevel, $number_digit),
                    'MarginFree'        =>    GF::nf($api->answer->MarginFree, $number_digit),
                    'MarginLeverage'    =>    GF::nf($api->answer->MarginLeverage, $number_digit),
                    'Profit'            =>    GF::nf($api->answer->Profit, $number_digit)
                );
            }
            else {
                $output->e = $total['e'];
            }
        }
        else {
            $output->e = 'Need to login first!';
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}

// Meta Platform Groups
function metaPlatformGroups() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['server']) ) $output->e = 'server expected';
    if( !isset($_REQUEST['type']) ) $output->e = 'type expected';
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id'])){
            global $db;
            $where = 'unit = '.$_SESSION['unitn'].' AND type = '.$_REQUEST['type'].' AND server = '.$_REQUEST['server'];
            $result = $db->select('mt_groups', $where);
            $html='';
            if($result) foreach ($result as $group){
                $html .= '<option>'.$group['name'].'</option>';
            }
            $output->res = $html;
        }
        else {
            $output->e = 'Need to login first!';
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}

// Meta Open Account
function metaOpenAccount() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['platform']) ) $output->e = 'platform expected';
    if( !isset($_REQUEST['type']) ) $output->e = 'type expected';
    if( !isset($_REQUEST['group']) ) $output->e = 'group expected';
    if( !isset($_REQUEST['amount']) ) $output->e = 'amount expected';
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id'])){

            global $db;
            global $DB_admin;
            global $userManager;
            $user_data = $userManager->get($_SESSION['id']);
            $userId     = $_SESSION['id'];
            $type       = $_REQUEST['type'];
            $platform   = $_REQUEST['platform'];
            $group      = $_REQUEST['group'];
            $amount     = $_REQUEST['amount'];
            $name       = $user_data['user_extra']['fname'].' '.$user_data['user_extra']['lname'];
            $uname      = $user_data['user_extra']['fname'];
            $usname     = $user_data['user_extra']['lname'];
            $email      = $user_data['email'];

            $date = date('Y-m-d\TH:i:s\Z');

            // MT5
            if($platform == "2"){
                $request = new CMT5Request();
                if($request->Init('mt5.tradeclan.co.uk:443') && $request->Auth(1000,"@Sra7689227",1950,"WebManager"))
                {
                    $main_pass = webapp::genPassword();
                    $investor_pass = webapp::genPassword();
                    // Real
                    if($type == "2"){
                        $prefixgroup = "real\\";
                    }
                    // Demo
                    else {
                        $prefixgroup = "demo\\";
                    }

                    // USER GET State
                    $code = '/user_add?pass_main='.$main_pass.'&pass_investor='.$investor_pass.'&group='.$prefixgroup.$group.'&name=test&email='.$email.'&leverage=200';
                    $result=$request->Get($code);

                    if($result!=false)
                    {
                        $json=json_decode($result);
                        if($json->retcode!='3 Invalid parameters'){
                            $login_5 = $json->answer->Login;
                            if($type == "1"){
                                $result2=$request->Get('/trade_balance?login='.$login_5.'&type=2&balance='.$amount.'&comment=Deposit');
                            }
                        } else {
                            $output->e[] = $json;
                        }


                    }
                }
                $request->Shutdown();
            }
            // MT4
            else {
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://auth.cplugin.net/connect/token",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS =>"grant_type=client_credentials&scope=webapi&client_id=cd205448-0fb9-4dd8-9abf-9e0687b149ac&client_secret=7fa5cbbd-13c2-43a4-97b8-842fbc54f0f1",
                    CURLOPT_HTTPHEADER => array(
                        "Content-Type: application/x-www-form-urlencoded"
                    ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                $data = json_decode($response);

                if($type == "1"){
                    $curl2 = curl_init();
                    curl_setopt_array($curl2, array(
                        CURLOPT_URL => "https://mywebapi.com/api/MT4/5f601116-2a15-448c-afea-66e7b7d7c6c5/UserRecordGet/".$userId,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "GET",
                        CURLOPT_HTTPHEADER => array(
                            "api-version: 1.0",
                            "Authorization: Bearer ".$data->access_token.""
                        ),
                    ));
                    $response2 = curl_exec($curl2);
                    curl_close($curl2);
                    $user = json_decode($response2);
                    $curl3 = curl_init();
                    curl_setopt_array($curl3, array(
                        CURLOPT_URL => "https://mywebapi.com/api/MT4/5f601116-2a15-448c-afea-66e7b7d7c6c5/UserRecordNew",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS =>'{"enable": 1,"leverage": 200,"group":"'.$group.'","name": "'.$name.'","country": "'.$country.'","email": "'.$email.'"}',
                        CURLOPT_HTTPHEADER => array(
                            "Api-Version: 1.0",
                            "Content-Type: application/json",
                            "Authorization: Bearer ".$data->access_token.""
                        ),
                    ));
                    $response3 = curl_exec($curl3);
                    $tp = json_decode($response3);
                    curl_close($curl3);
                    $output->res = $response3;
                }
                else if ($type == "2") {
                    $curl2 = curl_init();
                    curl_setopt_array($curl2, array(
                        CURLOPT_URL => "https://mywebapi.com/api/MT4/895a45cf-8c17-4c16-80ad-79958f133358/UserRecordGet/".$userId,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "GET",
                        CURLOPT_HTTPHEADER => array(
                            "api-version: 1.0",
                            "Authorization: Bearer ".$data->access_token.""
                        ),
                    ));
                    $response2 = curl_exec($curl2);
                    curl_close($curl2);
                    $user = json_decode($response2);
                    $curl3 = curl_init();
                    curl_setopt_array($curl3, array(
                        CURLOPT_URL => "https://mywebapi.com/api/MT4/895a45cf-8c17-4c16-80ad-79958f133358/UserRecordNew",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS =>'{"enable": 1,"leverage": 200,"group":"'.$group.'","name": "'.$name.'","country": "'.$country.'","email": "'.$email.'"}',
                        CURLOPT_HTTPHEADER => array(
                            "Api-Version: 1.0",
                            "Content-Type: application/json",
                            "Authorization: Bearer ".$data->access_token.""
                        ),
                    ));
                    $response3 = curl_exec($curl3);
                    $tp = json_decode($response3);
                    curl_close($curl3);
                    $output->res = $response3;
                }
            }


            $date = date('Y-m-d H:i:s');
            $platform_name =  '';
            if($platform==1)
                $platform_name = 'MT4';
            if($platform==2)
                $platform_name = 'MT5';

            $sqlPass = "INSERT INTO tp (user_id,login,password,group_id,server,created_at,created_by,updated_at,updated_by) VALUES ('$userId','$login_5','$main_pass','$type','$platform_name','$date','".$_SESSION["id"]."','$date','".$_SESSION["id"]."')";

            // MT5
            if($platform == "2"){
                $request1 = new CMT5Request();
                if($request1->Init('mt5.tradeclan.co.uk:443') && $request1->Auth(1000,"@Sra7689227",1950,"WebManager"))
                {
                    $result1=$request1->Get('/user_get?login='.$login_5);
                    if($result1!=false)
                    {
                        $json1=json_decode($result1);
                        if((int)$json1->retcode==0)
                        {
                            $user1=$json1->answer;
                            //--- Changing The Details
                            $user1->FirstName=$user_data['user_extra']['fname'];
                            $user1->LastName=$user_data['user_extra']['lname'];
                            $result1=$request1->Post('/user_update',json_encode($user1));
                        }
                    }
                    $request1->Shutdown();
                }

                mysqli_query($DB_admin, $sqlPass);

                $inserted_id = mysqli_insert_id($DB_admin);
                // Add actLog
                global $actLog; $actLog->add('MyWebAPI',$userId,1,'{"action":"New TP","user_id":"'.$userId.'","TP ID":"'.$inserted_id.'"}');

                // Send Email
                global $_Email_M;
                $where = "email='$email' AND unit IN (".Broker['units'].")";
                $receivers[] = array (
                    'id'    =>  $db->selectRow('users',$where)['id'],
                    'email' =>  $email,
                    'data'  =>  array(
                        'fname' =>  $uname,
                        'lname' =>  $usname,
                        'login' =>  $login_5,
                        'pass' =>  $main_pass,
                        'ipass' =>  $investor_pass
                    )
                );
                $subject = $theme = 'TP_New_Account';
                $_Email_M->send($receivers, $theme, $subject);

                $output->res = array(
                    'Login' => $login_5,
                    'Password' => $main_pass,
                    'Investor_Password' => $investor_pass,
                );


            }
            // MT4
            else {
                mysqli_query($DB_admin, $sqlPass);

                $inserted_id = mysqli_insert_id($DB_admin);
                // Add actLog
                global $actLog; $actLog->add('MyWebAPI',$userId,1,'{"action":"New TP","user_id":"'.$userId.'","TP ID":"'.$inserted_id.'"}');

                // Send Email
                global $db;
                global $_Email_M;
                $where = "email='$email' AND unit IN (".Broker['units'].")";
                $receivers[] = array (
                    'id'    =>  $db->selectRow('users',$where)['id'],
                    'email' =>  $email,
                    'data'  =>  array(
                        'fname' =>  $uname,
                        'lname' =>  $usname,
                        'login' =>  $tp->login,
                        'pass' =>  $tp->password,
                        'ipass' =>  ''
                    )
                );
                $subject = $theme = 'TP_New_Account';
                $_Email_M->send($receivers, $theme, $subject);

                $output->res = array(
                    'Login' => $tp->login,
                    'Password' => $tp->password
                );
            }

            mysqli_close($DB_admin);

            webapp::notificaionAdd($_SESSION['id'],'success','logo-buffer','New Account','WebApp',"
                Account number: {$output->res['Login']} <hr> Platform Password: {$output->res['Password']}");

        }
        else {
            $output->e = 'Need to login first!';
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}

// Meta Market Symbols
function metaMarketSymbols() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['account']) ) $output->e = 'account is expected';
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id'])){
            $mt5api = new mt5API();
            $api_params['login']  = $_REQUEST['account'];
            $mt5api->get('/api/user/get', $api_params);
            $e = $mt5api->Error;
            $api = $mt5api->Response;
            $login_Group = $api->answer->Group;
            if($login_Group){
                $api_params=[];
                $api_params['symbol']  = '*';
                $api_params['group']  = $login_Group;
                $api_params['trans_id']  = 0;
                $mt5api->get("/api/tick/stat", $api_params);
                $e = $mt5api->Error;
                $api = $mt5api->Response;
                if($api->retcode==="0 Done"){
                    $output->res['stat'] = $api->answer;
                    $mt5api->get('/api/tick/last_group', $api_params);
                    $e = $mt5api->Error;
                    $api = $mt5api->Response;
                    if($api->retcode==="0 Done"){
                        $output->res['last'] = $api->answer;
                    }
                    else {
                        $output->e = $e;
                    }
                }
                else {
                    $output->e = $e;
                }
            }
            else {
                $output->e = $e;
            }
        }
        else {
            $output->e = 'Need to login first!';
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}

// Meta Order Market
function metaOrderMarket() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['stopLoss']) ) $output->e = 'stop loss expected';
    if( !isset($_REQUEST['takeProfit']) ) $output->e = 'take profit  expected';
    if( !isset($_REQUEST['digits']) ) $output->e = 'digits expected';
    if( !isset($_REQUEST['login']) ) $output->e = 'login expected';
    if( !isset($_REQUEST['symbol']) ) $output->e = 'symbol expected';
    if( !isset($_REQUEST['aType']) ) $output->e = 'action Type expected';
    if( !isset($_REQUEST['volume']) ) $output->e = 'volume expected';
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    $is_open = webapp::isTradeOpenByLogin($_REQUEST['symbol'], $_REQUEST['login']);

    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id'])){

            $mt5api = new mt5API();

            // Request Position-
            $request_open['Action']        = 200; // TA_DEALER_POS_EXECUTE
            $request_open['Login']         = $_REQUEST['login'];
            $request_open['Symbol']        = $_REQUEST['symbol'];
            $request_open['Volume']        = $_REQUEST['volume']*10000;
            if(intval($_REQUEST['takeProfit']) != 0)
                $request_open['PriceTP']       = $_REQUEST['takeProfit'];
            if(intval($_REQUEST['stopLoss']) != 0)
                $request_open['PriceSL']   = $_REQUEST['stopLoss'];
            $request_open['Type']          = $_REQUEST['aType'];
            $request_open['TypeFill']      = 1;
            $request_open['Digits']        = $_REQUEST['digits'];
            $request_open['Comment']       = 'Q.WebAPP|Order|S:'.$_SESSION['sess_id'].'|U:'.$_SESSION['id'];

            $output->res['request_body'] = $request_open;

            $path = '/api/dealer/send_request';
            $mt5api->post($path, null, json_encode($request_open));
            $e = $mt5api->Error;
            if(!$e){
                $identifiers = $mt5api->Response->answer->id;

                // Check Request
                $data_result['id'] = $identifiers;
                $path = '/api/dealer/get_request_result';
                $mt5api->post($path, $data_result);
                $e = $mt5api->Error;
                if(!$e){
                    $retcode = $mt5api->Response->answer->$identifiers[0]->result->Retcode;
                    if($retcode == '10009'){
                        $output->res = webapp::$MetaErrorMsg[$retcode];
                    } else{
                        $output->e = webapp::$MetaErrorMsg[$retcode];
                    }
                }
                else {
                    $output->e = $e;
                }
            }
            else {
                $output->e = $e;
            }

        }
        else {
            $output->e = 'Need to login first!';
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}

// Meta Order Pending
function metaOrderPending() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['TypeTime']) ) $output->e = 'Time type expected';
    if( !isset($_REQUEST['PriceOrder']) ) $output->e = 'Price expected';
    if( !isset($_REQUEST['stopLoss']) ) $output->e = 'stop loss expected';
    if( !isset($_REQUEST['takeProfit']) ) $output->e = 'take profit  expected';
    if( !isset($_REQUEST['digits']) ) $output->e = 'digits expected';
    if( !isset($_REQUEST['login']) ) $output->e = 'login expected';
    if( !isset($_REQUEST['symbol']) ) $output->e = 'symbol expected';
    if( !isset($_REQUEST['type']) ) $output->e = 'Type expected';
    if( !isset($_REQUEST['volume']) ) $output->e = 'volume expected';
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    $is_open = webapp::isTradeOpenByLogin($_REQUEST['symbol'], $_REQUEST['login']);

    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id'])){

            $mt5api = new mt5API();

            $request_open['Action']        = 201; // TA_DEALER_ORD_PENDING
            $request_open['SourceLogin']   = $_REQUEST['login'];
            $request_open['Login']         = $_REQUEST['login'];
            $request_open['Symbol']        = $_REQUEST['symbol'];
            $request_open['Volume']        = $_REQUEST['volume']*10000;
            $request_open['PriceOrder']    = $_REQUEST['PriceOrder'];
            if(isset($_REQUEST['PriceTrigger'])){
                $request_open['PriceTrigger']   = $_REQUEST['PriceTrigger'];
            }
            $request_open['TypeTime']            = $_REQUEST['TypeTime'];
            if(isset($_REQUEST['TimeExpiration'])){
                $request_open['TimeExpiration'] = strtotime($_REQUEST['TimeExpiration']);
            }
            else{
                $request_open['TimeExpiration'] = 0;
            }
            if(intval($_REQUEST['takeProfit']) != 0){
                $request_open['PriceTP']       = $_REQUEST['takeProfit'];
            }
            if(intval($_REQUEST['stopLoss']) != 0){
                $request_open['PriceSL']   = $_REQUEST['stopLoss'];
            }
            $request_open['Type']          = $_REQUEST['type'];
            $request_open['TypeFill']      = 2;
            $request_open['Digits']        = $_REQUEST['digits'];
            $request_open['Comment']       = 'Q.WebAPP|PendingOrder|S:'.$_SESSION['sess_id'].'|U:'.$_SESSION['id'];

            $output->res['request_body'] = $request_open;

            $path = '/api/dealer/send_request';
            $mt5api->post($path, null, json_encode($request_open));
            $e = $mt5api->Error;
            if(!$e){
                $identifiers = $mt5api->Response->answer->id;

                // Check Request
                $data_result['id'] = $identifiers;
                $path = '/api/dealer/get_request_result';
                $mt5api->post($path, $data_result);
                $e = $mt5api->Error;
                if(!$e){
                    $retcode = $mt5api->Response->answer->$identifiers[0]->result->Retcode;
                    if($retcode == '10009'){
                        $output->res = webapp::$MetaErrorMsg[$retcode];
                    } else{
                        $output->e = webapp::$MetaErrorMsg[$retcode];
                    }
                }
                else {
                    $output->e = $e;
                }
            }
            else {
                $output->e = $e;
            }

        }
        else {
            $output->e = 'Need to login first!';
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}

// Meta Open Positions
function metaOpenPositions() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['account']) ) $output->e = 'account is expected';
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id'])){

            $mt5api = new mt5API();

            $api_params['login']  = $_REQUEST['account'];
            $mt5api->get('/api/position/get_batch', $api_params);
            $total  = array(
                'e'      => $mt5api->Error,
                'api'    => $mt5api->Response,
            );
            $api = $mt5api->Response;
            if($api->retcode==="0 Done"){
                $output->res = $api->answer;
            }
            else {
                $output->e = $total['e'];
            }
        }
        else {
            $output->e = 'Need to login first!';
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}

// Meta Close Position
function metaClosePosition() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['account']) ) $output->e = 'account is expected';
    if( !isset($_REQUEST['position']) ) $output->e = 'position is expected';
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id'])){

            $mt5api = new mt5API();

            $api_params['ticket']  = $_REQUEST['position'];
            $mt5api->get('/api/position/get_batch', $api_params);
            $e = $mt5api->Error;
            $output->position = $position = $mt5api->Response;
            if(!$e && $position->answer[0]->Login === $_REQUEST['account']){
                $type = ($position->answer[0]->Action) ? 0 : 1;

                // Close Position
                $path = '/api/dealer/send_request';
                $request_close['Action']       = 200;
                $request_close['Login']        = $position->answer[0]->Login;
                $request_close['Symbol']       = $position->answer[0]->Symbol;
                $request_close['Volume']       = $position->answer[0]->Volume;
                $request_close['TypeFill']     = 1;
                $request_close['Type']         = $type;
                $request_close['PriceOrder']   = $position->answer[0]->PriceCurrent;
                $request_close['Position']     = $position->answer[0]->Position;
                $request_close['Digits']       = $position->answer[0]->Digits;
                $request_close['Comment']      = 'Q.APP|Close_Pos|S:'.$_SESSION['sess_id'].'|U:'.$_SESSION['id'];

                $is_open = webapp::isTradeOpenByLogin($position->answer[0]->Symbol, $_REQUEST['account']);
                if($is_open){
                    $output->body = $request_close;
                    $mt5api->post($path, null, json_encode($request_close));
                    $e = $mt5api->Error;
                    $output->close = $mt5api->Response;
                    if(!$e){
                        $identifiers = $output->close->answer->id;

                        // Check Request
                        $data['id'] = $identifiers;
                        $path = '/api/dealer/get_request_result';
                        $mt5api->post($path, $data);
                        $e = $mt5api->Error;
                        $retcode = $mt5api->Response->answer->$identifiers[0]->result->Retcode;
                        if($retcode == '10009'){
                            $output->res = webapp::$MetaErrorMsg[$retcode];
                        } else{
                            $output->e = webapp::$MetaErrorMsg[$retcode];
                        }
                    }
                } else {
                    $output->e = 'Market is Closed!';
                }
            }
            else {
                $output->e = 'The position is not on the same login';
            }

        }
        else {
            $output->e = 'Need to login first!';
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}

// Meta Edit Position
function metaEditPosition() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['position']) ) $output->e = 'position is expected';
    if( !isset($_REQUEST['session']) ) $output->e = 'session is expected';
    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id'])){

            $mt5api = new mt5API();

            $request_edit['Position']  = $_REQUEST['position'];
            $request_edit['PriceTP']   = $_REQUEST['tp'];
            $request_edit['PriceSL']   = $_REQUEST['sl'];

            $path = '/api/position/update';
            $mt5api->post($path, null, json_encode($request_edit));
            $e = $mt5api->Error;
            if(!$e){
                $output->res = $mt5api->Response;
            }

        }
        else {
            $output->e = 'Need to login first!';
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}

// Meta Pending Orders
function metaPendingOrders() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['account']) ) $output->e = 'account is expected';
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id'])){

            $mt5api = new mt5API();

            $api_params['login']  = $_REQUEST['account'];
            $mt5api->get('/api/order/get_batch', $api_params);
            $e  = $mt5api->Error;
            $api = $mt5api->Response;
            if($api->retcode==="0 Done"){
                $output->res = $api->answer;
            }
            else {
                $output->e = $e;
            }
        }
        else {
            $output->e = 'Need to login first!';
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}

// Meta Cancel Order
function metaCancelOrder() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['account']) ) $output->e = 'account is expected';
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id'])){

            $mt5api = new mt5API();

            $api_params['ticket']  = $_REQUEST['order'];
            $mt5api->get('/api/order/delete', $api_params);
            $e  = $mt5api->Error;
            $api = $mt5api->Response;
            if($api->retcode==="0 Done"){
                $output->res = $api->answer;
            }
            else {
                $output->e = $e;
            }
        }
        else {
            $output->e = 'Need to login first!';
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}

// Meta Edit Order
function metaEditOrder() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['order']) ) $output->e = 'order is expected';
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id'])){

            $mt5api = new mt5API();

            $request_edit['Order']           = $_REQUEST['order'];
            //$request_edit['Type']            = $_REQUEST['Type'];
            $request_edit['VolumeCurrent']   = $_REQUEST['Volume']*10000;
            $request_edit['PriceTP']         = $_REQUEST['tp'];
            $request_edit['PriceSL']         = $_REQUEST['sl'];
            $request_edit['PriceOrder']      = $_REQUEST['PriceOrder'];
            $request_edit['PriceTrigger']    = $_REQUEST['PriceTrigger'];
            $request_edit['TypeTime']        = $_REQUEST['TypeTime'];
            if( in_array( $_REQUEST['TypeTime'],[2,3]) ){
                $request_edit['TimeExpiration']  = strtotime($_REQUEST['TimeExpiration']);
            }

            $path = '/api/order/update';
            $mt5api->post($path, null, json_encode($request_edit));
            $e = $mt5api->Error;
            if(!$e){
                $output->res = $mt5api->Response;
            }
            else {
                $output->e = $e;
            }

        }
        else {
            $output->e = 'Need to login first!';
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}

// Meta Edit Order Price
function metaEditOrderPrice() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['order']) ) $output->e = 'order is expected';
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id'])){

            $mt5api = new mt5API();

            $request_edit['Order']           = $_REQUEST['order'];
            $request_edit['PriceOrder']      = $_REQUEST['PriceOrder'];

            $path = '/api/order/update';
            $mt5api->post($path, null, json_encode($request_edit));
            $e = $mt5api->Error;
            if(!$e){
                $output->res = $mt5api->Response;
            }
            else {
                $output->e = $e;
            }

        }
        else {
            $output->e = 'Need to login first!';
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}

// Meta Account History
function metaAccountHistory() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['from']) ) $output->e = 'start date is expected';
    if( !isset($_REQUEST['to']) ) $output->e = 'end date is expected';
    if( !isset($_REQUEST['account']) ) $output->e = 'account is expected';
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id'])){

            $mt5api = new mt5API();

            $startDate = strtotime($_REQUEST['from']);
            $endDate = strtotime($_REQUEST['to']);

            $api_params['login']  = $_REQUEST['account'];
            $api_params['from']  = $startDate;
            $api_params['to']  = $endDate;
            $mt5api->get('/api/deal/get_batch', $api_params);
            $e  = $mt5api->Error;
            $api = $mt5api->Response;
            if($api->retcode==="0 Done"){
                $output->res = $api->answer;
            }
            else {
                $output->e = $e;
            }

        }
        else {
            $output->e = 'Need to login first!';
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}

// Meta Order History
function metaOrderHistory() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['order']) ) $output->e = 'order is expected';
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id'])){
            $mt5api = new mt5API();

            $api_params['ticket']  = $_REQUEST['account'];
            $mt5api->get('/api/history/get', $api_params);
            $e  = $mt5api->Error;
            $api = $mt5api->Response;
            if($api->retcode==="0 Done"){
                $output->res = $api->answer;
            }
            else {
                $output->e = $e;
            }

        }
        else {
            $output->e = 'Need to login first!';
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}

// Meta Symbol Chart
function metaSymbolChart() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['from']) ) $output->e = 'symbol expected';
    if( !isset($_REQUEST['to']) ) $output->e = 'symbol expected';
    if( !isset($_REQUEST['symbol']) ) $output->e = 'symbol expected';
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id'])){

            $mt5api = new mt5API();
            $api_params['symbol'] = $_REQUEST['symbol'];
            $api_params['from']   = $_REQUEST['from'];
            $api_params['to']     = $_REQUEST['to'];
            $api_params['data']   = 'dohlc';
            $mt5api->get('/api/chart/get', $api_params);
            $e = $mt5api->Error;
            $api = $mt5api->Response;
            if($api->retcode==="0 Done"){
                $output->res = $api->answer;
            }
            else {
                $output->e = $e;
            }

        }
        else {
            $output->e = 'Need to login first!';
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}

// Sync Counters
function syncCounters() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['account']) ) $output->e = 'account expected';
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id'])){

            global $db;
            $where = 'user_id='.$_SESSION['id'];
            $output->res['account'] = $db->count('tp',$where);

            /*
            $mt5api = new mt5API();
            $api_params['login']  = $_REQUEST['account'];
            $mt5api->get('/api/order/get_total', $api_params);
            $api = $mt5api->Response;
            if($api->retcode==="0 Done"){
                $output->res['order'] = $api->answer->total;
            }

            $mt5api->get('/api/position/get_total', $api_params);
            $api = $mt5api->Response;
            if($api->retcode==="0 Done"){
                $output->res['position'] = $api->answer->total;
            }
            */

        }
        else {
            $output->e = 'Need to login first!';
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}

// Meta Order History
function accountTradeStatus() {
    $output = new stdClass();
    $output->e = false;
    if( !isset($_REQUEST['rights']) ) $output->e = 'right is expected';
    if( !isset($_REQUEST['session']) ) $output->e = 'session expected';
    $output->login = $_REQUEST['account'];

    if(!$output->e) {
        webapp::sessionJump($_REQUEST['session']);
        if(isset($_SESSION['id'])){
            $output->res = intval(($_REQUEST['rights']&0x0000000000000004)>0);
        }
        else {
            $output->e = 'Need to login first!';
        }
        webapp::sessionJumpBack(Origin_Session_Id);
    }
    echo json_encode($output);
}
