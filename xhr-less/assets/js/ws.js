(function() {
    document.addEventListener("DOMContentLoaded", () => {

        const elStatus = document.getElementById('status');
        const elSend = document.getElementById('send');

        // Connect to server
        let socket = null;
        let serverUrl = 'ws://127.0.0.1:8000/json';
        if (window.MozWebSocket) {
            socket = new MozWebSocket(serverUrl);
        } else if (window.WebSocket) {
            socket = new WebSocket(serverUrl);
        }
        socket.binaryType = 'blob';

        /**
         * Callen when connected to websocket server.
         * @param {Object} msg
         */
        socket.onopen = (msg) => {
            elStatus.classList.remove('offline');
            elStatus.classList.add('online');
            elStatus.innerText = 'connected';
        };

        /**
         * Called when receiving a message from websocket server.
         * @param {Object} msg
         */
        socket.onmessage = (msg) => {
            try{
                console.log(JSON.parse(msg.data));
            }
            catch (e){
                console.error("Invalid response get from server: ", e);
            }
        };

        /**
         * Called when disconnected from websocket server.
         * @param {Object} msg
         */
        socket.onclose = (msg) => {
            elStatus.classList.remove('online');
            elStatus.classList.add('offline');
            elStatus.innerText = 'disconnected';
        };

        /**
         * Adds event listener to the status indicator.
         */
        elStatus.addEventListener('click', () => {
            socket.close();
        });

        /**
         * Adds event listener to the send button.
         */
        elSend.addEventListener('click', () => {
            let payload = {
                action: 'getJson',
                data: ''
            };
            socket.send(JSON.stringify(payload));
        });
    });
}).call();