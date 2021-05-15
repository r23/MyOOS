<?php
/**
 * MyOOS [Shopsystem]
 * https://www.oos-shop.de
 *
 * @license GNU/LGPLv2 (or at your option, any later version).
 * @package Phoenix
 * @subpackage Phoenix_Session
 */

use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag; 
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

/**
 * Phoenix_Session class.
 *
 * @deprecated
 */
class Phoenix_Session extends Session
{
    /**
     * The message type for status messages, to use with, for example, {@link hasMessages()}.
     *
     * @var string
     */
    const MESSAGE_STATUS = 'status';

    /**
     * The message type for warning messages, to use with, for example, {@link hasMessages()}.
     *
     * @var string
     */
    const MESSAGE_WARNING = 'warning';

    /**
     * The message type for error messages, to use with, for example, {@link hasMessages()}.
     *
     * @var string
     */
    const MESSAGE_ERROR = 'error';

    public function start()
    {
	
		$user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
		$spider_flag = false;
		$spider_kill_sid = 'false';

		// set the top level domains
		// $host = System::serverGetVar('HTTP_HOST');
		$current_domain = oos_server_get_top_level_domain(OOS_HTTPS_SERVER);

		// garbage collection may disabled by default (e.g., Debian)
		if (ini_get('session.gc_probability') == 0) {
			@ini_set('session.gc_probability', 1);
		}		
		
		/*
        $config = array(
            'gc_probability' => System::getVar('gc_probability'),
            'gc_divisor' => 10000,
            'gc_maxlifetime' => System::getVar('secinactivemins'),
        );
		*/
		
		/*
        $path = System::getBaseUri();
		*/
		
        if (empty($path)) {
            $path = '/';
        } elseif (substr($path, -1, 1) != '/') {
            $path .= '/';
        }

        $config['cookie_path'] = $path;

        $host = $current_domain;

        if (($pos = strpos($host, ':')) !== false) {
            $host = substr($host, 0, $pos);
        }

        // PHP configuration variables
        // Set lifetime of session cookie
		/*
        $seclevel = System::getVar('seclevel');
        switch ($seclevel) {
            case 'High':
                // Session lasts duration of browser
                $lifetime = null;
                // Referer check
                // ini_set('session.referer_check', $host.$path);
                $config['referer_check'] = $host;
                break;
            case 'Medium':
                // Session lasts set number of days
                $lifetime = System::getVar('secmeddays') * 86400;
                break;
            case 'Low':
            default:
                // (Currently set to 1 year)
                $lifetime = 31536000;
                break;
        }
		*/
		$lifetime = null;
        $config['cookie_lifetime'] = $lifetime;

		# possible values: 'strict', 'lax' and null
		$config['cookie_samesite'] = 'strict';

        $this->storage->setOptions($config);
        return parent::start();		
		
    }


    /**
     * Check if session has started.
     *
     * @return boolean
     */
    public function hasStarted()
    {
        return $this->isStarted();
    }

    /**
     * Expire session.
     *
     * Changes session ID and lose all data associated with a session.
     *
     * @return void
     */
    public function expire()
    {
        $this->invalidate();
    }

    /**
     * Regenerate session.
     *
     * Changes the session ID while retaining session data.
     *
     * @return void
     */
    public function regenerate()
    {
        $this->migrate();
    }

    /**
     * Add session message to the stack for a given type.
     *
     * @param string $type  Type.
     * @param mixed  $value Value.
     *
     * @return void
     */
    public function addMessage($type, $value)
    {
        $this->getFlashBag()->add($type, $value);
    }

    /**
     * Get special attributes by type.
     *
     * @param string $type    Type.
     * @param mixed  $default Default value to return (default = array()).
     *
     * @return mixed
     */
    public function getMessages($type, $default = array())
    {
        return $this->getFlashBag()->get($type, $default);
    }

    /**
     * Has attributes of type.
     *
     * @param string $type Type.
     *
     * @return boolean
     */
    public function hasMessages($type)
    {
        return $this->getFlashBag()->has($type);
    }

    /**
     * Clear messages of type.
     *
     * @param string $type Type.
     *
     * @return void
     */
    public function clearMessages($type = null)
    {
        $this->getFlashBag()->get($type);
    }

    /**
     * Set session variable.
     *
     * @param string $key       Key.
     * @param mixed  $default   Default = null.
     * @param string $namespace Namespace.
     *
     * @throws InvalidArgumentException If illegal namespace received.
     *
     * @return mixed
     */
    public function get($key, $default = null, $namespace = '/')
    {
        return parent::get($key, $default);
    }

    /**
     * Set a session variable.
     *
     * @param string $key       Key.
     * @param mixed  $value     Value.
     * @param string $namespace Namespace.
     *
     * @throws InvalidArgumentException If illegal namespace received.
     *
     * @return void
     */
    public function set($key, $value, $namespace = '/')
    {
        parent::set($key, $value);
    }

    /**
     * Delete session variable by key.
     *
     * @param string $key       Key.
     * @param string $namespace Namespace.
     *
     * @throws InvalidArgumentException If illegal namespace received.
     *
     * @return void
     */
    public function del($key, $namespace = '/')
    {
        parent::remove($key);
    }

    /**
     * Check if session variable key exists.
     *
     * @param string $key       Key.
     * @param string $namespace Namespace.
     *
     * @throws InvalidArgumentException If illegal namespace received.
     *
     * @return boolean
     */
    public function has($key, $namespace = '/')
    {
        return parent::has($key);
    }

}
