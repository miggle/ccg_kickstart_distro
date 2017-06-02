<?php

namespace Drupal\ccg_kickstart;


use Symfony\Component\Yaml\Yaml;

class CCGKickstart {

  /**
   * Provides the static variable to store the list of features
   * the distribution provides.
   */
  private static $featureList = NULL;

  /**
   * Provides a list of features used by the distribution
   * to package up configuration for the site.
   *
   * @return array The list of features used by the distribution.
   */
  public static function features() {
    if (self::$featureList === NULL) {
      $features_dir = drupal_get_path('profile', 'ccg_kickstart') . '/modules/ccg/features';
      $features = array_map(function ($file) {
        $parts = explode('/', $file);
        // Simply return the last directory in the path which is the name of the feature.
        return $parts[count($parts) - 1];
      }, array_filter(glob($features_dir . '/*'), 'is_dir'));
      // Now create an array of the features with human-readable names.
      $features_with_names = [];
      foreach ($features as $feature) {
        // Load the info file to retrieve the human friendly name and description for the feature.
        $info = Yaml::parse($features_dir . '/' . $feature . '/' . $feature . '.info.yml');
        $features_with_names[$feature] = ['name' => t($info['name']), 'description' => t($info['description'])];
      }
      self::$featureList = $features_with_names;
    }
    return self::$featureList;
  }

  /**
   * Provides a list of the currently enabled CCG Kickstart distribution features.
   * This is useful for determining which default content to allow the user to select
   * for installing.
   *
   * @return array The enabled CCG Kickstart distribution features.
   */
  public static function enabledFeatures() {
    $enabled = [];
    $module_handler = \Drupal::service('module_handler');
    $features = self::features();
    foreach ($features as $key => $info) {
      if ($module_handler->moduleExists($key)) {
        $enabled[$key] = $info;
      }
    }
    return $enabled;
  }
}
