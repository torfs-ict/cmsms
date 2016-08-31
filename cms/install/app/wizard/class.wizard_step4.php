<?php

namespace cms_autoinstaller;
use \__appbase;

class wizard_step4 extends \cms_autoinstaller\wizard_step
{
    private $_config;
    private $_samplecontent;
    private $_dbms_options;

    public function __construct()
    {
        parent::__construct();

        $tz = date_default_timezone_get();
        if( !$tz ) @date_default_timezone_set('UTC');
        $this->_config = array('dbtype'=>'','dbhost'=>'localhost','dbname'=>'','dbuser'=>'',
                               'dbpass'=>'','dbprefix'=>'cms_','dbport'=>'',
                               'query_var'=>'','timezone'=>$tz);
        $this->_samplecontent = TRUE;

        // get saved date
        $tmp = $this->get_wizard()->get_data('config');
        if( is_array($tmp) && count($tmp) ) $this->_config = $tmp;
        $tmp = $this->get_wizard()->get_data('samplecontent');
        if( $tmp === 0 || $tmp === 1 ) $this->_samplecontent = $tmp;

        $databases = array('mysqli'=>'MySQLi (4.1+)', 'mysql'=>'MySQL (compatibility');
        $this->_dbms_options = array();
        foreach ($databases as $db => $lbl) {
            if( extension_loaded($db) ) $this->_dbms_options[$db] = $lbl;
        }
        if( !count($this->_dbms_options) ) throw new \Exception(\__appbase\lang('error_nodatabases'));

        $action = $this->get_wizard()->get_data('action');
        if( $action == 'freshen' ) {
            // read config data from config.php for freshen action.
            $app = \__appbase\get_app();
            $destdir = $app->get_destdir();
            $config_file = $destdir.'/config.php';
            include_once($config_file);
            $this->_config['dbtype'] = $config['dbms'];
            $this->_config['dbhost'] = $config['db_hostname'];
            $this->_config['dbuser'] = $config['db_username'];
            $this->_config['dbpass'] = $config['db_password'];
            $this->_config['dbname'] = $config['db_name'];
            $this->_config['dbprefix'] = $config['db_prefix'];
            if( isset($config['db_port']) ) $this->_config['dbport'] = $config['db_port'];
            if( isset($config['query_var']) ) $this->_config['query_var'] = $config['query_var'];
            if( isset($config['timezone']) ) $this->_config['timezone'] = $config['timezone'];
        }
    }

    private function validate($config)
    {
        if( !isset($config['dbtype']) || !$config['dbtype'] ) throw new \Exception(\__appbase\lang('error_nodbtype'));
        if( !isset($config['dbhost']) || !$config['dbhost'] ) throw new \Exception(\__appbase\lang('error_nodbhost'));
        if( !isset($config['dbname']) || !$config['dbname'] ) throw new \Exception(\__appbase\lang('error_nodbname'));
        if( !isset($config['dbuser']) || !$config['dbuser'] ) throw new \Exception(\__appbase\lang('error_nodbuser'));
        //if( !isset($config['dbpass']) || !$config['dbpass'] ) throw new \Exception(\__appbase\lang('error_nodbpass'));
        if( !isset($config['dbprefix']) || !$config['dbprefix'] ) throw new \Exception(\__appbase\lang('error_nodbprefix'));
        if( !isset($config['timezone']) || !$config['timezone'] ) throw new \Exception(\__appbase\lang('error_notimezone'));

        if( $config['dbpass'] ) {
            if( strpos($config['dbpass'],"'") !== FALSE || strpos($config['dbpass'],'\\') !== FALSE ) {
                throw new \Exception(\__appbase\lang('error_invaliddbpassword'));
            }
        }

        // try a test connection
        $db = \__appbase\new_db_connection($config['dbtype']);
        IF( $config['dbport'] ) $db->port = (int) $config['dbport'];
        $res = $db->Connect($config['dbhost'],$config['dbuser'],$config['dbpass'],$config['dbname']);
        if( !$res ) throw new \Exception(\__appbase\lang('error_dbconnect'));
        $db->Execute("SET NAMES 'utf8'");

        // see if we can create and drop a table.
        $res = $db->Execute('CREATE TABLE '.$config['dbprefix'].'_dummyinstall (i int)');
        if( !$res ) throw new \Exception(\__appbase\lang('error_createtable'));
        $res = $db->Execute('DROP TABLE '.$config['dbprefix'].'_dummyinstall');
        if( !$res ) throw new \Exception(\__appbase\lang('error_droptable'));

        // see if a smartering of core tables exist
        $action = $this->get_wizard()->get_data('action');
        if( $action == 'install' ) {
            $res = $db->GetOne('SELECT content_id FROM '.$config['dbprefix'].'content');
            if( $res > 0 ) throw new \Exception(\__appbase\lang('error_cmstablesexist'));
            $res = $db->GetOne('SELECT module_name FROM '.$config['dbprefix'].'modules');
            if( $res > 0 ) throw new \Exception(\__appbase\lang('error_cmstablesexist'));
        }
    }

