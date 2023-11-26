<?php
interface LogFormat
{
    public function format($string);
}

interface LogDelivery
{
    public function deliver($format);
}
class RawFormat implements LogFormat
{
    public function format($string)
    {
        return $string;
    }
}

class DateFormat implements LogFormat
{
    public function format($string)
    {
        return date('Y-m-d H:i:s') . $string;
    }
}

class DetailsDateFormat implements LogFormat
{
    public function format($string)
    {
        return date('Y-m-d H:i:s') . $string . ' - With some details';
    }
}
class EmailDelivery implements LogDelivery
{
    public function deliver($format)
    {
        echo "Output format ({$format}) via email";
    }
}

class SmsDelivery implements LogDelivery
{
    public function deliver($format)
    {
        echo "Output format ({$format}) via SMS";
    }
}

class ConsoleDelivery implements LogDelivery
{
    public function deliver($format)
    {
        echo "Output format ({$format}) to console";
    }
}
class Logger
{
    private $format;
    private $delivery;

    public function __construct(LogFormat $format, LogDelivery $delivery)
    {
        $this->format   = $format;
        $this->delivery = $delivery;
    }

    public function log($string)
    {
        $formattedString = $this->format->format($string);
        $this->deliver($formattedString);
    }

    private function deliver($format)
    {
        $this->delivery->deliver($format);
    }
}
$logger = new Logger(new RawFormat(), new SmsDelivery());
$logger->log('Emergency error! Please fix me!');
