<?php

namespace Drupal\giphy_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'GiphyDefaultFormatter' formatter.
 *
 * @FieldFormatter(
 *   id = "giphy_formatter",
 *   module = "giphy_field",
 *   label = @Translation("Giphy Field"),
 *   field_types = {
 *     "giphy"
 *   }
 * )
 */
class GiphyDefaultFormatter extends FormatterBase {

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
