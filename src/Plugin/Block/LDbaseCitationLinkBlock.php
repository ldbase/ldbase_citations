<?php

namespace Drupal\ldbase_citations\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Render\Markup;

/**
 * Provides a 'LDbaseCitationLinkBlock' block.
 *
 * @Block(
 *  id = "ldbase_citation_link_block",
 *  admin_label = @Translation("Ldbase citation link block"),
 * )
 */
class LDbaseCitationLinkBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return array(
      '#theme' => 'ldbase_citation_link',
      '#link_text' => $this->t('Get APA Citation'),
    );
  }

}
