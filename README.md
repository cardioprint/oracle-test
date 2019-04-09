# oracle-test

# AJAX without XMLHTTPRequest
This prototype show 5 ways to obtain JSON data from server without using XHR:
 - loading *.js resource;
 - using *fetch* API;
 - using *Server-Sent Events*;
 - via WebSockets;
 - using iframe.

Please launch following commands to enable WebSockets based AJAX:
`cd xhr-less\server\;`
`php run-ws-server.php`

Also, it is not supplied with jsdoc and phpdoc comments.

# ENORMOUS XML
This script uses native XML Parser (https://www.php.net/manual/en/book.xml.php). 
It reads input xml file chunk by chunk to save memory and filters items.
Though, not all requirements are completed: no writing to file and launching from command line.