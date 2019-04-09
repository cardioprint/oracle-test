<?php

declare(strict_types=1);

namespace Bloatless\WebSocket\Application;

use Bloatless\WebSocket\Connection;

class JsonApplication extends Application
{
    /**
     * @var array $clients
     */
    private $clients = [];

    /**
     * Handles new connections to the application.
     *
     * @param Connection $client
     * @return void
     */
    public function onConnect(Connection $client): void
    {
        $id = $client->getClientId();
        $this->clients[$id] = $client;
    }

    /**
     * Handles client disconnects.
     *
     * @param Connection $client
     * @return void
     */
    public function onDisconnect(Connection $client): void
    {
        $id = $client->getClientId();
        unset($this->clients[$id]);
    }

    /**
     * Handles incomming data/requests.
     * If valid action is given the according method will be called.
     *
     * @param string $data
     * @param Connection $client
     * @return void
     */
    public function onData(string $data, Connection $client): void
    {
        try {
            $decodedData = $this->decodeData($data);
            $actionName = 'action' . ucfirst($decodedData['action']);
            if (method_exists($this, $actionName)) {
                call_user_func([$this, $actionName]);
            }
        } catch (\RuntimeException $e) {
            // @todo Handle/Log error
        }
    }

    private function actionGetJson(): void
    {
        $jsonData = json_encode(['message' => "I'm JSON received via websockets"]);
        foreach ($this->clients as $sendto) {
            $sendto->send($jsonData);
        }
    }
}
