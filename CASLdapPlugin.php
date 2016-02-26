<?php
/**
 * CASLdapPlugin for phplist
 *
 * This file is a part of CASLdapPlugin.
 *
 * This plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.    See the
 * GNU General Public License for more details.
 *
 * @category    phplist
 * @package     CASLdapPlugin
 * @license     http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

 /**
 * Registers the plugin with phplist
 */
class CASLdapPlugin extends phplistPlugin
{
    /*
    * Inherited Variables
    */
    public $name = 'CAS LDAP Auth Plugin';
    public $description = 'Use CAS SSO to authenticate administrators and retrieve their informations and superuser rights from LDAP';
    public $documentationUrl = 'https://github.com/brenard/phplist-casldap-plugin';
    public $authors = 'Benjamin Renard';
    public $enabled = 1;
    public $version = '1.0';

    // these 2 settings create fields on lists/admin/?page=configure under the CAS LDAP section
    public $settings = array(
        'casldap_phpcas_path' => array(
            'description' => 'PhpCAS library path',
            'type' => 'text',
            'value' => 'CAS.php',
            'allowempty' => false,
            'category'=> 'CAS/LDAP',
        ),
        'casldap_netldap2_path' => array(
            'description' => 'Net_LDAP2 library path',
            'type' => 'text',
            'value' => 'Net/LDAP2.php',
            'allowempty' => false,
            'category'=> 'CAS/LDAP',
        ),
        'cas_host' => array(
            'description' => 'CAS : server host',
            'type' => 'text',
            'value' => '',
            'allowempty' => false,
            'category'=> 'CAS/LDAP',
        ),
        'cas_context' => array(
            'description' => 'CAS : Context of the Server (example : /cas)',
            'type' => 'text',
            'value' => '/cas',
            'allowempty' => false,
            'category'=> 'CAS/LDAP',
        ),
        'cas_port' => array(
            'description' => "CAS : Port of your server. Normally for a https server it's 443",
            'type' => 'integer',
            'value' => 443,
            'min' => 1,
            'max' => 65535,
            'allowempty' => false,
            'category'=> 'CAS/LDAP',
        ),
        'cas_version' => array(
            'description' => "CAS : Protocol version. Could be 1, 2 or 3.",
            'type' => 'integer',
            'value' => 2,
            'min' => 1,
            'max' => 3,
            'allowempty' => false,
            'category'=> 'CAS/LDAP',
        ),
        'cas_disable_logout' => array(
            'description' => "CAS : Disable CAS logout when user logout from phplist.",
            'type' => 'boolean',
            'value' => false,
            'allowempty' => true,
            'category'=> 'CAS/LDAP',
        ),
        'cas_server_ca_cert_path' => array(
            'description' => "CAS : Path to the CA chain that issued the server certificate",
            'type' => 'text',
            'value' => '',
            'allowempty' => true,
            'category'=> 'CAS/LDAP',
        ),
        'cas_server_disable_ssl_validation' => array(
            'description' => "CAS : Disable SSL validation of the CAS server. If Path to the CA chain that issued the server certificate is not provide, the SSL validation is automatically disabled.",
            'type' => 'boolean',
            'value' => false,
            'allowempty' => false,
            'category'=> 'CAS/LDAP',
        ),
        'cas_debug_file_path' => array(
            'description' => "CAS : debug file path",
            'type' => 'text',
            'value' => '',
            'allowempty' => true,
            'category'=> 'CAS/LDAP',
        ),
        'ldap_host' => array(
            'description' => "LDAP : server name to connect to. You can provide several hosts separated by comma.",
            'type' => 'text',
            'value' => 'localhost',
            'allowempty' => false,
            'category'=> 'CAS/LDAP',
        ),
        'ldap_port' => array(
            'description' => "LDAP : server port",
            'type' => 'integer',
            'value' => 389,
            'min' => 1,
            'max' => 65535,
            'allowempty' => true,
            'category'=> 'CAS/LDAP',
        ),
        'ldap_version' => array(
            'description' => "LDAP : protocol version",
            'type' => 'integer',
            'value' => 3,
            'min' => 2,
            'max' => 3,
            'allowempty' => true,
            'category'=> 'CAS/LDAP',
        ),
        'ldap_starttls' => array(
            'description' => "LDAP : TLS is started after connecting",
            'type' => 'boolean',
            'value' => false,
            'allowempty' => false,
            'category'=> 'CAS/LDAP',
        ),
        'ldap_binddn' => array(
            'description' => "LDAP : The DN to bind as (username). If you don't supply this, an anonymous bind will be established.",
            'type' => 'text',
            'value' => '',
            'allowempty' => true,
            'category'=> 'CAS/LDAP',
        ),
        'ldap_bindpw' => array(
            'description' => "LDAP : Password for the binddn",
            'type' => 'text',
            'value' => '',
            'allowempty' => true,
            'category'=> 'CAS/LDAP',
        ),
        'ldap_basedn' => array(
            'description' => "LDAP : base DN (root directory)",
            'type' => 'text',
            'value' => '',
            'allowempty' => false,
            'category'=> 'CAS/LDAP',
        ),
        'ldap_search_user_filter' => array(
            'description' => "LDAP : search user filter. Keyword %login will be replace by user CAS login.",
            'type' => 'text',
            'value' => '(|(uid=%login)(mail=%login))',
            'allowempty' => false,
            'category'=> 'CAS/LDAP',
        ),
        'ldap_search_user_basedn' => array(
            'description' => "LDAP : search user base DN. If not specify, root directory base DN will be use",
            'type' => 'text',
            'value' => '',
            'allowempty' => true,
            'category'=> 'CAS/LDAP',
        ),
        'ldap_search_user_scope' => array(
            'description' => "LDAP : search user scope. Could be : base, sub or one. If not specify, 'sub' is used.",
            'type' => 'text',
            'value' => '',
            'allowempty' => true,
            'category'=> 'CAS/LDAP',
        ),

        'ldap_search_user_login_attr' => array(
            'description' => "LDAP : search user login attribute. If not specify, CAS login will be use as phplist login.",
            'type' => 'text',
            'value' => 'uid',
            'allowempty' => true,
            'category'=> 'CAS/LDAP',
        ),
        'ldap_search_user_mail_attr' => array(
            'description' => "LDAP : search user mail attribute.",
            'type' => 'text',
            'value' => 'mail',
            'allowempty' => false,
            'category'=> 'CAS/LDAP',
        ),
        'ldap_search_superuser_filter' => array(
            'description' => "LDAP : search filter use to known if logged user is a superuser. Keyword %login will be replace by user phplist login. If this search return one (or more) LDAP object, user will be consider as a superuser. If not specify, no search will be make and superuser status will be base on <em>casldap_all_user_superadmin</em> parameter.",
            'type' => 'text',
            'value' => '(memberuid=%login)',
            'allowempty' => false,
            'category'=> 'CAS/LDAP',
        ),
        'ldap_search_superuser_basedn' => array(
            'description' => "LDAP : search superuser base DN. If not specify, root directory base DN will be use",
            'type' => 'text',
            'value' => '',
            'allowempty' => true,
            'category'=> 'CAS/LDAP',
        ),
        'casldap_all_user_superadmin' => array(
            'description' => "Make all logged user as superuser",
            'type' => 'boolean',
            'value' => false,
            'allowempty' => false,
            'category'=> 'CAS/LDAP',
        ),
	'casldap_logout_redirect_url' => array(
            'description' => "Redirect URL after user logout",
            'type' => 'url',
            'value' => '/',
            'allowempty' => false,
            'category'=> 'CAS/LDAP',
        ),
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function activate() {
      if (CHECK_REFERRER) {
        global $allowed_referrers;
        if (!isset($allowed_referrers)) $allowed_referrers=array();
        $cas_host=getConfig('cas_host');
        if ($cas_host) $allowed_referrers[]=$cas_host;
      }
      if (isset($_GET['ticket'])) self :: login();
    }

    private function init_cas_client() {
	if (class_exists('phpCAS'))
		return true;

	require(getConfig('casldap_phpcas_path'));

	$cas_debug_file=getConfig('cas_debug_file_path');
	if (!empty($cas_debug_file)) {
		phpCAS::setDebug($cas_debug_file);
	}

	$cas_host=getConfig('cas_host');
	$cas_port=getConfig('cas_port') or 443;
	$cas_context=getConfig('cas_context');

	switch(getConfig('cas_version')) {
		case 1:
			$cas_version=CAS_VERSION_1_0;
			break;
		case 2:
			$cas_version=CAS_VERSION_2_0;
			break;
		case 3:
			$cas_version=CAS_VERSION_3_0;
			break;
		default:
			$cas_version=CAS_VERSION_2_0;
			break;
	}

	phpCAS::client($cas_version, $cas_host, intval($cas_port), $cas_context);

	$cas_server_ca_cert_path=getConfig('cas_server_ca_cert_path');
	if ($cas_server_ca_cert_path) {
		phpCAS::setCasServerCACert($cas_server_ca_cert_path);
	}
	else {
		phpCAS::setNoCasServerValidation();
	}
    }

    public function login()
    {

        global $tables;

	if (!class_exists('Net_LDAP2'))
		require(getConfig('casldap_netldap2_path'));

	self :: init_cas_client();

	// force CAS authentication
	phpCAS::forceAuthentication();

	$cas_login=phpCAS::getUser();

	if (!$cas_login) return;

	$ldap_config=array (
		'host' => getConfig('ldap_host'),
		'port' => getConfig('ldap_port'),
		'basedn' => getConfig('ldap_basedn'),
	);

	$ldap_version=getConfig('ldap_version');
	if (is_int($ldap_version))
		$ldap_config['version']=$ldap_version;

	if (getConfig('ldap_starttls'))
		$ldap_config['starttls']=true;

	$ldap_binddn=getConfig('ldap_binddn');
	$ldap_bindpw=getConfig('ldap_bindpw');
	if ($ldap_binddn && $ldap_bindpw) {
		$ldap_config['binddn']=$ldap_binddn;
		$ldap_config['bindpw']=$ldap_bindpw;
	}
	$ldap = Net_LDAP2::connect($ldap_config);

	if (Net_LDAP2::isError($ldap)) {
		die(Fatal_Error(s("Could not connect to LDAP-server: %s",$ldap->getMessage())));
	}

	$user_filter=str_replace('%login',$cas_login,getConfig('ldap_search_user_filter'));
	$user_basedn=getConfig('ldap_search_user_basedn');
	if (!$user_basedn) $user_basedn=NULL;

	$user_scope=getConfig('ldap_search_user_scope');
	if (!in_array($user_scope,array('one','base','sub'))) $user_scope='sub';

	$user_login_attr=getConfig('ldap_search_user_login_attr');
	$user_mail_attr=getConfig('ldap_search_user_mail_attr');

	$options=array(
		'scope' => $user_scope,
		'attributes' => array($user_mail_attr)
	);

	if ($user_login_attr) $options['attributes'][]=$user_login_attr;

	$search = $ldap->search($user_basedn, $user_filter, $options);

	if (Net_LDAP2::isError($search)) {
		die(Fatal_Error(s("A problem occured during user search in LDAP : %s",$search->getMessage())));
	}

	if ($search->count()==0) {
		die(Error(s("You are not authorized to access to this page")));
	}
	elseif ($search->count()!=1) {
		die(Fatal_Error(s("Found %d users in LDAP corresponding to CAS login %s.",$search->count(),$cas_login)));
	}

	$user_entry = $search->shiftEntry();

	if ($user_login_attr) {
		$login=$user_entry->getValue($user_login_attr,'single');
		if (!is_string($login)) {
			die(Fatal_Error(s("Fail to retreive user login from LDAP data")));
		}
	}
	else {
		$login=$cas_login;
	}

	$mail=$user_entry->getValue($user_mail_attr,'single');
	if (!is_string($mail)) {
		die(Fatal_Error(s("Fail to retreive user mail from LDAP data")));
	}

	$superuser=0;


	$superuser_filter=getConfig('ldap_search_superuser_filter');

	if ($superuser_filter) {
		$superuser_filter=str_replace('%login',$login,$superuser_filter);
		$superuser_basedn=getConfig('ldap_search_superuser_basedn');
		if (!$superuser_basedn) $superuser_basedn=NULL;
		$superuser_scope=getConfig('ldap_search_superuser_scope');
		if (!in_array($superuser_scope,array('one','base','sub'))) $superuser_scope='sub';
		$search = $ldap->search($superuser_basedn,$superuser_filter,array('scope' => $superuser_scope, 'attrsonly' => true));
		if (Net_LDAP2::isError($search)) {
			die(Fatal_Error(s("A problem occured during the search in LDAP to known if user is a superuser : %s",$search->getMessage())));
		}

		if ($search->count()>0) {
			$superuser=1;
		}
	}
	elseif (getConfig('casldap_all_user_superadmin')) {
		$superuser=1;
	}

	$row = Sql_Fetch_Row_Query(
		sprintf(
			"SELECT id, privileges
			FROM {$tables['admin']}
			WHERE loginname = '%s'",
			sql_escape($login)
		)
	);

	if ($row) {
		list($id, $privileges) = $row;

		$update = Sql_Query(
			sprintf(
				"UPDATE {$tables['admin']} SET
				email = '%s',
				superuser = %s,
				disabled = 0
				WHERE id=%s",
				sql_escape($mail),
				$superuser,
				$id
			)
		);

		if (!$update) {
			die(Fatal_Error(s("Fail to update user informations in database : %s",Sql_Error())));
		}
	}
	else {
		$insert = Sql_Query(
			sprintf(
				"INSERT INTO {$tables['admin']}
				(loginname,email,superuser,disabled)
				VALUES
				('%s','%s',%s,0)",
				sql_escape($login),
				sql_escape($mail),
				$superuser
			)
		);
		if (!$insert) {
			die(Fatal_Error(s("Fail to create user in database : %s",Sql_Error())));
		}

		$id = Sql_Insert_Id();
	}

	$_SESSION['adminloggedin'] = $_SERVER["REMOTE_ADDR"];
	$_SESSION['logindetails'] = array(
	    'adminname' => $login,
	    'id' => $id,
	    'superuser' => $superuser
	);

	if ($privileges) {
	    $_SESSION['privileges'] = unserialize($privileges);
	}

	if (isset($_GET['ticket'])) {
		header('Location: '.$_SERVER['REQUEST_URI']);
		exit();
	}

	return true;
    }

    //When user logs out redirect them to the CAS logout page and then back to here.
    public function logout()
    {

	$logout_redirect_url=getConfig('casldap_logout_redirect_url');

        $_SESSION['adminloggedin'] = "";
        $_SESSION['logindetails'] = "";

        //destroy the session
        session_destroy();

	if (!getConfig('cas_disable_logout')) {
		self :: init_cas_client();
		phpCAS::logoutWithUrl($logout_redirect_url);
	}

        header( "Location: $logout_redirect_url" );
        exit();
    }
}


