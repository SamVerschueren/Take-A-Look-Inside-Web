<?php
interface Reader {
    function isValid($parameters);
    function read($parameters);
}
?>