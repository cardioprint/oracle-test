if (!!window.EventSource) {
    //https://www.html5rocks.com/en/tutorials/eventsource/basics/
    const source = new EventSource('server/sse.php');
    source.addEventListener('message', function(e) {
        try{
            console.log(JSON.parse(e.data));
        }
        catch (e){
            console.error("Receiving data by SSE failed, server responded invalid data: ", e);
        }
        finally {
            source.close();
        }
    }, false);

    source.addEventListener('open', function(e) {
        // Connection was opened.
    }, false);

    source.addEventListener('error', function(e) {
        if (e.readyState == EventSource.CLOSED) {
            // Connection was closed.
        }
    }, false);
} else {
    //https://caniuse.com/#search=EventSource
    console.warn("Your browser doesn't support Server-Sent events :( Try to eat some ice-cream to stop crying...")
}