<?php

namespace Drupal\gif_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Site\Settings;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Render\Element;
use Drupal\Core\Render\ElementInfoManagerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;


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
    ConfigFactoryInterface $config_factory) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);
    $this->elementInfo = $element_info;
    $this->localSettings = $local_settings;
    $this->configFactory = $config_factory->get('gif_field.settings');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $element_info = $container->get('element_info');
    $local_settings = $container->get('settings');
    $config_factory = $container->get('config.factory');
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['third_party_settings'],
      $element_info,
      $local_settings,
      $config_factory
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

    $element['preview'] = [
      '#type' => 'inline_template',
      '#template' => "<div style='height: 200px;'><img style='max-height: 200px; max' class='gif-preview' src='{{image}}' /></div>",
      '#context' => [
        'image' => $value,
      ],
    ];

    $element['value'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Gif API Search'),
      '#description' => self::checkForApiKey(),
      '#default_value' => $value,
      '#autocomplete_route_name' => $this->getSetting('autocomplete_route_name'),
      '#autocomplete_route_parameters' => [],
      '#size' => $this->getSetting('size'),
      '#maxlength' => 255,
      '#attached' => [
        'library' => [
          'gif_field/gif-preview',
        ],
      ],
    ];

    return $element;
  }

  private function checkForApiKey() {
    $api_key = $this->t('No API key found in settings.php or in configuration.');
    $settings_key = $this->localSettings->get('gif_api_key');
    $config_key = $this->configFactory->get('api_key');

    if ($settings_key || $config_key) {
      $api_key = 'Search for gifs using the field.';
    }

    return $api_key;
  }

}
