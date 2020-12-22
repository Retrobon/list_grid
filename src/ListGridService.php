<?php

namespace Drupal\list_grid;

use Drupal\Core\TempStore\PrivateTempStoreFactory;


class ListGridService {

  /**
   * @var \Drupal\Core\TempStore\PrivateTempStoreFactory
   */
  private $temp_store_factory;


  /**
   * ListGridService constructor.
   *
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $temp_store_factory
   */
  public function __construct(PrivateTempStoreFactory $temp_store_factory) {

    $this->temp_store_factory = $temp_store_factory->get('grid_list');
  }

  public function data() {
    $config = \Drupal::config('list_grid.listgrid');
    return explode(':', $config->get($this->temp_store_factory->get('grid_list')));

  }
}
