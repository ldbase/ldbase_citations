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
    $title = $node->getTitle();
    $doi = \Drupal::service('ldbase_citations.render')->getNodeFieldValue($nid, 'field_doi');
    if ($doi) {
      $url = "https://doi.org/{$doi}";
    }
    else {
      global $base_url;
      $path = \Drupal\Core\Url::fromRoute('entity.node.canonical', ['node' => $node->id()])->toString();
      $url = "{$base_url}{$path}";
    }
    $authors = \Drupal::service('ldbase_citations.render')->getNodeAuthors($nid, 'field_related_persons');
    $year = \Drupal::service('date.formatter')->format($node->getCreatedTime(), 'html_year');
    $current_day = date('d', time());
    $current_month = date('m', time());
    $current_year = date('Y', time());

    $metadata = array(
      array(
        "title" => $title,
        "author" => $authors,
        "archive" => "LDbase",
        "accessed" => array(
          "date-parts" => array(array($current_year, $current_month, $current_day)),
        ),
        "retrieved" => "{$current_year}-{$current_month}-{$current_day}",
        "URL" => $url,
        "issued" => array(
          "date-parts" => array(array($year)),
        ),
        "type" => 'webpage',
      )
    );

    $style = StyleSheet::loadStyleSheet("apa");
    $citeproc = new CiteProc($style);
    $citation = $citeproc->render(json_decode(json_encode($metadata)), "bibliography");

    return $citation;
  }

  public function getNodeFieldValue($nid, $fieldname) {
    $node = Node::load($nid);
    $value_array = $node->get($fieldname)->getValue();
    return !empty($value_array) ? $value_array[0]['value'] : NULL;
  }

  public function getNodeAuthors($nid, $fieldname) {
    $authors = array();
    $node = Node::load($nid);
    $authors_raw = $node->get($fieldname)->getValue();
    foreach ($authors_raw as $author) {
      $family = array();
      $given = array();
      $author_nid = $author['target_id'];
      $author_node = Node::load($author_nid);
      $author_first_name = trim($author_node->field_first_name->value ?? '');
      $author_last_name = trim($author_node->field_last_name->value ?? '');
      // Use Full Name if it exists
      if (!empty($author_first_name) && !empty($author_last_name)) {
        $family[] = $author_last_name;
        $given[] = $author_first_name;
        if (!empty($author_middle_name = trim($author_node->field_middle_name->value ?? ''))) {
          array_push($given, ' '.$author_middle_name);
        }
      }
      else {
        $author_name = trim($author_node->getTitle());
        $author_name_exploded = explode(' ', $author_name);
        $converted_length = count($author_name_exploded) - 1;
        $family = array_slice($author_name_exploded, $converted_length);
        $given = ( $converted_length > 1 ? array(implode(' ', array_slice($author_name_exploded, 0, 2))) : array_slice($author_name_exploded, 0, 1) ); // This is a crime
      }

      $authors[] = array('family' => implode('', $family), 'given' => implode('', $given));
    }
    return $authors;
  }
}

