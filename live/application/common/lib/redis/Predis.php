<?php

namespace app\common\lib\redis;

class Predis
{
    public $redis = "";
    /**
     * 定义单例模式的变量
     * @var null
     */
    private static $_instance = null;

    public static function getInstance()
    {
        if (empty(Predis::$_instance)) {
            Predis::$_instance = new Predis();
        }
        return Predis::$_instance;
    }

    private function __construct()
    {

        $this->redis = new \Redis();
        $result = $this->redis->connect(config('redis.host'), config('redis.port'), config('redis.timeOut'));
        if ($result === false) {
            throw new \Exception('redis connect error');
        }
    }

    /**
     * set
     * @param $key
     * @param $value
     * @param int $time
     * @return bool|string
     */
    public function set($key, $value, $time = 0)
    {
        if (!$key) {
            return '';
        }
        if (is_array($value)) {
            $value = json($value);
        }
        if (!$time) {
            return $this->redis->set($key, $value);
        }

        return $this->redis->setex($key, $time, $value);
    }

    /**
     * get
     * @param $key
     * @return bool|string
     */
    public function get($key)
    {
        if (!$key) {
            return '';
        }

        return $this->redis->get($key);
    }

    /**
     * @param $key
     * @return array
     */
    public function sMembers($key)
    {
        return $this->redis->sMembers($key);
    }

    /**
     * @param $name
     * @param $arguments
     * @return array
     */
    public function __call($name, $arguments)
    {
        if (count($arguments) != 2) {
            return '';
        }
        $this->redis->$name($arguments[0], $arguments[1]);
    }

    public function sadd($k, $v)
    {
        return $this->redis->sAdd($k, $v);
    }

    public function srem($k, $v)
    {
        return $this->redis->sRem($k, $v);
    }


}