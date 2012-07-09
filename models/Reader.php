<?php
interface Reader {
    function isValid($parameters);
    function execute($sql);
}
?>