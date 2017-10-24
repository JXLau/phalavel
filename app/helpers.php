<?php
/**
 * Author: Jason
 */
use Phalcon\Config;
use Phalcon\Di;
use Phalcon\Http\Response;

/**
 * Get the available container instance.
 *
 * @param  string $make
 * @param  array $parameters
 * @return mixed|Di
 * @throws Phalcon\Di\Exception
 */
function app($make = null, array $parameters = null)
{
    $di = Di::getDefault();

    if (is_null($make)) {
        return $di;
    }

    return $di->get($make, $parameters);
}

/**
 * Get / set the specified configuration value.
 *
 * If an array is passed as the key, we will assume you want to set an array of values.
 *
 * @param  string $key
 * @return mixed|Config
 */
function config($key = null)
{
    if (is_null($key)) {
        return app('config');
    }

    return app('config')->get($key);
}

if (! function_exists('response')) {
    /**
     * Return an Http Response
     *
     * @param  int $code
     * @param  string $message
     * @param  string $type
     * @return Response
     */
    function response($data, $type, $code = '200')
    {
        /** @var Response $response */
        $response = app('response');

        if ($code == 404) {
            $response->setStatusCode($code, 'Not Found');
            $response->setContent($data ?: 'This is crazy, but this page was not found!'.PHP_EOL);
        } else {
            $response->setStatusCode($code);

            if ($data) {
                if (strtolower($type) == 'json' && is_array($data)) {
                    $response->setJsonContent($data);
                } else {
                    $response->setContent($data);    
                }
            }
        }

        return $response->send();
    }
}

if ( !function_exists('abort')) {
    /**
     * Return an Http Exception
     *
     * @param  int $code
     * @param  string $message
     * @return Response
     */
    function abort($code, $message = '')
    {
        /** @var Response $response */
        return response($message, 'content', $code);
    }
}

if (! function_exists('session')) {
    /**
     * Get / set the specified session value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string $key
     * @param  mixed $default
     * @return mixed
     */
    function session($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('session');
        }

        if (is_array($key)) {
            return app('session')->set(key($key), current($key));
        }

        return app('session')->get($key, $default);
    }
}

if (! function_exists('dispatch')) {
    /**
     * Dispatch a job to its appropriate handler.
     *
     * @param  mixed  $job
     * @return mixed
     */
    function dispatch($job)
    {
        return app('queue')->push($job);
    }
}