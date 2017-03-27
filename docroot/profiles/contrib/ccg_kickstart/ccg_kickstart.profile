<?php

/**
 * @file
 * Defines the CCG Kickstart Profile install screen by modifying the install form.
 */

use Drupal\ccg_kickstart\Form\DefaultContentForm;
use Drupal\ccg_kickstart\Form\FeatureForm;

/**
 * Implements hook_install_tasks().
 */
function ccg_kickstart_install_tasks(&$install_state) {
  $create_default_content = \Drupal::state()->get('ccg_kickstart.create_default_content', FALSE);
  return array(
    'ccg_kickstart_features' => array(
      'display_name' => t('Select features'),
      'display' => TRUE,
      'type' => 'form',
      'function' => FeatureForm::class,
    ),
    'ccg_kickstart_install_features' => array(
      'display_name' => t('Install features'),
      'display' => TRUE,
      'type' => 'batch',
    ),
    'ccg_kickstart_create_default_content' => array(
      'display_name' => t('Create default content'),
      'display' => $create_default_content,
      'type' => 'form',
      'function' => DefaultContentForm::class,
      'run' => $create_default_content ? INSTALL_TASK_RUN_IF_NOT_COMPLETED : INSTALL_TASK_SKIP,
    ),
    'ccg_kickstart_install_default_content' => array(
      'display_name' => t('Install default content'),
      'display' => $create_default_content,
      'type' => 'batch',
      'run' => $create_default_content ? INSTALL_TASK_RUN_IF_NOT_COMPLETED : INSTALL_TASK_SKIP,
    ),
  );
}

/**
 * Deals with building an array of batch operations for the installation
 * of selected features.
 *
 * @return array
 */
function ccg_kickstart_install_features(array &$install_state) {
  $batch = [];
  // In the case all features are selected reveal the default content step.
  if (count($install_state['ccg_kickstart']['features']) === count(\Drupal\ccg_kickstart\CCGKickstart::features())) {
    \Drupal::state()->set('ccg_kickstart.create_default_content', TRUE);
  }
  foreach ($install_state['ccg_kickstart']['features'] as $feature) {
    $batch['operations'][] = ['ccg_kickstart_install_feature', [$feature]];
  }
  return $batch;
}

/**
 * Deals with installing a single feature for a new site install.
 *
 * @param $feature
 */
function ccg_kickstart_install_feature($feature) {
  \Drupal::service('module_installer')->install([$feature]);
}


/**
 * Deals with building an array of batch operations for installing default content
 * and any other tasks needed to be carried out.
 *
 * @param array $install_state
 * @return array
 */
function ccg_kickstart_install_default_content(array &$install_state) {
  $batch = [];
  foreach ($install_state['ccg_kickstart']['default_content_tasks'] as $task) {
    $batch['operations'][] = ['ccg_kickstart_default_content_task', [$task]];
  }
  return $batch;
}

/**
 * Deals with carrying out a single default content batch task.
 *
 * @param $task
 */
function ccg_kickstart_default_content_task($task) {
  if (strpos($task, 'module:') === 0) {
    $module = explode(':', $task)[1];
    \Drupal::service('module_installer')->install([$module]);
  }
}
