<?php

namespace Drupal\ldbase_citations;

/**
 * Interface LDbaseCitationsServiceInterface.
 */
interface LDbaseCitationsServiceInterface {

  public function renderCitationForNode($nid);
  public function getNodeFieldValue($nid, $fieldname);
  public function getNodeAuthors($nid, $fieldname);

}
