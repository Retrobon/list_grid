<?php

namespace Drupal\list_grid\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AlertCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;


class ListGridController extends ControllerBase {

  protected $tempStore;

  /**
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  private $config;

  public function __construct(PrivateTempStoreFactory $temp_store_factory) {
    $this->tempStore = $temp_store_factory->get('grid_list');
    $this->config = \Drupal::config('list_grid.listgrid');
    if (!$this->tempStore->get('grid_list')) {
      $this->tempStore->set('grid_list', $this->config->get('default'));
    }
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('tempstore.private')
    );
  }

  /**
   * @throws \Drupal\Core\TempStore\TempStoreException
   */
  public function grid() {
    $this->tempStore->set('grid_list', 'grid');
    return $this->response('.view-cat');
  }

  /**
   * @throws \Drupal\Core\TempStore\TempStoreException
   */
  public function list() {
    $this->tempStore->set('grid_list', 'list');
    return $this->response('.view-cat');
  }

  /**
   * @param $class
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   */
  public function response($class): AjaxResponse {
    $response = new AjaxResponse();
    $response->addCommand(new InvokeCommand($class, 'trigger', ['RefreshView']));
    return $response;
  }
}
