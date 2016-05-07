<?php
/**
 * Minecraft Server Status and Player Information Query.
 *
 * Based on https://github.com/xPaw/PHP-Minecraft-Query
 *
 * @author      Ben Christopher Tomlin <ben@tomlin.no>
 * @copyright   Copyright (c) 2016 [bct]productions (http://tomlin.no)
 * @license     The MIT License (MIT)
 * @version     1.1 (May 2016)
 */

namespace bct;

class MCQuery
{
    private $socket;

    /**
     * Gets the current server status data of a Minecraft server
     * @param string $host  the server hostname/ip
     * @param int    $port  the server query port
     * @return mixed        an array of status data, or false on failure
     */
    public function info($host, $port = 25565)
    {
        // Connect to server through socket
        $this->socket = @fsockopen('udp://' . $host, (int)$port, $errno, $error, 1);

        // Check for connection errors
        if ($errno || $this->socket === false)
        {
            if (DEBUG) echo 'Could not create socket: ' . $error;
            return false;
        }

        // Socket settings
        stream_set_timeout($this->socket, 1);
        stream_set_blocking($this->socket, true);

        // Get challenge from server
        if (($challenge = $this->challenge()) === false)
            return false;

        // Run status query and get status data
        if (($status = $this->status($challenge)) === false)
            return false;

        // Close socket
        fclose($this->socket);

        // Return status data
        return $status;
    }

    private function write($command, $append = "")
    {
        $command = pack('c*', 0xFE, 0xFD, $command, 0x01, 0x02, 0x03, 0x04) . $append;
        $length  = strlen($command);

        if ($length !== fwrite($this->socket, $command, $length))
        {
            if (DEBUG) echo 'Failed to write to socket.';
            return false;
        }

        $data = fread($this->socket, 2048);

        if ($data === false)
        {
            if (DEBUG) echo 'Failed to read from socket.';
            return false;
        }

        if (strlen($data) < 5 || $data[0] != $command[2])
        {
            if (DEBUG) echo 'Invalid return data from socket.';
            return false;
        }

        return substr($data, 5);
    }

    private function challenge()
    {
        $data = $this->write(0x09);

        if ($data === false)
        {
            if (DEBUG) echo 'Failed to receive challenge.';
            return false;
        }

        return pack('N', $data);
    }

    private function status($challenge)
    {
        // Get status from server (pad extra 0x00's for additional data)
        $data = $this->write(0x00, $challenge . pack('c*', 0x00, 0x00, 0x00, 0x00));

        if (!$data)
        {
            if (DEBUG) echo 'Failed to receive status.';
            return false;
        }

        $last = '';
        $info = array();

        $data = substr($data, 11);
        $data = explode("\x00\x00\x01player_\x00\x00", $data);

        if (count($data) !== 2)
        {
            if (DEBUG) echo 'Failed to parse server response.';
            return false;
        }

        $players = substr($data[1], 0, -2);
        $data    = explode("\x00", $data[0]);

        // Parse online players
        if ($players)
            $info['players'] = explode("\x00", $players);

        // Array with known keys in order to validate the result
        $keys = array(
            'hostname'   => 'motd',
            'gametype'   => 'type',
            'version'    => 'version',
            'plugins'    => 'plugins',
            'map'        => 'map',
            'numplayers' => 'current',
            'maxplayers' => 'max',
            'hostport'   => 'port',
            'hostip'     => 'host'
        );

        foreach ($data as $key => $value)
        {
            if (~$key & 1)
            {
                if (!array_key_exists($value, $keys ))
                {
                    $last = false;
                    continue;
                }

                $last = $keys[$value];
                $info[$last] = '';
            }
            else if ($last != false)
            {
                $info[$last] = $value;
            }
        }

        // Convert integers
        $info['current'] = intval($info['current']);
        $info['max']     = intval($info['max']);
        $info['port']    = intval($info['port']);

        // Parse plugins, if any
        if ($info['plugins'])
        {
            $data = explode(": ", $info['plugins'], 2);

            $info['rawplugins'] = $info['plugins'];
            $info['software']   = $data[0];

            if (count($data) == 2)
                $info['plugins'] = explode("; ", $data[1]);
        }
        else
            $info['software'] = 'Vanilla';

        // Return server status data
        return $info;
    }
}