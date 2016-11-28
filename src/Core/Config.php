<?php

Namespace Language\Core;

use Peekmo\JsonPath\JsonPath;

class Config
{
    /**
     * @var $data string
     */
    var $data;

    /**
     * @var $arg mixed
     */
    protected $arg;

    /**
     * Config constructor.
     * @param $arg mixed
     * @param $ext string
     */
    public function __construct($arg, $ext = '')
    {
        $this->arg = $arg;

        if (is_array($this->arg))
        {
            $this->set($this->arg);
        }
        else if (isset($this->arg))
        {
            if (file_exists($this->arg) && is_file($this->arg))
            {
                $this->set(json_decode(file_get_contents($this->arg),true));
            }
        }

        if($ext)
        {
            $this->add($ext);
        }
    }

    /**
     * @param $path string
     */
    public function add($path)
    {
        $newPath = json_decode($path,true);
        $filePath = json_decode(file_get_contents($this->arg),true);
        $this->set(array_merge_recursive($filePath, $newPath));
    }

    /**
     * @param $arr
     */
    protected function set($arr)
    {
        $this->data = $arr;
    }

    /**
     * @param $path string
     * @return mixed
     */
    public function get($path)
    {
        $jpath = new JsonPath();
        $value = $jpath->jsonPath($this->data, $path);
        return $value[0];
    }

}
