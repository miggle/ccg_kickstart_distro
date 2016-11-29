<?php

namespace Drupal\ccg_kickstart\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a form for determining whether default content should be created or not.
 */
class DefaultContentForm extends FormBase {

  /**
   * The Drupal application root.
   *
   * @var string
   */
  protected $root;

  /**
   * DefaultContentForm constructor.
   *
   * @param string $root
   *   The Drupal application root.
   */
  public function __construct($root) {
    $this->root = $root;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('app.root')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ccg_kickstart_default_content';
  }
  
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, array &$install_state = NULL) {
    // Retrieve the map of features to default content modules.
    // Retrieve the enabled set of features.
    $form['#title'] = $this->t('Create default content');
    $form['default_content'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Check this box to create default content for your new site install.'),
      '#default_value' => 1,
    ];
    $form['actions'] = [
      'continue' => [
        '#type' => 'submit',
        '#value' => $this->t('Continue'),
      ],
      '#type' => 'actions',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    if ($form_state->getValue('default_content')) {
      $GLOBALS['install_state']['ccg_kickstart']['default_content_tasks'] = [
        'module:ccg_default_content',
        'module:ccg_second_level_menu_links',
        'module:ccg_third_level_menu_links',
      ];
    }
  }
}
