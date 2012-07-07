<?php
/**
 * It will accept a request and refer it to the right Controller.
 *
 * @package TakeALookInside
 * @copyright (C) 2012 by iRail vzw/asbl
 * @author Sam Verschueren  <sam@irail.be>
 */

require_once('includes/Router.php');

$router = new Router();
$router->handleRoute();
?>