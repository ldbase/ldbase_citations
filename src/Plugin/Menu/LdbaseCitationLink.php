<?php

namespace Drupal\ldbase_citations\Plugin\Menu;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Menu\MenuLinkDefault;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Menu\StaticMenuLinkOverridesInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\ldbase_content\LDbaseObjectService;

/**
 * Creates link to get citation information
 */
class LdbaseCitationLink extends MenuLinkDefault {

  /**
   * The route match service.
   *
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  protected $routeMatch;

  /**
   * The current user.
   *
   * @var \Drupal\ldbase_content\LDbaseObjectService
   */
  protected $ldbaseObject;

  /**
   * Constructs a new Get Citation Link.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Menu\StaticMenuLinkOverridesInterface $static_override
   *   The static override storage.
   * @param \Drupal\Core\Routing\CurrentRouteMatch $route_match
   *   The route match service.
   * @param \Drupal\ldbase_content\LDbaseObjectService $ldbase_object
   *   The LDbase object service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, StaticMenuLinkOverridesInterface $static_override, CurrentRouteMatch $route_match, LDbaseObjectService $ldbase_object) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $static_override);
    $this->routeMatch = $route_match;
    $this->ldbaseObject = $ldbase_object;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('menu_link.static.overrides'),
      $container->get('current_route_match'),
      $container->get('ldbase.object_service')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getTitle() {
    return 'Get Citation';
  }

  public function getRouteName() {
    return 'ldbase_citations.display_citation';
  }

  public function getRouteParameters() {
    $node = $this->routeMatch->getParameter('node');
    $ldbase_uuid = !(empty($node)) ? $node->uuid() : 'not_a_node';

    return ['node' => $ldbase_uuid];
  }

  /**
   * {@inheritdoc}
   */
  public function getOptions() {
    $options = parent::getOptions();
    $options['attributes']['class'] = ['use-ajax', 'citation-modal-icon'];
    $options['attributes']['data-dialog-type'] = 'modal';
    $options['attributes']['data-dialog-options'] = Json::encode(['width' => '800', 'title' => 'Citation']);

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function isEnabled() {
    $node = $this->routeMatch->getParameter('node');
    $url = \Drupal::request()->getRequestUri();
    $ldbase_object_uuid = $this->ldbaseObject->isUrlAnLdbaseObjectUrl($url);
    $show_button = FALSE;
    if ($ldbase_object_uuid) {
      $node = $this->ldbaseObject->getLdbaseObjectFromUuid($ldbase_object_uuid);
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
    }
    return $show_button;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    return Cache::mergeContexts(parent::getCacheContexts(), array('user'));
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }
}
