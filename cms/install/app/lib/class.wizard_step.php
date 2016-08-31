<?php

namespace cms_autoinstaller;

abstract class wizard_step extends \__appbase\wizard_step
{
  static $_registered;

  public function __construct()
  {
    $dd = \__appbase\get_app()->get_destdir();
    if( !$dd ) throw new \Exception('Session Failure');

    if( !self::$_registered ) {
      \__appbase\smarty()->registerPlugin('function','wizard_form_start', array($this,'fn_wizard_form_start'));
      \__appbase\smarty()->registerPlugin('function','wizard_form_end', array($this,'fn_wizard_form_end'));
      self::$_registered = 1;
    }

    \__appbase\smarty()->assign('version',\__appbase\get_app()->get_dest_version());
    \__appbase\smarty()->assign('version_name',\__appbase\get_app()->get_dest_name());
    \__appbase\smarty()->assign('dir',\__appbase\get_app()->get_destdir());
  }

  public function fn_wizard_form_start($params, $smarty)
  {
      echo '<form method="POST" action="'.$_SERVER['REQUEST_URI'].'">';
  }

  public function fn_wizard_form_end($params, $smarty)
  {
      echo '</form>';
  }

  protected function get_primary_title()
  {
      $app = \__appbase\get_app();
      $action = $this->get_wizard()->get_data('action');
      $str = null;
      switch( $action ) {
      case 'upgrade':
          $str = \__appbase\lang('action_upgrade',$app->get_dest_version());
          break;
      case 'freshen':
          $str = \__appbase\lang('action_freshen',$app->get_dest_version());
          break;
      case 'install':
      default:
          $str = \__appbase\lang('action_install',$app->get_dest_version());
      }
      return $str;
  }

  protected function display()
  {
      $app = \__appbase\get_app();
      \__appbase\smarty()->assign('wizard_steps',$this->get_wizard()->get_nav());
      \__appbase\smarty()->assign('title',$this->get_primary_title());
  }

  public function error($msg)
  {
      $msg = addslashes($msg);
      echo '<script type="text/javascript">add_error(\''.$msg.'\');</script>'."\n";
      flush();
  }

  public static function verbose($msg)
  {
      $msg = addslashes($msg);
      $verbose = \__appbase\wizard::get_instance()->get_data('verbose');
      if( $verbose )  echo '<script type="text/javascript">add_verbose(\''.$msg.'\');</script>'."\n";
      flush();
  }

  public function message($msg)
  {
      $msg = addslashes($msg);
      echo '<script type="text/javascript">add_message(\''.$msg.'\');</script>'."\n";
      flush();
  }

  public function set_block_html($id,$html)
  {
      $html = addslashes($html);
      echo '<script type="text/javascript">set_block_html(\''.$id.'\',\''.$html.'\');</script>'."\n";
      flush();
  }

  protected function finish()
  {
      echo '<script type="text/javascript">finish();</script>'."\n";
      flush();
  }

}

?>
