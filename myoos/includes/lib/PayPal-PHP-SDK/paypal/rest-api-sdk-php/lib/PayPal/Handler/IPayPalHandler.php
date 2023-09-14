<?php

namespace PayPal\Handler;

/**
 * Interface IPayPalHandler
 *
 * @package PayPal\Handler
 */
interface IPayPalHandler
{
    /**
     *
     * @param  \Paypal\Core\PayPalHttpConfig $httpConfig
     * @param  string                        $request
     * @return mixed
     */
    public function handle($httpConfig, $request, mixed $options);
}
