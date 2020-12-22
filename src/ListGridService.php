<?php

namespace Drupal\list_grid;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;


class ListGridService {

  /**
   * @var \Drupal\Core\TempStore\PrivateTempStoreFactory
   */
  private $temp_store_factory;

  /**
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  private $configFactory;

  /**
   * ListGridService constructor.
   *
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $temp_store_factory
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   */
  public function __construct(PrivateTempStoreFactory $temp_store_factory, ConfigFactoryInterface $configFactory) {
    $this->temp_store_factory = $temp_store_factory->get('grid_list');
    $this->configFactory = $configFactory->get('list_grid.listgrid');
  }

  public function data() {
    return [
      explode(':', $this->configFactory->get($this->temp_store_factory->get('grid_list'))),
      $this->configFactory->get()
    ];

  }
}
