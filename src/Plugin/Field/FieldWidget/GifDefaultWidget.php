<?php

namespace Drupal\gif_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Site\Settings;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Render\ElementInfoManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\gif_field\Services\GifGenerator;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;

/**
 * Plugin implementation of the 'GifDefaultWidget' widget.
 *
 * @FieldWidget(
 *   id = "gif_widget",
 *   label = @Translation("Gif Select"),
 *   field_types = {
 *     "Gif"
 *   }
 * )
 */
class GifDefaultWidget extends WidgetBase implements ContainerFactoryPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function __construct(
    $plugin_id,
    $plugin_definition,
    FieldDefinitionInterface $field_definition,
    array $settings,
    array $third_party_settings,
    ElementInfoManagerInterface $element_info,
    Settings $local_settings,
    ConfigFactoryInterface $config_factory,
    EntityTypeManagerInterface $entity_type_manager,
    GifGenerator $gif_generator) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);
    $this->elementInfo = $element_info;
    $this->localSettings = $local_settings;
    $this->configFactory = $config_factory->get('gif_field.settings');
    $this->entityTypeManager = $entity_type_manager;
    $this->gifGenerator = $gif_generator;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $element_info = $container->get('element_info');
    $local_settings = $container->get('settings');
    $config_factory = $container->get('config.factory');
    $entity_type_manager = $container->get('entity_type.manager');
    $gif_generator = $container->get('gif_field.get_generator');
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['third_party_settings'],
      $element_info,
      $local_settings,
      $config_factory,
      $entity_type_manager,
      $gif_generator
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'size' => '60',
      'autocomplete_route_name' => 'gif_field.autocomplete',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $value = isset($items[$delta]->value) ? $items[$delta]->value : '';
    $form_state->set('api_key', $this->gifGenerator->setApiKey());
    $form_state->set('default_description', $this->t('Search for gifs using the field.'));

    if ($form_state->get('api_key') === NULL) {
      $form_state->set('default_description', $this->t('No API key found in settings.php or in configuration.'));
    }

    $element['preview'] = [
      '#type' => 'inline_template',
      '#template' => "<div style='height: 200px;'><img style='max-height: 200px; max' class='gif-preview' src='{{image}}' /></div>",
      '#context' => [
        'image' => $value,
      ],
    ];
    $element['value'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Gif Link'),
      '#description' => $this->t('This field is read only.'),
      '#default_value' => $value,
      '#attributes' => [
        'readonly' => 'readonly'
      ],
    ];
    $element['gif_search'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Gif API Search'),
      '#description' => $form_state->get('default_description'),
      '#ajax' => [
        'callback' => [$this, 'returnGifs'],
        'event' => 'end_typing',
        'wrapper' => 'gif_field_results',
        'progress' => [
          'type' => 'throbber',
          'message' => t('Verifying entry...'),
        ],
      ],
      '#size' => $this->getSetting('size'),
      '#maxlength' => 255,
      '#attached' => [
        'library' => [
          'gif_field/gif-preview',
        ],
      ],
      '#attributes' => [
        'class'      => [
          'delayed-input-submit'
        ],
        'data-delay' => '750',
      ],
    ];
    
    $element['results'] = [
      '#type' => 'inline_template',
      '#template' => "<div id='gif_field_results' class='empty'></div>",
      
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  private function checkForApiKey() {
    $api_key = $this->t('No API key found in settings.php or in configuration.');
    $settings_key = $this->localSettings->get('gif_api_key');
    $config_key = $this->configFactory->get('api_key');

    if ($settings_key || $config_key) {
      $api_key = 'Search for gifs using the field.';
    }

    return $api_key;
  }

  /**
   * {@inheritdoc}
   */
  public function returnGifs(array &$form, FormStateInterface $form_state) {
    https://drupal.stackexchange.com/questions/243317/dependency-injection-for-formelement-process-callback
    // $res = \Drupal::service('gif_field.get_generator')->getGif(
    //   $form_state->getValue('value'),
    //   $form_state->get('api_key'),
    //   'giphy'
    // );
    
    ksm('eijfoiwjf');
    \Drupal::logger('my_module')->notice('werewr');
    $element['results'] = [
      '#type' => 'inline_template',
      '#template' => "<div id='gif_field_results' data-gifs='[{key: object}]'>333</div>",
      
    ];
    $variables['#attached']['drupalSettings']['lotus']['lotusJS']['lotus_height'] = 2;
    $renderer = \Drupal::service('renderer');
    $response = new AjaxResponse();
    $response->addCommand(new ReplaceCommand('#gif_field_results', $renderer->render($element)));
    //$form_state->setRebuild();

    return $response;
  }

}
