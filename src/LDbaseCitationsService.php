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
    $title = $node->getTitle();
    $doi = \Drupal::service('ldbase_citations.render')->getNodeFieldValue($nid, 'field_doi');
    //$year = $node->field_activity_range_select[0]->entity->field_from_year->value;
    $authors = \Drupal::service('ldbase_citations.render')->getNodeAuthors($nid, 'field_related_persons');

    $metadata = array(
      array(
        "title" => $title, 
        "author" => $authors,
        "DOI" => $doi,
        "archive" => "LDbase",
        /*
        "issued" => array(
          "date-parts" => array(array($year)),
        )
        */
      )
    );
    return json_encode($metadata);
  }

  public function renderCitationMetadataForDataset($nid) {
    $node = Node::load($nid);
    $title = $node->getTitle();
    $doi = \Drupal::service('ldbase_citations.render')->getNodeFieldValue($nid, 'field_doi');
    //$year = $node->field_data_collection_range[0]->entity->field_from_year->value;
    $authors = \Drupal::service('ldbase_citations.render')->getNodeAuthors($nid, 'field_related_persons');

    $metadata = array(
      array(
        "title" => $title, 
        "author" => $authors,
        "DOI" => $doi,
        "archive" => "LDbase",
        /*
        "issued" => array(
          "date-parts" => array(array($year)),
        )
        */
      )
    );
    return json_encode($metadata);
  }

  public function renderCitationMetadataForCode($nid) {
    $node = Node::load($nid);
    $title = $node->getTitle();
    $doi = \Drupal::service('ldbase_citations.render')->getNodeFieldValue($nid, 'field_doi');
    $authors = \Drupal::service('ldbase_citations.render')->getNodeAuthors($nid, 'field_related_persons');

    $metadata = array(
      array(
        "title" => $title, 
        "author" => $authors,
        "DOI" => $doi,
        "archive" => "LDbase",
      )
    );
    return json_encode($metadata);
  }

  public function renderCitationMetadataForDocument($nid) {
    $node = Node::load($nid);
    $title = $node->getTitle();
    $doi = \Drupal::service('ldbase_citations.render')->getNodeFieldValue($nid, 'field_doi');
    $authors = \Drupal::service('ldbase_citations.render')->getNodeAuthors($nid, 'field_related_persons');

    $metadata = array(
      array(
        "title" => $title, 
        "author" => $authors,
        "DOI" => $doi,
        "archive" => "LDbase",
      )
    );
    return json_encode($metadata);
  }

  public function getNodeFieldValue($nid, $fieldname) {
    $node = Node::load($nid);
    $value_array = $node->get($fieldname)->getValue();
    return $value_array[0]['value'];
  }

  public function getNodeAuthors($nid, $fieldname) {
    $authors = array();
    $node = Node::load($nid);
    $authors_raw = $node->get($fieldname)->getValue();
    foreach ($authors_raw as $author) {
      $author_nid = $author['target_id'];
      $author_node = Node::load($author_nid);
      $author_name = $author_node->getTitle();
      $author_name_exploded = explode(' ', $author_name);
      $converted_length = count($author_name_exploded) - 1;
      $family = array_slice($author_name_exploded, $converted_length);
      $given = ( $converted_length > 1 ? array(implode(' ', array_slice($author_name_exploded, 0, 2))) : array_slice($author_name_exploded, 0, 1) ); // This is a crime
      $authors[] = array('family' => implode('', $family), 'given' => implode('', $given));
    }
    return $authors; 
  }
}

