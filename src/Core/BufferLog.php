<?php
Namespace Language\Core;

Use Psr\Log\LoggerInterface;
Use Psr\Log\LogLevel;

class BufferLog implements LoggerInterface
{

    private $buffer;
    private $filename;

    public function __construct($filename = null)
    {
        $this->filename = $filename;
        $this->buffer = "";
    }

    public function emergency($message, array $context = array())
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    public function alert($message, array $context = array())
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    public function critical($message, array $context = array())
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    public function error($message, array $context = array())
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    public function warning($message, array $context = array())
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    public function notice($message, array $context = array())
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    public function info($message, array $context = array())
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    public function debug($message, array $context = array())
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    public function log($level, $message, array $context = array(), $timestamp = null)
    {
        if (!$timestamp) $timestamp = \time();

        $message = $this->buildMessage($level, $message, $context, $timestamp);
        $this->appendBuffer($message);

        if (!$this->filename) return;

        file_put_contents($this->filename, $message, FILE_APPEND);
    }
    
    public function getBuffer()
    {
        return $this->buffer;
    }
    
    public function appendBuffer($message)
    {
        $this->buffer .= $message;
    }
    
    public function buildMessage($level, $message, $context, $timestamp)
    {
        return  \gmdate("d/m/Y", $timestamp) .
                " " .
                \gmdate("H:i:s", $timestamp) .
                " " .
                strtoupper($level) .
                " " .
                $this->interpolate($message, $context) .
                PHP_EOL;
    }
    
    /**
    * Interpolates context values into the message placeholders.
    */
    function interpolate($message, array $context = array())
    {
        // build a replacement array with braces around the context keys
        $replace = array();
        foreach ($context as $key => $val) {
            $replace['{' . $key . '}'] = $val;
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }
    
}
