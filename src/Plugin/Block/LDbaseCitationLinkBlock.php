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
    $url = \Drupal::request()->getRequestUri();
    $ldbase_object_uuid = \Drupal::service('ldbase.object_service')->isUrlAnLdbaseObjectUrl($url);
    if ($ldbase_object_uuid) {
      $node = \Drupal::service('ldbase.object_service')->getLdbaseObjectFromUuid($ldbase_object_uuid);
      //hide link if content is external rather tan uploaded
      if ($node->bundle() === 'dataset' && $node->get('field_dataset_upload_or_external')->value === 'external') {
        $show_button = FALSE;
      }
      elseif ($node->bundle() === 'document' && $node->get('field_doc_upload_or_external')->value === 'external') {
        $show_button = FALSE;
      }
      elseif ($node->bundle() === 'code' && $node->get('field_code_upload_or_external')->value === 'external') {
        $show_button = FALSE;
      }
      else {
        $show_button = TRUE;
      }

      if ($show_button) {
        return array(
          '#theme' => 'ldbase_citation_link',
          '#link_text' => $this->t('Get Citation'),
        );
      }
      else {
        return [];
      }
    }
  }

}
