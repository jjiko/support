<?php
function asset_revision_css($manifest, $key)
{
  if (property_exists($manifest, $key)) {
    return '/css/' . $manifest->{$key};
  }
}

function asset_revision($key)
{
  if ($manifest = getJson(base_path() . '/build/assets/rev-manifest.json')) {
    if (strpos($key, '.css')) {
      return asset_revision_css($manifest, $key);
    }
  }

  return "/revision/missing-$key";
}

function cdn_path()
{
  return "https://".env("CDN_PATH", 'cdn.joejiko.com');
}

function cdn_img_path()
{
  return cdn_path()."/img";
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

function shorten($url)
{
  return Jiko\Shorten\Bitly\Bitly::url($url);
}

function amazon_link($ASIN, $text) {
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

  if($contents === false) {
    $err_message = "file_get_contents failed for url or took longer than 5 seconds.";
    $err_params = [
      "url" => $url
    ];

    Log::warning($err_message, $err_params);

    return false;
  }

  $data = json_decode($contents);
  if($data === null && json_last_error() !== JSON_ERROR_NONE) {
    $err_message = "JSON decode failed on file_get_contents response.";
    $err_params = ["url" => $url, "json_last_error" => json_last_error(), "json_last_error_msg" => json_last_error_msg()];

    Log::warning($err_message, $err_params);

    return false;
  }

  return $data;
}