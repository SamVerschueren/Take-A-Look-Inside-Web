<?php
/**
 * Interface that tells which methods that should be implemented.
 *
 * @package TakeALookInside
 * @author Sam Verschueren  <sam@irail.be>
 */
interface IController {
    function post($parameters);
    function get($parameters);
    function put($parameters);
    function delete($parameters);
}
?>