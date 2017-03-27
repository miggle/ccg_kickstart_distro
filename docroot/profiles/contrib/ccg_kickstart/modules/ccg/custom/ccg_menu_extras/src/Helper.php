<?php

namespace Drupal\ccg_menu_extras;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Menu\MenuLinkInterface;
use Drupal\menu_link_content\Plugin\Menu\MenuLinkContent;

/**
 * Class Helper
 *
 * Provides a utility to carry out tasks as a part of the process
 * of providing extra configurable functionality to menu items.
 *
 * @package Drupal\ccg_menu_extras
 */
class Helper {

  /**
   * Takes a form state object and retrieves a menu link entity.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * @return mixed The menu link entity.
   */
  public function getMenuItemFromFormState(FormStateInterface $form_state) {
    $build_info = $form_state->getBuildInfo();
    $menu_link_content_form = $build_info['callback_object'];
    return $menu_link_content_form->getEntity();
  }

  /**
   * Get a menu link entity from menu link content plugin.
   *
   * @param \Drupal\Core\Menu\MenuLinkInterface $menu_link_content_plugin
   */
  public function getMenuLinkEntityFromLink(MenuLinkInterface $menu_link_content_plugin) {
    $entity = NULL;
    if ($menu_link_content_plugin instanceof MenuLinkContent) {
      list($entity_type, $uuid) = explode(':', $menu_link_content_plugin->getPluginId(), 2);
      $entity = \Drupal::entityManager()->loadEntityByUuid($entity_type, $uuid);
    }
    return $entity;
  }
}
