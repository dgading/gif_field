<?php

namespace Drupal\giphy_field\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Site\Settings;

/**
 * Returns autocomplete responses for countries.
 */
class GiphyAutocompleteController {

  /**
   * Returns response for the country name autocompletion.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The current request object containing the search string.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   A JSON response containing the autocomplete suggestions for countries.
   */
  public function autocomplete(Request $request) {
    $matches = [];
    $string = $request->query->get('q');
    if ($string) {
      $results = self::getGiphyResults($string);

      foreach ($results->data as $key => $result) {
        $matches[] = [
          'value' => $result->images->original->url,
          'label' => $result->slug . ' (' . $result->id . ')',
        ];
      }
    }
    return new JsonResponse($matches);
  }

  /**
   * {@inheritdoc}
   */
  public function getGiphyResults($search_term) {
    $client = \Drupal::httpClient();
    $res = json_decode($client->request(
      'GET',
      'http://api.giphy.com/v1/gifs/search?q=' . $search_term . '&api_key=' . Settings::get('giphy_api_key'))
      ->getBody()
      ->getContents()
    );
    return $res;
  }

}
