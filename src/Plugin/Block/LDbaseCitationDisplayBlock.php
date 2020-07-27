<?php

namespace Drupal\ldbase_citations\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Render\Markup;

/**
 * Provides a 'LDbaseCitationDisplayBlock' block.
 *
 * @Block(
 *  id = "ldbase_citation_display_block",
 *  admin_label = @Translation("Ldbase citation display block"),
 * )
 */
class LDbaseCitationDisplayBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $url = \Drupal::request()->getRequestUri();
    $ldbase_object_uuid = \Drupal::service('ldbase.object_service')->isUrlAnLdbaseObjectUrl($url);
    if ($ldbase_object_uuid) {
      $node = \Drupal::service('ldbase.object_service')->getLdbaseObjectFromUuid($ldbase_object_uuid);
      $citable_ctypes = array('project', 'dataset', 'code', 'document');
      if (in_array($node->bundle(), $citable_ctypes)){
        $citation = \Drupal::service('ldbase_citations.render')->renderCitationForNode($node->id());
      }
      else {
        $citation = NULL;
      }
    }
    else {
      $citation = NULL;
    }

    return [
      '#markup' => Markup::create($citation),
    ];

  }

}
