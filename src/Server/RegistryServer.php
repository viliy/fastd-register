<?php
/**
 * Created by PhpStorm.
 * User: yong
 * Date: 2018/6/15
 * Time: 11:59
 */

namespace Server;

use FastD\Servitization\Server\TCPServer;
use Runner\Validator\Validator;
use Support\Consumer\Broadcast;
use swoole_server;

/**
 * Class RegistryServer
 * @package Server
 */
class RegistryServer extends TCPServer
{
    /**
     * @var RegistryEntity
     */
    protected $entity;

    /**
     * @param swoole_server $server
     * @param $fd
     * @param $fromId
     */
    public function doClose(swoole_server $server, $fd, $fromId)
    {
        if ($this->entity) {
            //服务断开连接，移除注册配置
            registry()->remove($this->entity);

            if ($this->isBroadcast()) {
                $this->broadcastUpdateNode();
            }
        }

        print_r('连接断开' . PHP_EOL);
    }

    /**
     * @param $data
     */
    protected function validate($data)
    {
        $rules = [
            'service_host' => 'required|url',
            'service_name' => 'required|string',
            'service_pid' => 'required|numeric',
        ];

        $validator = new Validator($data, $rules);
        $validator->validate();
    }

    /**
     * 广播到每个代理节点
     */
    protected function broadcastUpdateNode()
    {
        $client = new Broadcast(config()->get('producer_server.host'));
        $client->start();

        print_r('通知广播服务节点更新' . PHP_EOL);
    }

    /**
     * @return bool
     */
    protected function isBroadcast()
    {
        if (config()->has('producer_server.host')) {
            return true;
        }

        return false;
    }
}