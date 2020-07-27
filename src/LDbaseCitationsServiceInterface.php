<?php

namespace Drupal\ldbase_citations;

/**
 * Interface LDbaseCitationsServiceInterface.
 */
interface LDbaseCitationsServiceInterface {

  public function renderCitationForNode($nid);
  public function renderCitationMetadataForProject($nid);
  public function renderCitationMetadataForDataset($nid);
  public function renderCitationMetadataForCode($nid);
  public function renderCitationMetadataForDocument($nid);
  public function getNodeFieldValue($nid, $fieldname);
  public function getNodeAuthors($nid, $fieldname);

}
