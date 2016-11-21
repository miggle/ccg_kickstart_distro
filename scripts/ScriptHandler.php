<?php

/**
 * @file
 * Contains \miggle\CCGKickstart\ScriptHandler.
 */

namespace miggle\CCGKickstart;

use Composer\Script\Event;
use Composer\Util\ProcessExecutor;
use Symfony\Component\Filesystem\Filesystem;

class ScriptHandler {

  public static function moveModules(Event $event) {
    $extra = $event->getComposer()->getPackage()->getExtra();

    $modules_source = $extra['lightning-modules-path'];
    $modules_target = $extra['demo-modules-path'];

    $script = new ScriptHandler();
    $script->recurse_copy($modules_source, $modules_target);
  }

  public static function makeLightningConfigOptional(Event $event) {
    $extras = $event->getComposer()->getPackage()->getExtra();

    $modules_source = $extras['demo-modules-path'];
    $lightning_features = $modules_source . '/lightning_features';
    if (file_exists($lightning_features)) {
      // Set all lightning module config to optional.
      foreach (glob($lightning_features . '/*') as $filename) {
        $file = realpath($filename);
        if (is_dir($file)) {
          if (file_exists($file . '/config/install')) {
            if (file_exists($file . '/config/optional')) {
              (new Filesystem)->remove($file . '/config/optional');
            }
            rename($file . '/config/install', $file . '/config/optional');
          }
        }
      }
    }
  }

  public static function moveLibraries(Event $event) {
    $extra = $event->getComposer()->getPackage()->getExtra();

    $libraries_source = $extra['lib-source'];
    $libraries_target = $extra['lib-target'];

    $script = new ScriptHandler();
    $script->recurse_copy($libraries_source, $libraries_target);
  }

  public static function replaceGitIgnore(Event $event) {
    $extra = $event->getComposer()->getPackage()->getExtra();

    $template = $extra['gitignore-template'];
    // First ensure this hasn't been default with already by checking whether
    // the template file exists.
    if (file_exists($template)) {
      // Now force the copying of the template file to .gitignore.
      if (file_exists('.gitignore')) {
        unlink('.gitignore');
        copy($template, '.gitignore');
      }
      // Finally remove the template file.
      unlink($template);
    }
  }

  /**
   * Copy a directory and all of it's contents.
   * @see http://php.net/manual/en/function.copy.php#91010
   *
   * @param $src
   * @param $dst
   */
  private function recurse_copy($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
      if (( $file != '.' ) && ( $file != '..' )) {
        if ( is_dir($src . '/' . $file) ) {
          $this->recurse_copy($src . '/' . $file,$dst . '/' . $file);
        }
        else {
          copy($src . '/' . $file,$dst . '/' . $file);
        }
      }
    }
    closedir($dir);
  }
}
