<?php
namespace WEBAPP;

/**
 * html
 * Public Static Functions
 */
class html{

    /**
     * Minify HTML
     */
    public static function minify(string $html){
        $html = str_replace(array("\r","\n","  "),'', $html);
        $html = str_replace(array("> <",">\t<"),'><', $html);
        $html = str_replace(array('" ',' "'),'"', $html);
        return $html;
    }

    /**
     * Permit Error
     * @param string $target
     * @param string $action
     * @param string $role
     * @return string
     */
    public static function permitError(string $target, string $action, string $role): string
    {
        $block =
            '
                You are not permitted to do [<strong>'.$action.'</strong>] on [<strong>'.$target.'</strong>] screen as a [<strong>'.$role.'</strong>]
        ';
        return self::minify($block);
    }

    /**
     * Screen
     * @param $screen
     * @return array|string|string[]
     */
    public static function screen($screen, $params=null){
        ob_start();
        try{
            global $_L;
            If(is_file(APP_ROOT."screens".DIRECTORY_SEPARATOR."$screen.php"))
                include_once(APP_ROOT."screens".DIRECTORY_SEPARATOR."$screen.php");
            else
                throw new Exception("Screen <strong>$screen</strong> not found!");
            $block = ob_get_contents();
        } catch (Exception $e){
            $block = '<div id="'.$screen.'" class="screen-wrapper alert alert-warning">'.$e->getMessage()."</div>";
        }
        ob_end_clean();
        return self::minify($block);
    }

}