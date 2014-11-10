<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {html_js_inputbox} function plugin
 *
 * File:       function.html_js_inputbox.php<br>
 * Type:       function<br>
 * Name:       js_inputbox<br>
 * Date:       06.Oct.2005<br>
 * Purpose:    Prints out a list of text input types<br>
 * Examples:
 * <pre>
 * {html_js_inputbox values=$ids}
 * {html_js_inputbox class='inputbox' name='searchword' value=$lang.text }
 * </pre>
 * @author r23 <info@r23.de>
 * @version    1.0
 * @param array
 * @param Smarty
 * @return string
 * @uses smarty_function_escape_special_chars()
 */
function smarty_function_html_js_inputbox($params, &$smarty)
{
    require_once $smarty->_get_plugin_filepath('shared','escape_special_chars');

    $class = 'inputbox';
    $name = 'keywords';
    $size = '20';
    $maxlength = '40';
    $value = 'search...';
    $extra = '';

    foreach($params as $_key => $_val) {
        switch($_key) {
            case 'class':
            case 'name':
            case 'value':
                $$_key = (string)$_val;
                break;

            case 'size':
            case 'maxlength':
                $$_key = intval($_val);
                break;

            default:
                if(!is_array($_val)) {
                    $extra .= ' '.$_key.'="'.smarty_function_escape_special_chars($_val).'"';
                } else {
                    $smarty->trigger_error("html_js_inputbox: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
                }
                break;
        }
    }

    return '<input class="' . $class . '" type="text" name="' . $name . '" size="' . $size . '" maxlength="' . $maxlength . '"  value="' . $value . '"  onblur="if(this.value==\'\') this.value=\'' . $value . '\';" onfocus="if(this.value==\''. $value . '\') this.value=\'\';" />';

}

?>