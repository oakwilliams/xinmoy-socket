# Xinmoy Socket
Distributed Socket Framework Based on Swoole
## Features
* It supports MySQL master/slave architecture.
* It supports Redis master/slave architecture.
* It supports CURL.
## Xinmoy Socket App
![](https://github.com/oakwilliams/xinmoy-socket/wiki/Xinmoy%20Socket%20App.jpg)
## App\Server
```
<?php
namespace App;


use Xinmoy\Server\Server as XinmoyServer;


class Server extends XinmoyServer {
    public function onTest($server, $fd, $reactor_id, $data) {
        $this->send($fd, 'test');
    }
}
```
## Xinmoy Socket Protocol
![](https://github.com/oakwilliams/xinmoy-socket/wiki/Xinmoy%20Socket%20Protocol.jpg)
## Test
```
{
    "type": "test",
    "data": null
}
```
## Documentation
For more information, please visit [Wiki](https://github.com/oakwilliams/xinmoy-socket/wiki).
