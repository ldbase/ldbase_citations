<?php

namespace Drupal\ldbase_citations\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\Markup;
use Drupal\Node\NodeInterface;
use Drupal\ldbase_citations\LDbaseCitationsService;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CitationController extends ControllerBase {
  /**
   * CitationController constructor
   *
   * @param \Drupal\ldbase_citations\LDbaseCitationsService $citation_service
   */
  public function __construct(LDbaseCitationsService $citation_service) {
    $this->citation_service = $citation_service;

  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('ldbase_citations.render')
    );
  }

  /**
   * Get the citation content
   *
   * @param \Drupal\Node\NodeInterface $node
   */
  public function getCitation(NodeInterface $node) {
    $nid = $node->id();
    $citation = $this->citation_service->renderCitationForNode($nid);

    return [
      '#markup' => Markup::create($citation),
    ];
  }
}
