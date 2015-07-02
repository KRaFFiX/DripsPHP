<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 29.01.15 - 11:25.
 */
namespace DripsPHP\API;

/**
 * Class Dispatcher.
 *
 * The dispatcher class is intended as a template.
 * With the help of the class, it is possible to trigger events within the class.
 * The triggered events can be intercepted from the outside, so that another
 * component at a particular event can mount.
 */
abstract class Dispatcher
{
    protected static $events = array();

    /**
     * Using this function it is possible to intercept an event and execute a
     * function when triggered the event.
     * The following information must be given: the name of the event (which is
     * to be intercepted), and the function of which is to be executed when the
     * event was triggered.
     *
     * @param $event
     * @param callable $callback
     */
    public static function on($event, \Closure $callback)
    {
        self::$events[$event][] = $callback;
    }

    /**
     * This function triggers a specific event and can only be triggered by the
     * corresponding class itself.
     * Indicate the name of the event.
     * Optionally, $args are set. These parameters are then at the function
     * passed to intercept the event.
     * $args may be either an array itself from parameters or a single value.
     * This method returns a result, either the return value of the executed
     * function, which was called in triggering the event, or if no return value
     * is supplied, $args.
     *
     * @param $event
     * @param array $args
     *
     * @return mixed
     */
    protected static function call($event, $args = array())
    {
        // if someone expects the event
        if (array_key_exists($event, self::$events)) {
            // perform all functions that have registered for this event
            foreach (self::$events[$event] as $callback) {
                // if is an array then
                if (is_array($args)) {
                    // values of the array are given as individual parameters
                    $newArgs = call_user_func_array($callback, $args);
                } else {
                    // otherwise pass only one parameter which is $args
                    $newArgs = call_user_func($callback, $args);
                }
                // if the function performed has a valid return value - return it
                if (!empty($newArgs)) {
                    $args = $newArgs;
                }
            }
        }

        return $args;
    }
}
