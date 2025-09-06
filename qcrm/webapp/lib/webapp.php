<?php

/**
 * webapp
 * Public Static Functions
 */
class webapp{

    public static $MetaErrorMsg = array(
        '10001' => ' Request is on the way.',
        '10002' => 'Request accepted.',
        '10003' => 'Request processed.',
        '10004' => 'Requote in response to the request.',
        '10005' => 'Prices in response to the request.',
        '10006' => 'Request rejected.',
        '10007' => 'Request canceled.',
        '10008' => 'An order placed as a result of the request.',
        '10009' => 'Request fulfilled.',
        '10010' => 'Request partially fulfilled.',
        '10011' => 'Common error of request.',
        '10012' => 'Request timed out.',
        '10013' => 'Invalid request.',
        '10014' => 'Invalid volume.',
        '10015' => 'Invalid price.',
        '10016' => 'Wrong stop levels or price.',
        '10017' => 'Trade is disabled.',
        '10018' => 'Market is closed.',
        '10019' => 'Not enough money.',
        '10020' => 'Price has changed.',
        '10021' => 'No price.',
        '10022' => 'Invalid order expiration.',
        '10023' => 'Order has been changed.',
        '10024' => 'Too many trade requests. For example, this error can be returned in response to an attempt to send more than 128 trade requests from one Manager API instance.',
        '10025' => 'Request does not contain changes.',
        '10026' => 'Autotrading disabled on the server.',
        '10027' => 'Autotrading disabled on the client side.',
        '10028' => 'Request blocked by the dealer.',
        '10029' => 'Modification failed due to order or position being close to market.',
        '10030' => 'Fill mode is not supported.',
        '10031' => 'No connection.',
        '10032' => 'Allowed only for real accounts.',
        '10033' => 'Reached the limit on the number of orders.',
        '10034' => 'Reached the volume limit.',
        '10035' => 'Invalid or prohibited order type.',
        '10036' => 'Position is already closed. For example, this error appears when attempting to modify the stop levels of an already closed position.',
        '10037' => 'Used for internal purposes.',
        '10038' => 'Volume to be closed exceeds the current volume of the position.',
        '10039' => "Order to close the position already exists. The error may appear in the hedging mode:
                    •when trying to closed a position with an opposite one in case there's already an order to close that position
                    •when trying to close the entire position or a part of it in case the total volume of existing orders to close it and the newly placed order exceeds the current volume of the position",
        '10040' => 'The number of open positions simultaneously present on an account can be limited by the settings of a group. After a limit is reached, the server returns the MT_TRADE_RETCODE_REQUEST_LIMIT_POSITIONS error when attempting to place an order. The limitation operates differently depending on the position accounting type:
                    •Netting — number of open positions is considered. When a limit is reached, the platform disables placing new orders whose execution may increase the number of open positions. In fact, the platform allows placing orders only for the symbols that already have open positions. The current pending orders are not considered since their execution may lead to changes in the current positions but it cannot increase their number.
                    •Hedging — pending orders are considered together with open positions, since a pending order activation always leads to opening a new position. When a limit is reached, the platform disables placing both new market orders for opening positions and pending orders.',
        '10041' => 'Request rejected, order canceled. This code is returned when the action IMTConRoute::ACTION_CANCEL_ORDER in a routing rule is applied.',
        '10042' => 'The request is rejected, because the "Only long positions are allowed" rule is set for the symbol  (IMTConSymbol::TRADE_LONGONLY).',
        '10043' => 'The request is rejected, because the "Only short positions are allowed" rule is set for the symbol (IMTConSymbol::TRADE_SHORTONLY).',
        '10044' => 'The request is rejected, because the "Only position closing is allowed" rule is set for the symbol (IMTConSymbol::TRADE_CLOSEONLY).',
        '10045' => 'Position closure is not allowed by the FIFO rule. It is used for the groups with the enabled IMTConGroup::TRADEFLAGS_FIFO_CLOSE option, according to which all positions should be closed strictly in the order in which they were opened: the oldest one should be closed first, then the next one, etc.',
        '10046' => 'Opening of a position or placing of a pending order is not possible because hedge positions are prohibited. The error is returned if a user tries to execute a trading operation in the case the IMTConGroup::TRADEFLAGS_HEDGE_PROHIBIT flag is enabled for the group and the user already has an opposite order or position for the same symbol.'
    );

    /**
     * Check Session Role
     */
    public static function checkSession(){
        global $_L;
        $_L->set($_SESSION['language']);
        if( isset($_SESSION['id']) ) {
            global $db;
            $where = "type='avatar' AND user_id=".$_SESSION['id'];
            $media = $db->selectRow('media',$where);
            if( $media['media'] )
                $_SESSION['webapp']['avatar'] = 'media/'.$media['media'];
            global  $userManager;
            $_SESSION['webapp']['user'] = $userManager->get($_SESSION['id']);

            if($_SESSION['type']==='Admin')
                $_SESSION['webapp']['role'] = 'Admin';
            else if(isset($_SESSION['type']))
                $_SESSION['webapp']['role'] = $_SESSION['type'];
            else
                $_SESSION['webapp']['role'] = 'Guest';
        }
        else{
            $_SESSION['webapp']['role'] = 'Guest';
        }
        return $_SESSION['webapp']['role'];
    }

