<?php
/**
 * Matomo - free/libre analytics platform
 *
 * @link https://matomo.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\CustomVariables\Categories;

use Piwik\Category\Subcategory;
use Piwik\Piwik;

class CustomVariablesSubcategory extends Subcategory
{
    protected $categoryId = 'General_Visitors';
    protected $id = 'CustomVariables_CustomVariables';
    protected $order = 45;

    public function getHelp()
    {
        return '<p>' . Piwik::translate('CustomVariables_CustomVariablesSubcategoryHelp1') . '</p>'
            . '<p><a href="https://matomo.org/docs/custom-variables/" rel="noreferrer noopener" target="_blank">' . Piwik::translate('CustomVariables_CustomVariablesSubcategoryHelp2') . '</a></p>'
            ;
    }
}
