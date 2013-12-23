<?php
/** 
*
* WP-United Permissions [Serbian, Latin Script]
*
* @package WP-United
* @version $Id: v0.9.0RC3 2012/12/06 John Wells (Jhong) Exp $
* @copyright (c) 2006-2010 wp-united.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
// General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 51 Franklin Street - Fifth Floor, Boston, MA  02110-1301, USA.
//


/**
* DO NOT CHANGE
*/
if (empty($lang) || !is_array($lang))
{
    $lang = array();
}

// Adding new category
$lang['permission_cat']['wputd'] = 'WP-United';

	// Adding new permission set
	//$lang['permission_type']['wp_'] = 'WordPress Permissions';

// Adding the permissions
$lang = array_merge($lang, array(
    'acl_u_wpu_subscriber'    	=> array('lang' => 'Moguća integracija kao WordPress pretplatnik (može da gleda profil, može da piše komentare)', 'cat' => 'wputd'),
    'acl_u_wpu_contributor'    	=> array('lang' => 'Moguća integracija kao WordPress saradnik (može da piše postove, ali ne i da ih objavi)', 'cat' => 'wputd'),
    'acl_u_wpu_author'    		=> array('lang' => 'Moguća integracija kao WordPress autor(može da piše blog postove)', 'cat' => 'wputd'),
    'acl_m_wpu_editor'    		=> array('lang' => 'Moguća integracija kao WordPress urednik (može da ažurira postove drugih)', 'cat' => 'wputd'),
    'acl_a_wpu_administrator'   => array('lang' => 'Moguća integracija kao WordPress administrator', 'cat' => 'wputd'),
	'acl_f_wpu_xpost'			=> array('lang' => 'Može da postuje blog postove na ovom forumu', 'cat' => 'wputd'),
	'acl_f_wpu_xpost_comment'	=> array('lang' => 'Može da odgovara na unakrsne postove na forumu iz WordPress', 'cat' => 'wputd'),
));
?>
