<?php

namespace StudioEcho\StudioEchoMediaBundle\Lib;

/**
 * Handle file uploads via XMLHttpRequest
 */
class UploadedFileXhr {

  private $logger;

  /**
   * 
   */
  function __construct($logger) {
    $this->logger = $logger;
    $this->logger->info('UploadedFileXhr');
  }

  /**
   * Save the file to the specified path
   * @return boolean TRUE on success
   */
  public function save($path) {
//    $this->logger->info('save');
//    $this->logger->info('$path = ' . print_r($path, true));

    $input = fopen("php://input", "r");
//    $this->logger->info('step 1');
    $temp = tmpfile();
//    $this->logger->info('$temp = ' . print_r($temp, true));
//    $this->logger->info('step 2');
    $realSize = stream_copy_to_stream($input, $temp);
//    $this->logger->info('$realSize = ' . print_r($realSize, true));
//    $this->logger->info('step 3');
    fclose($input);
//    $this->logger->info('step 4');

    if ($realSize != $this->getSize()) {
      return false;
    }

    $target = fopen($path, "w");
    fseek($temp, 0, SEEK_SET);
    stream_copy_to_stream($temp, $target);
    fclose($target);

    return true;
  }

  /**
   * Get the original filename
   * @return string filename
   */
  public function getName() {
    return $_GET['qqfile'];
  }

  /**
   * Get the file size
   * @return integer file-size in byte
   */
  public function getSize() {
    if (isset($_SERVER["CONTENT_LENGTH"])) {
      return (int) $_SERVER["CONTENT_LENGTH"];
    } else {
      throw new Exception('Getting content length is not supported.');
    }
  }

}

?>