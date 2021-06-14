<?php

namespace Lib;

class Response
{

  public function setHeaderCode($headerCode)
  {
    if (!$headerCode) return false;
    http_response_code($headerCode);
  }

  public function redirect($url = null)
  {
    if (!$url) header("Location: /");
    header("Location: " . $url);
    die;
  }

  public function setHeaderContentType($type = null, $charset = 'utf-8')
  {
    if (!$type) return false;

    if ($charset)
      header("Content-Type: {$type}; charset={$charset}");
    else
      header("Content-Type: {$type}");
  }

  public function downloadFile($filePath, $fileName = null)
  {
    if (!$filePath || !file_exists($filePath)) return false;

    if (!$fileName)
      $fileName = basename($filePath);

    /**
     * Specifig header settings
     */
    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary");
    header("Content-disposition: attachment; filename=\"{$fileName}\"");

    /**
     * Finally, readfile and exit
     */
    readfile($filePath);
    die;
  }

  public function disableCORS()
  {
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
      // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
      // you want to allow, and if so:
      header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
      header('Access-Control-Allow-Credentials: true');
      header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

      if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        // may also be using PUT, PATCH, HEAD etc
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

      if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

      exit(0);
    }
  }

  public function html($data, $charset = 'utf-8', $headerCode = 200)
  {
    $this->setHeaderCode($headerCode);
    $this->setHeaderContentType("text/html", $charset);
    echo $data;
    die;
  }

  public function json($data = null, $charset = "utf-8", $headerCode = 200)
  {
    $this->setHeaderCode($headerCode);
    $this->setHeaderContentType("application/json", $charset);
    echo json_encode($data);
    die;
  }
}
