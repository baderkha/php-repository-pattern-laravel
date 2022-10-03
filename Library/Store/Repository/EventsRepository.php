<?php

namespace Library\Store\Repository;

/**
 * Events repository you can extend in order to house event handling in your specific implementation
 *
 * you can also expose this to the client side if you really want , could be useful for things like
 * sns events or broker events;
 */
interface EventsRepository
{
    const CREATED_EV = "created";
    const UPDATED_EV = "updated";
    const DELETED_EV = "deleted";

    /**
     * fired when a model/models (array) gets created
     * @param $handler
     * @return void
     */
    function OnCreateOneOrMany($handler):void;
    /**
     * fired when a model/models (array) gets deleted
     * @param $handler
     * @return void
     */
    function OnDeleteOneOrMany($handler):void;
    /**
     * fired when a model/models (array) gets updated
     * @param $handler
     * @return void
     */
    function OnUpdateOneOrMany($handler):void;
}
