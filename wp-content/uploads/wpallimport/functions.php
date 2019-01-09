<?php
function set_timestamp($date, $hours) {
return strtotime($date .' '. $hours . ':00');
}
?>