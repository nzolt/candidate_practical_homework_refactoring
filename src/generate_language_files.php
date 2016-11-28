<?php


chdir(__DIR__);
$home = __DIR__ . '/..';

include('../vendor/autoload.php');

/**
 * define Root path to config (JSON)
 */
$rotPath = '{"system": {
        "paths": {"root": "' . $home . '"}
      }}';
$config = new Language\Core\Config($home . '/etc/config.json',$rotPath);
$logPath =
    $config->get('system.paths.root') .
    $config->get('system.log.file');

$logger = new Language\Core\BufferLog($logPath);
try{
    $languageBatchBo = new \Language\LanguageBatchBo($config, $logger);
    $languageBatchBo->generateLanguageFiles();
    $languageBatchBo->generateAppletLanguageXmlFiles();
    echo "Language files generated successfully.";
} catch (\Exception $e) {
    // Catch and log any uncaught Exception
    echo "Error occurred during the generation of Language files. The error is logged.";
    $logger->error($e);
}

