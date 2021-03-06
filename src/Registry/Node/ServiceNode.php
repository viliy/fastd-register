<?php
/**
 * Created by PhpStorm.
 * User: yong
 * Date: 2018/6/15
 * Time: 14:28
 */

namespace Registry\Node;

use Registry\Contracts\NodeAbstract;

/**
 * Class ServiceNode
 * @package Registry
 */
class ServiceNode extends NodeAbstract
{
    /**
     * The node unique hash.
     *
     * @var string
     */
    protected $hash;

    /**
     * ServiceNode constructor.
     * @param array $input
     * @param int $flags
     * @param string $iterator_class
     */
    public function __construct($input = array(), $flags = 0, $iterator_class = "ArrayIterator")
    {
        parent::__construct($input, $flags, $iterator_class);

        $info = parse_url($this->host());

        if (isset($info['port']) && !$this->has('service_port')) {
            $this->set('service_port', $info['port']);
        }

        $this->hash = md5($this->host() . ':' . $this->port());
    }

    /**
     * @return string
     */
    public function hash()
    {
        return $this->hash;
    }

    /**
     * @return string
     */
    public function service()
    {
        return $this->get('service_name');
    }

    /**
     * @return string
     */
    public function pid()
    {
        return $this->get('pid');
    }

    /**
     * @return string
     */
    public function host()
    {
        return $this->get('service_host');
    }

    /**
     * @return string
     */
    public function port()
    {
        return $this->get('service_port');
    }

    /**
     * @return string
     */
    public function extra()
    {
        return $this->get('extra');
    }

    public function toJson()
    {
        return json_encode($this->getArrayCopy());
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->getArrayCopy();
    }
}
