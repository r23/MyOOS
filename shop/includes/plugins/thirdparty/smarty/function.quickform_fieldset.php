<?php
/**
 * Smarty plugin
 * @file function.quickform_fieldset.php
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {quickform_fieldset} function plugin
 *
 * Type:     function
 * Name:     quickform_fieldset
 * Date:     2006-01-21
 * Purpose:  Convenience function to output form elements
 *           in a fieldset from a PEAR::HTML_QuickForm
 *           object assigned to Smarty via the
 *           HTML_QuickForm_Renderer_ArraySmarty renderer.
 *           Each element gets wrapped in a div, which gets assigned
 *           a class="required" or "error" based on whether that
 *           element is required and/or has an error.
 *           NOTE that CSS will be required to style the HTML.
 * Requires: PEAR::HTML_QuickForm
 *           PEAR::HTML_QuickForm_Renderer_ArraySmarty (included with HTML_Quickform)
 * Params:
 *         - form   = The form variable as assigned by
 *                    renderer->toArray(). Required.
 *         - fields = Comma-delimited list or assigned array variable
 *                    of QF fields. Required.
 *         - legend = The legend element of the fieldset.  Optional.
 *         - class  = Class attribute.  Optional.
 *         - id     = ID attribute.  Optional.
 *
 * Examples:
 * <pre>
 * {quickform_fieldset form=$form_data}
 * {quickform_fieldset form=$form_data legend="Billing Info"}
 * {quickform_fieldset form=$form_data legend="Billing Info" fields="first_name, last_name, address"}
 * {quickform_fieldset form=$form_data legend=$billing_legend fields=$billing_fields}
 * {quickform_fieldset form=$form_data legend="Billing" id="billing" fields=$billing_fields}
 * </pre>
 * Sample Output:
 * <pre>
 * <fieldset id="billing"><legend>Billing</legend>
 * <div class="required">
 * <label for="first_name">First Name</label>
 * <input type="text" name="first_name" id="first_name" />
 * </div>
 * <div class="required">
 * <label for="last_name">Last Name</label>
 * <input type="text" name="last_name" id="last_name" />
 * </div>
 * </pre>
 *
 * @author Max Schwanekamp http://www.neptunewebworks.com/contact
 * @version 1.1
 * @param array
 * @param Smarty
 * @return string Valid XHTML
 * @todo Need a better method for dealing with the ID attribute for elements
 */
function smarty_function_quickform_fieldset($params, &$smarty)
{
        /* Extract params into local vars
           yields $form, $fields and optionally $legend, $class, $id */
        extract($params);

        /* $form is required */
        if ( (!isset($form)) )
        {
            $smarty->trigger_error("quickform_fieldset: missing 'form' parameter");
            return;
        }
        /* $fields is required */
        if ( (!isset($fields)) )
        {
            $smarty->trigger_error("quickform_fieldset: missing 'fields' parameter");
            return;
        }

        //break out the fields into an array
        $fields = explode(',',$fields);
        $fields = array_map('trim',$fields);

        //assemble fieldset and legend html
        $html = "<fieldset"
              . (isset($id) ? ' id="'.$id.'"' : null)
              . (isset($class) ? ' id="'.$class.'"' : null)
              . ">";

        if( isset($legend) && $legend!='' )
        {
                $html .= "\n\t<legend>$legend</legend>";
        }

        //loop through fields to assemble html string for each element.
        for($i = 0; $i < count($fields); $i++)
        {
                $el = $form[$fields[$i]];
                if(! $el )
                {
                        continue;
                }

                //radio buttons are rendered as a deeper array
                if( is_array($el) && (!in_array('html', array_keys($el))) )
                {
					$keys = array_keys($el);
                    $el['label'] = $el[$keys[0]]['label'];
                    $el['required'] = $el[$keys[0]]['required'];
                    $el['html'] = "\n\t\t<fieldset"
								.' class="radiogroup" id="'.$el['name'].'">'.
								"\n\t\t\t<legend>".$el['label']."</legend>";
                    for($i = 0; $i < count($keys); $i++)
                    {
                        $el['html'] .= "\n\t\t\t".$el[$keys[$i]]['html'];
                    }
                    $el['html'] .= "\n\t\t</fieldset>";
                }
                else
                {
                    //KLUDGE to add the ID attribute for each element,
                    //same as name attrib, if id is not already set.
                    //QuickForm doesn't auto-add ID except for radio btns and checkboxes
                    if (! stristr($el['html'],' id="') )
                    {
                        $el['html'] = str_replace(' name="'.$el['name'].'"',
                        	' name="'.$el['name'].'" id="'.$el['name'].'"',
                            $el['html']);
                    }

                    $el['html'] = '<label for="'.$el['name'].'">'
                                .  $el['label'].'</label>'.$el['html'].'';
                }

                //set block (div) class according to required, help text and error.
                //this can be easily modified to add other class values according
                //to element attributes as set in your php script
                $divclass = array();
                $class_text = '';
                if ( $el['required'] ) $divclass[] = 'required';
                if ( $el['error'] ) $divclass[] = 'error';
				if ( 'checkbox' == $el['type']) $divclass[] = 'checkbox';
                if (! empty($divclass) ) $class_text = ' class="'.implode(' ', $divclass).'"';

                //assemble the html string for this element
                $html .= "\n\t<div".$class_text.'>'.$el['html']."\n\t</div>";

        } // for
        $html .= "\n</fieldset>\n";

        return $html;
}


?>