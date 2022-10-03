<?php

namespace Library\Events;

use Library\Events\Exceptions\EventDoesNotExistException;

/**
 * Hash Delimiter
 */
const DELIMITER_EV_EMITTER = "::";

/**
 * FilterExpression Event Emitter you can use and attach to a class
 * Not Super performance oriented since we're just using k string => array,
 * so it's o(n) for remove + addition + read operations;
 * Very simple and javascript inspired
 * @author Ahmad Baderkhan
 */
trait EventsEmitter
{
    private array $events = array();
    /**
     * Emits an event and executes the listeners
     * @param string $evName
     * @param any $payload
     * @return void
     */
    public function emit(string $evName , mixed $payload) : void
    {
        print_r("emitting");
        if (!isset($this->events[$evName]) || empty($this->events[$evName]))
        {
            return;
        }
        $fns = $this->events[$evName];

        foreach ($fns as $fn)
        {
            $fn["fn"]($payload);
        }
    }
    /**
     * Add a generic event listener for type
     * @param string $evName
     * @param $handler
     * @return string
     */
    public function on(string $evName,$handler) : string
    {
        if (!isset($this->events[$evName]))
        {
            $this->events[$evName] = array();
        }
        $rng = rand();
        $hash = "$evName".DELIMITER_EV_EMITTER."$rng";
        $this->events[$evName][] = ["fn"=>$handler,"id"=>$hash];

        return $hash;
    }

    /**
     * @param string $hash
     * @return void
     */
    public function remove(string $hash) : void
    {
       $hash = explode(DELIMITER_EV_EMITTER,$hash);
       $evName = $hash[0];
       $fnId = $hash[1];

       $fns = $this->events[$evName];

       if (!isset($fns))
       {
           return;
       }

       $this->events[$evName] = array_filter(
           $fns,
           function ($v , $k) use ($fnId) {
                return $v["id"] != $fnId;
           }
       ,ARRAY_FILTER_USE_BOTH);
    }
}
