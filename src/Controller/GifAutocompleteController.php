<?php

namespace Drupal\gif_field\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Site\Settings;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\gif_field\Services\GifGenerator;

/**
 * Returns autocomplete responses for countries.
 */
class GifAutocompleteController extends ControllerBase {
  /**
   * Returns response for the gif field autocompletion.
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
   * Include config from admin settings.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface;
   */
  protected $configFactory;

  /**
   * The Messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;
  

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $settings = $container->get('settings');
    $gif_generator = $container->get('gif_field.get_generator');
    $config_factory = $container->get('config.factory');
    $messenger_service = $container->get('messenger');
    return new static(
      $settings,
      $gif_generator,
      $config_factory,
      $messenger_service
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(
    Settings $settings,
    GifGenerator $gif_generator,
    ConfigFactoryInterface $config_factory,
    MessengerInterface $messenger_service
    ) {
    $this->localSettings = $settings;
    $this->gifGenerator = $gif_generator;
    $this->configFactory = $config_factory->get('gif_field.settings');
    $this->messenger = $messenger_service;
  }

  /**
   * {@inheritdoc}
   *
   * Turn {data:{...}, pagination: {...}, meta: {...}} into
   * [['value' => 'label'], ['value' => 'label'], ...]
   */
  public function autocomplete(Request $request) {
    $query = $request->getQueryString();
    $api_key = self::setApiKey();

    if ($api_key === NULL) {

      $this->messenger->addError('No API key found in settings.php or in configuration.');
      return new JsonResponse('');
    }


    $gif_service = 'giphy';
    $key = $this->configFactory->get('api_key');
    \Drupal::logger('my_module')->notice($key);
    if ($query) {
      $gif_matches = $this->gifGenerator->getGif($query, $api_key, $gif_service);
      return new JsonResponse($gif_matches);
    }
  }

  private function setApiKey() {
    $settings_key = $this->localSettings->get('gif_api_key');
    $config_key = $this->configFactory->get('api_key');

    if ($settings_key) {
      return $settings_key;
    }
    else if ($config_key) {
      return $config_key;
    }
    else {
      return NULL;
    }
  }

}
