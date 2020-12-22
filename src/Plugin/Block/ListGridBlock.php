<?php


namespace Drupal\list_grid\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;

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
    $build['#content']['links'][] = [
      '#type' => 'link',
      '#title' => 'grid',
      '#url' => Url::fromRoute('list_grid.grid'),
      '#attributes' => [
        'class' => ['use-ajax', 'grid']
      ]
    ];
    $build['#content']['links'][] = [
      '#type' => 'link',
      '#title' => 'list',
      '#url' => Url::fromRoute('list_grid.row'),
      '#attributes' => [
        'class' => ['use-ajax', 'list']
      ]
    ];
    return $build;
  }

}

