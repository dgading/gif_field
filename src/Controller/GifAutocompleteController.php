<?php

namespace Drupal\gif_field\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Site\Settings;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\gif_field\Services\GifGenerator;

/**
 * Returns autocomplete responses for countries.
 */
class GifAutocompleteController extends ControllerBase {
  /**
   * Returns response for the giphy field autocompletion.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The current request object containing the search string.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   A JSON response containing the autocomplete suggestions for countries.
   */

  /**
   * GifGenerator.
   *
   * @var \Drupal\gif_field\Services\GifGenerator
   */
  protected $gifGenerator;

  /**
   * Config from settings.php.
   *
   * @var \Drupal\Core\Site\Settings
   */
  protected $localSettings;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $settings = $container->get('settings');
    $gif_generator = $container->get('giphy_field.get_generator');
    return new static($settings, $gif_generator);
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(Settings $settings, GifGenerator $gif_generator) {
    $this->localSettings = $settings;
    $this->gifGenerator = $gif_generator;
  }

  /**
   * {@inheritdoc}
   *
   * Turn {data:{...}, pagination: {...}, meta: {...}} into
   * [['value' => 'label'], ['value' => 'label'], ...]
   */
  public function autocomplete(Request $request) {
    $query = $request->getQueryString();
    $api_key = $this->localSettings->get('gif_api_key');
    $gif_service = 'giphy';
    if ($query) {
      $gif_matches = $this->gifGenerator->getGif($query, $api_key, $gif_service);
      return new JsonResponse($gif_matches);
    }
  }

}
