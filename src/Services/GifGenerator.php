<?php

namespace Drupal\gif_field\Services;

use GuzzleHttp\ClientInterface;

/**
 * Returns API response from Gif Service.
 */
class GifGenerator {
  /**
   * An http client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * {@inheritdoc}
   */
  public function __construct(ClientInterface $http_client) {
    $this->httpClient = $http_client;
  }

  /**
   * {@inheritdoc}
   */
  public function getGif($query, $api_key, $gif_service) {
    $api_url;
    if ($gif_service === 'giphy') {
      $api_url = 'http://api.giphy.com/v1/gifs/search?' . $query . '&api_key=' . $api_key;
    }
    else {
      return NULL;
    }

    $api_response = self::gifApiCall($api_url);
    $gifs = self::buildGifMatches($api_response);

    return $gifs;

  }

  /**
   * {@inheritdoc}
   */
  private function gifApiCall($url) {
    $api_response = json_decode($this->httpClient->request(
      'GET',
      $url)
      ->getBody());

    return $api_response;
  }

  /**
   * {@inheritdoc}
   */
  private function buildGifMatches($results) {
    $matches = [];
    foreach ($results->data as $key => $result) {
      $matches[] = [
        'value' => $result->images->original->url,
        'label' => $result->slug . ' (' . $result->id . ')',
      ];
    }

    return $matches;
  }

}
