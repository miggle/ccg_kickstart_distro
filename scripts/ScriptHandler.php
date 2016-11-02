<?php

/**
 * @file
 * Contains \miggle\CCGKickstart\ScriptHandler.
 */

namespace miggle\CCGKickstart;

use Composer\Script\Event;
use Composer\Util\ProcessExecutor;

class ScriptHandler {

  public static function moveModules(Event $event) {
    $extra = $event->getComposer()->getPackage()->getExtra();

    $modules_source = $extra['lightning-modules-path'];
    $modules_target = $extra['demo-modules-path'];

    $script = new ScriptHandler();
    $script->recurse_copy($modules_source, $modules_target);
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

  /**
   * Remove the distribution definition from the lightning install profile.
   *
   * @param Event $event
   */
  public static function removeDistros(Event $event) {
    $lightning_info = 'docroot/profiles/contrib/lightning/lightning.info.yml';
    $dataArray = Yaml::parse($lightning_info);

    unset($dataArray['distribution']);
    $dataString = Yaml::dump($dataArray);

    file_put_contents($lightning_info, $dataString);
  }
}
