<?php
$jsonData = [
    "message" =>  "We're JSON symbols loaded using iframe POST request"
];
printf('<script>parent.CallbackRegistry[window.name](%s)</script>', json_encode($jsonData));