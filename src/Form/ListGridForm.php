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
      '#default_value' => $config->get('grid'),
      "#empty_option" => t('- Select -'),
    ];

    $form['views_list'] = [
      '#type' => 'select',
      '#title' => $this->t('list'),
      '#options' => $views_on_site,
      '#default_value' => $config->get('list'),
      '#states' => [
        'invisible' => [
          ':input[name="views_grid"]' => ['value' => ''],
        ],
      ],
    ];
//https://gist.github.com/leymannx/72d41cf0baa4dee62d6ddc89bc7c7a5a
    $set_id = $form_state->get('set_id');
    if ($set_id === NULL) {
      $set_id = $form_state->set('set_id', 0);
      $set_id = 0;
    }

    $form['setIds'] = [
      '#type' => 'fieldset',
      '#prefix' => '<div id="set-id-wrapper">',
      '#suffix' => '</div>',
    ];


    $form['setIds']['actions']['add_set'] = [
      '#type' => 'submit',
      '#limit_validation_errors' => [],
      '#value' => t('add set views'),
      '#submit' => ['::addOneSet'],
      '#ajax' => [
        'callback' => '::addmoreCallbackSet',
        'wrapper' => 'set-id-wrapper',
      ],
      '#attributes' => ['class' => ['btn-transparent']],
    ];


    $form['setIds']['actions']['remove_set'] = [
      '#type' => 'submit',
      '#limit_validation_errors' => [],
      '#value' => t('delete set views'),
      '#submit' => ['::removeoneSet'],
      '#ajax' => [
        'callback' => '::addmoreCallbackSet',
        'wrapper' => 'set-id-wrapper',
      ],
      '#name' => t('setremove'),
      '#attributes' => ['class' => ['btn-transparent']],
      '#suffix' => t('<div class="id-wrapper"></div>'),
    ];

    for ($i = 0; $i < $set_id; $i++) {
      $form['setIds']['views_grid_' . $i] = [
        '#type' => 'select',
        '#title' => $this->t('grid'),
        '#options' => $views_on_site,
        '#default_value' => $config->get('grid_' . $i),
        "#empty_option" => t('- Select -'),
      ];

      $form['setIds']['views_list_' . $i] = [
        '#type' => 'select',
        '#title' => $this->t('list'),
        '#options' => $views_on_site,
        '#default_value' => $config->get('list_' . $i),
        "#empty_option" => t('- Select -'),
      ];
    }


    return parent::buildForm($form, $form_state);
  }

  public function addmoreCallbackSet(array &$form, FormStateInterface $form_state) {
    $set_id = $form_state->get('set_id');
    return $form['setIds'];
  }

  public function addOneSet(array &$form, FormStateInterface $form_state) {
    $set_id = $form_state->get('set_id');
    $add_button = $set_id + 1;
    $form_state->set('set_id', $add_button);
    $form_state->setRebuild();
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $count = 0;
    $views = [];
    foreach ($form_state->getValues() as $key => $value) {
      if (strpos ( $key , 'views_grid_') === 0){
        $views[$count]['grid'] = $value;
      }
      if (strpos ( $key , 'views_list_') === 0){
        $views[$count]['list'] = $value;
        $count++;
      }
    }

    $this->config('list_grid.listgrid')
      ->set('default', $form_state->getValue('default'))
      ->set('views', $views)
      ->set('grid', $form_state->getValue('views_grid'))
      ->set('list', $form_state->getValue('views_list'))
      ->save();
  }

}
