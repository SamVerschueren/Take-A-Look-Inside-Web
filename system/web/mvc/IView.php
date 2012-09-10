<?php
/**
 * Defines the methods that are required for a view.
 * 
 * @package system.web.mvc
 * @since 2012-06-23
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
interface IView {
    /**
     * Renders the specified view context.
     */
    public function render();
}
?>