    protected function process()
    {
        $tmp = array_keys($this->_dbms_options);
        $this->_config['dbtype'] = $tmp[0];
        $this->_config['dbhost'] = trim(\__appbase\utils::clean_string($_POST['dbhost']));
        $this->_config['dbname'] = trim(\__appbase\utils::clean_string($_POST['dbname']));
        $this->_config['dbuser'] = trim(\__appbase\utils::clean_string($_POST['dbuser']));
        $this->_config['dbpass'] = trim(\__appbase\utils::clean_string($_POST['dbpass']));
        $this->_config['timezone'] = trim(\__appbase\utils::clean_string($_POST['timezone']));
        if( isset($_POST['dbtype']) ) $this->_config['dbtype'] = trim(\__appbase\utils::clean_string($_POST['dbtype']));
        if( isset($_POST['dbport']) ) $this->_config['dbport'] = trim(\__appbase\utils::clean_string($_POST['dbport']));
        if( isset($_POST['dbprefix']) ) $this->_config['dbprefix'] = trim(\__appbase\utils::clean_string($_POST['dbprefix']));
        if( isset($_POST['query_var']) ) $this->_config['query_var'] = trim(\__appbase\utils::clean_string($_POST['query_var']));
        if( isset($_POST['samplecontent']) ) $this->_samplecontent = (int)$_POST['samplecontent'];

        $this->get_wizard()->set_data('config',$this->_config);
        $this->get_wizard()->set_data('samplecontent',$this->_samplecontent);

        try {
            $this->validate($this->_config);
            $url = $this->get_wizard()->next_url();
            $action = $this->get_wizard()->get_data('action');
            if( $action == 'freshen' ) $url = $this->get_wizard()->step_url(6);
            \__appbase\utils::redirect($url);
        }
        catch( \Exception $e ) {
            $smarty = \__appbase\smarty();
            $smarty->assign('error',$e->GetMessage());
        }
    }

    protected function display()
    {
        parent::display();
        $smarty = \__appbase\smarty();

        $tmp = timezone_identifiers_list();
        if( !is_array($tmp) ) throw new \Exception(\__appbase\lang('error_tzlist'));
        $tmp2 = array_combine(array_values($tmp),array_values($tmp));
        $smarty->assign('timezones',array_merge(array(''=>\__appbase\lang('none')),$tmp2));

        $smarty->assign('dbtypes',$this->_dbms_options);

        $smarty->assign('action',$this->get_wizard()->get_data('action'));
        $smarty->assign('verbose',$this->get_wizard()->get_data('verbose',0));
        $smarty->assign('config',$this->_config);
        $smarty->assign('samplecontent',$this->_samplecontent);
        $smarty->assign('yesno',array('0'=>\__appbase\lang('no'),'1'=>\__appbase\lang('yes')));
        $smarty->display('wizard_step4.tpl');
        $this->finish();
    }

} // end of class

?>