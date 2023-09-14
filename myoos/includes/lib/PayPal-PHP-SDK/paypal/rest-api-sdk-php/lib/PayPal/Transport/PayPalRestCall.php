<?php
namespace PayPal\Transport;

use PayPal\Core\PayPalHttpConfig;
use PayPal\Core\PayPalHttpConnection;
use PayPal\Core\PayPalLoggingManager;
use PayPal\Rest\ApiContext;

/**
 * Class PayPalRestCall
 *
 * @package PayPal\Transport
 */
class PayPalRestCall
{


    /**
     * Paypal Logger
     *
     * @var PayPalLoggingManager logger interface
     */
    private $logger;


    /**
     * Default Constructor
     */
    public function __construct(private readonly ApiContext $apiContext)
    {
        $this->logger = PayPalLoggingManager::getInstance(self::class);
    }

    /**
     * @param  array  $handlers Array of handlers
     * @param  string $path     Resource path relative to base service endpoint
     * @param  string $method   HTTP method - one of GET, POST, PUT, DELETE, PATCH etc
     * @param  string $data     Request payload
     * @param  array  $headers  HTTP headers
     * @return mixed
     * @throws \PayPal\Exception\PayPalConnectionException
     */
    public function execute($path, $method, $handlers = [], $data = '', $headers = [])
    {
        $config = $this->apiContext->getConfig();
        $httpConfig = new PayPalHttpConfig(null, $method, $config);
        $headers = $headers ?: [];
        $httpConfig->setHeaders(
            $headers +
            ['Content-Type' => 'application/json']
        );

        // if proxy set via config, add it
        if (!empty($config['http.Proxy'])) {
            $httpConfig->setHttpProxy($config['http.Proxy']);
        }

        /**
 * @var \Paypal\Handler\IPayPalHandler $handler 
*/
        foreach ($handlers as $handler) {
            if (!is_object($handler)) {
                $fullHandler = "\\" . (string)$handler;
                $handler = new $fullHandler($this->apiContext);
            }
            $handler->handle($httpConfig, $data, ['path' => $path, 'apiContext' => $this->apiContext]);
        }
        $connection = new PayPalHttpConnection($httpConfig, $config);
        $response = $connection->execute($data);

        return $response;
    }
}
