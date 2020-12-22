<?php


namespace Drupal\list_grid\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'ListGridBlock' block.
 *
 * @Block(
 *  id = "list_grid_block",
 *  admin_label = @Translation("List Grid block"),
 * )
 */
class ListGridBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['#theme'] = 'list_grid';
    $build['grid_block']['#markup'] = 'Implement GridBlock.';

    return $build;
  }

}
