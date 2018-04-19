<?php
function wake_on_lan($macAddressHexadecimal, $broadcastAddress)
{
  $macAddressHexadecimal = str_replace(':', '', $macAddressHexadecimal);
  // check if $macAddress is a valid mac address
  try {
    if (!ctype_xdigit($macAddressHexadecimal)) {
      throw new \Exception('Mac address invalid, only 0-9 and a-f are allowed');
    }
  } catch (\Exception $e) {
    //
  }
  $macAddressBinary = pack('H12', $macAddressHexadecimal);
  $magicPacket = str_repeat(chr(0xff), 6) . str_repeat($macAddressBinary, 16);
  try {
    if (!$fp = fsockopen('udp://' . $broadcastAddress, 7, $errno, $errstr, 2)) {
      throw new \Exception("Cannot open UDP socket: {$errstr}", $errno);
    }
  } catch (\Exception $e) {
    //
  }
  fputs($fp, $magicPacket);
  fclose($fp);
}

function asset_revision($key)
{
  $prefix = strpos($key, '.css') ? 'css/' : 'js/';
  if (in_array(App::environment(), ['production', 'staging'])) {
    if ($manifest = getJson(base_path() . '/build/assets/rev-manifest.json')) {
      if (property_exists($manifest, $prefix . $key)) {
        return '/dist/' . $manifest->{$prefix . $key};
      }
    }
  }

  //return "/revision/missing-$key";
  return sprintf("/dist/%s%s", $prefix, $key);
}

function cdn_path()
{
  return "https://" . env("CDN_PATH", 'cdn.joejiko.com');
}

function cdn_img_path()
{
  return cdn_path() . "/img";
}

/**
 * @param string $path
 * @return string path to image location
 */
function img_path($path = '')
{

  /**
   * @todo file exists locally?
   */

  if (empty($path)) {
    return cdn_path();
  }

  // remove trailing slash
  if (strrpos('/', $path) === strlen($path)) {
    return cdn_path() . "$path";
  }

  return cdn_path() . "/$path";
}


app()->singleton('Jiko\Shorten\Bitly\Bitly');
function shorten($url)
{
  return app('Jiko\Shorten\Bitly\Bitly')->url($url);
}

function amazon_link($ASIN, $text)
{
  return sprintf('<a href="//www.amazon.com/gp/product/%s/?tag=joji08-20">%s</a>', $ASIN, $text);
}

/**
 * @param $url to request with JSON response.
 * @return array|bool
 */
function getJson($url)
{
  $ctx = stream_context_create([
    'http' => [
      'timeout' => 5
    ]
  ]);
  $contents = @file_get_contents($url, false, $ctx);

  if ($contents === false) {
    $err_message = "file_get_contents failed for url or took longer than 5 seconds.";
    $err_params = [
      "url" => $url
    ];

    Log::warning($err_message, $err_params);

    return false;
  }

  $data = json_decode($contents);
  if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    $err_message = "JSON decode failed on file_get_contents response.";
    $err_params = ["url" => $url, "json_last_error" => json_last_error(), "json_last_error_msg" => json_last_error_msg()];

    Log::warning($err_message, $err_params);

    return false;
  }

  return $data;
}