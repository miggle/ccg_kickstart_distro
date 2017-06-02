<?php

namespace Drupal\ccg_kickstart\Form;


use Drupal\ccg_kickstart\CCGKickstart;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a form for providing options for which features should be installed.
 * These are packages of configuration revolved around different content types and site functionality.
 */
class FeatureForm extends FormBase {

  /**
   * The Drupal application root.
   *
   * @var string
   */
  protected $root;

  /**
   * The list of features provided by the CCG Kickstart
   * distribution.
   *
   * @var array
   */
  protected $features;

  /**
   * ExtensionSelectForm constructor.
   *
   * @param string $root
   *   The Drupal application root.
   */
  public function __construct($root) {
    $this->root = $root;
    $this->features = CCGKickstart::features();
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
    return 'ccg_kickstart_features';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, array &$install_state = NULL) {
    $form['#title'] = $this->t('Select features');
    $form['help'] = [
      '#type' => 'markup',
      '#markup' => $this->t('Select the features to be installed for your site.'),
    ];
    foreach ($this->features as $name => $feature) {
      // Exclude core from the options as will always be installed.
      if ($name !== 'ccg_kickstart_core') {
        $form['feature_' . $name] = [
          '#type' => 'checkbox',
          '#title' => $feature['name'],
          '#description' => $feature['description'],
          '#default_value' => 1,
        ];
      }
    }
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
    // Add the core feature to the list of features to be installed as the core feature
    // is not optional.
    $install_features = ['ccg_kickstart_core'];
    foreach ($this->features as $name => $feature) {
      if ($form_state->getValue('feature_' . $name)) {
        $install_features[] = $name;
      }
    }
    // Save the features to be installed to the install state.
    $GLOBALS['install_state']['ccg_kickstart']['features'] = $install_features;
  }
}
