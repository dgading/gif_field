<?php

namespace Drupal\gif_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'GifDefaultFormatter' formatter.
 *
 * @FieldFormatter(
 *   id = "gif_formatter",
 *   module = "gif_field",
 *   label = @Translation("Gif Field"),
 *   field_types = {
 *     "gif"
 *   }
 * )
 */
class GifDefaultFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = $this->t('Displays the random string.');
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {
      // Render each element as markup.
      $element[$delta] = ['#markup' => '<img src="' . $item->value . '" alt="" />'];
    }

    return $element;
  }

}
