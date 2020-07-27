<?php

namespace Drupal\ldbase_citations;

use Drupal\node\Entity\Node;
use Seboettg\CiteProc\StyleSheet;
use Seboettg\CiteProc\CiteProc;

/**
 * Class LDbaseCitationsService.
 */
class LDbaseCitationsService implements LDbaseCitationsServiceInterface {

  /**
   * Constructs a new LDbaseCitationsService object.
   */
  public function __construct() {
  }

  public function renderCitationForNode($nid) {
    $node = Node::load($nid);
    $ctype = $node->bundle();

    switch ($ctype) {
      case 'project':
        $metadata = \Drupal::service('ldbase_citations.render')->renderCitationMetadataForProject($node->id());
        break;
      case 'dataset':
        $metadata = \Drupal::service('ldbase_citations.render')->renderCitationMetadataForDataset($node->id());
        break;
      case 'code':
        $metadata = \Drupal::service('ldbase_citations.render')->renderCitationMetadataForCode($node->id());
        break;
      case 'document':
        $metadata = \Drupal::service('ldbase_citations.render')->renderCitationMetadataForDocument($node->id());
        break;
    }

    $style = StyleSheet::loadStyleSheet("apa");
    $citeproc = new CiteProc($style);
    $citation = $citeproc->render(json_decode($metadata), "bibliography");

    return $citation;
  }

  public function renderCitationMetadataForProject($nid) {
    $node = Node::load($nid);
    return $metadata;
  }

  public function renderCitationMetadataForDataset($nid) {
    $node = Node::load($nid);
    return $metadata; 
  }

  public function renderCitationMetadataForCode($nid) {
    $node = Node::load($nid);
    return $metadata; 
  }

  public function renderCitationMetadataForDocument($nid) {
    $node = Node::load($nid);
    return $metadata; 
  }

}

