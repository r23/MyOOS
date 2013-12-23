<?php
/** 
*
* WP-United Permissions [French]
*
* @package WP-United
* @version $Id: v0.9.2. 2013/01/09 John Wells (Jhong) Exp $
* @copyright (c) 2006-2013 wp-united.com
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
    'acl_u_wpu_subscriber'    	=> array('lang' => 'Peut intégrer en tant qu\'abonné WordPress (peut voir le profil, écrire des commentaires)', 'cat' => 'wputd'),
    'acl_u_wpu_contributor'    	=> array('lang' => 'Peut intégrer en tant que contributeur WordPress (écriture, mais pas publier de messages)', 'cat' => 'wputd'),
    'acl_u_wpu_author'    		=> array('lang' => 'Peut intégrer comme un auteur WordPress (peut écrire des articles dans WordPress)', 'cat' => 'wputd'),
    'acl_m_wpu_editor'    		=> array('lang' => 'Peut intégrer un éditeur de WordPress (peut éditer les messages des autres)', 'cat' => 'wputd'),
    'acl_a_wpu_administrator'   => array('lang' => 'Peut intégrer en tant qu\'administrateur WordPress', 'cat' => 'wputd'),
	'acl_f_wpu_xpost'			=> array('lang' => 'Peut écrire des articles à ce forum', 'cat' => 'wputd'),
	'acl_f_wpu_xpost_comment'	=> array('lang' => 'Peut répondre aux articles du blog cross-poster à ce forum à partir de wordpress', 'cat' => 'wputd'),
));
?>
