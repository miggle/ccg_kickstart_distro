<?php

namespace Drupal\ccg_choice_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides the CCG Choices widget block.
 *
 * @Block(
 *   id = "ccg_choice_widget_block",
 *   admin_label = @Translation("NHS choices widget"),
 * )
 */
class CCGChoiceWidgetBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return array(
      '#theme' => 'ccg_choice_widget',
      '#attached' => array(
        'library' =>  array(
          'ccg_choice_widget/nhs_choices_global',
        ),
      ),
    );
  }
}
