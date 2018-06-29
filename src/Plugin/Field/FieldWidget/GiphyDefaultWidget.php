<?php

namespace Drupal\giphy_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'GiphyDefaultWidget' widget.
 *
 * @FieldWidget(
 *   id = "giphy_widget",
 *   label = @Translation("Giphy Select"),
 *   field_types = {
 *     "Giphy"
 *   }
 * )
 */
class GiphyDefaultWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'size' => '60',
      'autocomplete_route_name' => 'giphy.autocomplete',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $value = isset($items[$delta]->value) ? $items[$delta]->value : '';

    $element['preview'] = [
      '#type' => 'inline_template',
      '#template' => "<div style='height: 200px;'><img style='max-height: 200px; max' class='giphy-preview' src='{{image}}' /></div>",
      '#context' => [
        'image' => $value,
      ],
    ];

    $element['value'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Giphy API Search'),
      '#default_value' => $value,
      '#autocomplete_route_name' => $this->getSetting('autocomplete_route_name'),
      '#autocomplete_route_parameters' => [],
      '#size' => $this->getSetting('size'),
      '#maxlength' => 255,
      '#attached' => [
        'library' => [
          'giphy_field/giphy-preview',
        ],
      ],
    ];

    return $element;
  }

}
