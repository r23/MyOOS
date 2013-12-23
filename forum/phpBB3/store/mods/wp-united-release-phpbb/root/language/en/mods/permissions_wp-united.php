<?php
/** 
*
* WP-United Permissions
*
* @package WP-United
* @version $Id: 0.9.1.5  2012/12/28 John Wells (Jhong) Exp $
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
	'acl_u_wpu_subscriber'    => array('lang' => 'Can integrate as a WordPress subscriber (can view profile, write comments)', 'cat' => 'wputd'),
	'acl_u_wpu_contributor'    => array('lang' => 'Can integrate as a WordPress contributor (can write but not publish posts)', 'cat' => 'wputd'),
	'acl_u_wpu_author'    => array('lang' => 'Can integrate as a WordPress author (can write blog posts)', 'cat' => 'wputd'),
	'acl_m_wpu_editor'    => array('lang' => 'Can integrate as a WordPress editor (can edit others\' posts)', 'cat' => 'wputd'),
	'acl_a_wpu_administrator'    => array('lang' => 'Can integrate as a WordPress administrator', 'cat' => 'wputd'),
	'acl_f_wpu_xpost'	=>	array('lang' => 'Can post blog posts to this forum', 'cat' => 'wputd'),
	'acl_f_wpu_xpost_comment'	=> array('lang' => 'Can reply to blog posts cross-posted to this forum from WordPress', 'cat' => 'wputd'),
));

// Done. End of file.
