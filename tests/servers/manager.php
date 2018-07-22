<?php
/**
 * Author: Twosee <twose@qq.com>
 * Date: 2018/7/22 下午5:53
 */

function get_one_free_port()
{
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    $ok = socket_bind($socket, "0.0.0.0", 0);
    if (!$ok) {
        return false;
    }
    $ok = socket_listen($socket);
    if (!$ok) {
        return false;
    }
    $ok = socket_getsockname($socket, $addr, $port);
    if (!$ok) {
        return false;
    }
    socket_close($socket);
    return $port;
}

return (function () {
    return [
        'websocket' => (function () {
            $ip = '127.0.0.1';
            $port = get_one_free_port();
            $process = new \swoole_process(function (\swoole_process $process) use ($ip, $port) {
                $process->exec(PHP_BINARY, [__DIR__ . '/websocket.php', $ip, $port]);
            }, '/dev/null');
            $pid = $process->start();

            return [
                'ip' => $ip,
                'port' => $port,
                'pid' => $pid
            ];
        })()
    ];
})();