    /**
     * Session Jump
     * @param string $session_id
     * @return false|string
     */
    public static function sessionJump(string $session_id){
        session_write_close();
        session_id($session_id);
        session_start();
        self::checkSession();
    }

    /**
     * Session Jump Back
     */
    public static function sessionJumpBack(string $session_id){
        session_write_close();
        session_id($session_id);
        session_start();
    }

    /**
     * Add Notification
     * @param $user_id
     * @param $type
     * @param $title
     * @param $notion
     * @param $content
     * @return false|int
     */
    public static function notificaionAdd($user_id, $type, $icon, $title, $notion, $content){
        global $db;
        $notify['user_id'] = $user_id;
        $notify['type']    = $type;
        $notify['icon']    = $icon;
        $notify['title']   = $title;
        $notify['notion']  = $notion;
        $notify['content'] = $content;
        $notify['status']  = 0;
        return $db->insert('webapp_notifications', $notify);
    }

    /**
     * Update Notification Status
     * @param $notificaion_id
     * @param $stats
     * @return bool
     */
    public static function notificaionStatus($notificaion_id, $stats){
        global $db;
        $notify['status']  = $stats;
        return $db->updateId('webapp_notifications',$notificaion_id, $notify);
    }

    /**
     * Seen All Notification
     * @param $user_id
     * @return bool
     */
    public static function notificaionSeenAll($user_id){
        global $db;
        $notify['status']  = 1;
        $where = "user_id=$user_id";
        return $db->updateAny('webapp_notifications', $notify, $where);
    }

    /**
     * Delete All Notification
     * @param $user_id
     * @return bool
     */
    public static function notificaionDeleteAll($user_id){
        global $db;
        $where = "user_id=$user_id";
        return $db->deleteAny('webapp_notifications', $where);
    }

    /**
     * List User Notifications
     * @param $user_id
     * @param $count
     * @param $stats
     * @return array|bool
     */
    public static function notificaionsList($user_id, $from=0, $count=0){
        global $db;
        $where = "user_id=$user_id AND status IN(0,1)";
        if($from) $where = "$from>id AND ".$where;
        return $db->select('webapp_notifications', $where, '*', $count, 'id DESC');
    }

    /**
     * List User Notifications
     * @param $user_id
     * @param $count
     * @param $stats
     * @return array|bool
     */
    public static function countUnreadNotifications($user_id){
        global $db;
        $where = "user_id=$user_id AND status=0";
        return $db->count('webapp_notifications', $where);
    }

    /**
     * Get Notification
     */
    public static function getNotification($id){
        global $db;
        return $db->selectId('webapp_notifications', $id);
    }

    /**
     * Check Permit
     * @param string $target
     * @param string $act
     * @param bool $echo
     * @return bool
     */
    public static function checkPermit(string $target,string $act, bool $echo=false): bool
    {
        if($_SESSION['type']==='Admin') return true;
        global $permits;
        if($permits->$target[$_SESSION['type']]){
            $res = (bool) $permits->$target[$_SESSION['type']][$act] ?? false;
        } else {
            $res = (bool) $permits->$target['*'][$act] ?? false;
        }
        if($echo && !$res)
            echo \WEBAPP\html::permitError($target, $act, $_SESSION['type']);
        return $res;
    }

    public static function randString( $length ) {
        $chars = "abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ123456789";
        return substr(str_shuffle($chars),0,$length);
    }

    public static function genPassword($length=8, $symbol='@') {
        $shuffled_char = str_shuffle('ABEFGHJKLMNPQRTYZabdefghijkmnpqrtyz');
        $shuffled_num = str_shuffle('123456789');
        return str_shuffle(substr($shuffled_char,-($length-3)).$symbol).substr($shuffled_num,0,1).substr($shuffled_char,0,1);
    }

    public static function isTradeOpenByGroup($symbol, $group)
    {
        $mt5api = new mt5API();
        $api_symbol['symbol'] = $symbol;
        $api_symbol['group'] = $group;

        $mt5api->get('/api/symbol/get_group', $api_symbol);
        $e = $mt5api->Error;
        $api_symbol = $mt5api->Response->answer->SessionsTrades;
        $is_open=false;
        $week_day= date('w',strtotime("today"));
        $time_in_min = ceil( (time()-strtotime("today"))/60 );
        $symbol_times = $api_symbol[$week_day];
        if($symbol_times) foreach ($symbol_times as $symbol_time) {
            if( ($symbol_time->Open <= $time_in_min) && ($time_in_min <= $symbol_time->Close) ) {
                $is_open = true;
                break;
            }
        }
        return $is_open;
    }

    public static function isTradeOpenByLogin($symbol, $login)
    {
        $group = self::getLoginGroup($login);
        if($group['name']) return self::isTradeOpenByGroup($symbol, $group['name']);
        return $login;
    }

    public static function getLoginGroup($login)
    {
        $mt5api = new mt5API();
        $api_group['login'] = $login;
        $mt5api->get('/api/user/group', $api_group);
        $group['error'] = $mt5api->Error;
        $api_group = $mt5api->Response;
        $group['name'] = $api_group->answer->group;
        $group['demo_groups'] = array('LidyaGOLD', 'LidyaSTD', 'LidyaVIP');
        $group['is_demo'] = $group['name'] != str_ireplace($group['demo_groups'],"XX",$group['name']);
        return $group;
    }

}