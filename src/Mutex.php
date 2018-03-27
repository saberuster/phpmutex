<?php

namespace PhpMutex;


use Predis\Client;

/**
 * Class Mutex
 *
 * @author  saberuster
 * @package \PhpMutex
 * @property string         $name
 * @property \Predis\Client $redis
 */
class Mutex
{
    protected $name;
    protected $redis;

    public function __construct( Client $redis, $name )
    {
        $this->name  = $name;
        $this->redis = $redis;
    }


    public function lock( $expire = 1 )
    {
        if ( $this->redis->setnx($this->name, '1') == '0' ) {
            return 0;
        }

        if ( $expire > 0 && $this->redis->expire($this->name, $expire) == '0' ) {
            return -1;
        }

        return 1;
    }


    public function unlock()
    {
        if ( $this->redis->del([ $this->name ]) == '1' ) {
            return 1;
        }

        return 0;
    }
}
