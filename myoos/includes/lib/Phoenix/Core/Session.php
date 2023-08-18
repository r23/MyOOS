<?php
/**
 * MyOOS [Shopsystem]
 * https://www.oos-shop.de
 *
 * @license    GNU/LGPLv2 (or at your option, any later version).
 * @package    Phoenix
 * @subpackage Phoenix_Session
 */

use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;
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
    final public const MESSAGE_STATUS = 'status';

    /**
     * The message type for warning messages, to use with, for example, {@link hasMessages()}.
     *
     * @var string
     */
    final public const MESSAGE_WARNING = 'warning';

    /**
     * The message type for error messages, to use with, for example, {@link hasMessages()}.
     *
     * @var string
     */
    final public const MESSAGE_ERROR = 'error';

    public function start()
    {
        $user_agent = strtolower((string) $_SERVER['HTTP_USER_AGENT']);
        $spider_flag = false;
        $spider_kill_sid = 'false';

        // set the top level domains
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
        } elseif (!str_ends_with((string) $path, '/')) {
            $path .= '/';
        }

        $config['cookie_path'] = $path;

        $host = $current_domain;

        if (($pos = strpos((string) $host, ':')) !== false) {
            $host = substr((string) $host, 0, $pos);
        }

        // Currently set to 1 year
        $lifetime = 31_536_000;
        $config['cookie_lifetime'] = $lifetime;

        // possible values: 'strict', 'lax' and null
        $config['cookie_samesite'] = 'strict';

        $options['cache_limiter'] = session_cache_limiter();

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
    public function addMessage($type, mixed $value)
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
    public function getMessages($type, mixed $default = [])
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
