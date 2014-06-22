<?php
require_once dirname(__DIR__).'/vendor/autoload.php';
use Fluent\Logger\FluentLogger;
use Kassner\LogParser\LogParser;
use Fluent\Logger\Entity;
Fluent\Autoloader::register();

$logger = new FluentLogger("localhost","24224");
$files = array('access.log', 'error.log', 'fpm-error.log');
$log_path = '/var/docroot/drupal-logs';
$parser = new LogParser("%h %l %u %t \"%r\" %>s %O \"%{Referer}i\" \"%{User-Agent}i\"(?P<extra>(.*))");
$parser = new LogParser("%h %l %u %t \"%r\" %>s %O \"%{Referer}i\" \"%{User-Agent}i\" vhost=(?P<vhost>.*?) host=(?P<host_extra>.*?) hosting_site=(?P<hosting_site>.*?) pid=(?P<pid>.*?) request_time=(?P<request_processing>.*?)");
foreach ($files as $file) {
  $contents = file_get_contents($log_path . '/'. $file);
  $lines = explode(PHP_EOL, $contents);
  foreach ($lines as $line) {
    try{
      $details = $parser->parse($line);
      $request = $details->request;
      $parts = explode(' ', $request);
      $method = $parts[0];
      $path = $parts[1];
      $protocol = $parts[2];
      $time = strtotime($details->time);
      $record = array(
        'method' => $method,
        'path' => $path,
        'host' => $details->host,
        'hosting_site' => $details->hosting_site,
        'status' => $details->status,
        'sent_bytes' => $details->sentBytes,
        'referer' => $details->HeaderReferer,
        'user_agent' => $details->HeaderUserAgent,
        'execution_time' => $details->request_processing
      );
      $entity = new Entity('apache.access', $record, $time);
      $logger->post2($entity);
    } 
    catch(Exception $e){

    }
  }
}
