<?php
// src/Service/Chat.php

namespace App\Service;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use App\Repository;
class Chat implements MessageComponentInterface {

    protected $clients;
    protected $rooms;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->rooms = [];
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);

        if (isset($data['action'])) {
            switch ($data['action']) {
                case 'join':
                    $this->joinRoom($from, $data['room']);
                    break;
                case 'message':
                    $this->sendMessage($from, $data['message'], $data['room']);
                    break;
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it from the rooms and clients
        $this->clients->detach($conn);
        $this->removeFromRooms($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    protected function joinRoom(ConnectionInterface $conn, $room) {
        // Remove from previous rooms
        $this->removeFromRooms($conn);

        // Attach to the new room
        $this->rooms[$room][] = $conn->resourceId;

        echo "Connection {$conn->resourceId} joined room {$room}\n";
    }

    protected function removeFromRooms(ConnectionInterface $conn) {
        // Remove connection from all rooms
        foreach ($this->rooms as $room => &$connections) {
            $index = array_search($conn->resourceId, $connections);
            if ($index !== false) {
                unset($connections[$index]);
                echo "Connection {$conn->resourceId} left room {$room}\n";
            }
        }
    }

    protected function sendMessage(ConnectionInterface $from, $message, $room) {
        if (isset($this->rooms[$room])) {
            foreach ($this->rooms[$room] as $clientId) {
                foreach ($this->clients as $client) {
                    if ($client->resourceId == $clientId && $from !== $client) {
                        $client->send($message);
                    }
                }
            }
        }
    }
}
