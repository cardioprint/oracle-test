<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
/**
 * Constructs the SSE data format and flushes that data to the client.
 *
 * @param string $id Timestamp/id of this connection.
 * @param string $msg Line of text that should be transmitted.
 */
function sendMsg($id , $msg) {
    echo "id: $id" . PHP_EOL;
    echo "data: {\n";
    echo "data: \"message\": \"$msg\"\n";
    echo "data: }\n";
    echo PHP_EOL;
    ob_flush();
    flush();
}
$startedAt = time();
sendMsg($startedAt , "You're reading JSON sent by server by means of Server-Sent Events");