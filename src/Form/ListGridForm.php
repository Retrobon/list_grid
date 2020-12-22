<?php

namespace Drupal\list_grid\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Views;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ListGridForm.
 */
class ListGridForm extends ConfigFormBase {

  /**
   * Drupal\Core\Entity\EntityManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->entityManager = $container->get('entity.manager');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'list_grid.listgrid',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'list_grid_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $views_on_site = Views::getViewsAsOptions();

    $config = $this->config('list_grid.listgrid');
    $form['default'] = [
      '#type' => 'select',
      '#title' => $this->t('default'),
      '#options' => ['grid' => 'grid', 'list' => 'list'],
      '#default_value' => $config->get('views'),
    ];

    $form['views_grid'] = [
      '#type' => 'select',
      '#title' => $this->t('grid'),
      '#options' => $views_on_site,
      '#default_value' => $config->get('views'),
    ];

    $form['views_list'] = [
      '#type' => 'select',
      '#title' => $this->t('list'),
      '#options' => $views_on_site,
      '#default_value' => $config->get('views'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('list_grid.listgrid')
      ->set('default', $form_state->getValue('default'))
      ->set('grid', $form_state->getValue('views_grid'))
      ->set('list', $form_state->getValue('views_list'))
      ->save();
  }

}
