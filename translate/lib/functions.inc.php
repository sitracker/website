<?php
// functions.inc.php - Function library and defines for SiT -Support Incident Tracker
//
// SiT (Support Incident Tracker) - Support call tracking system
// Copyright (C) 2010 The Support Incident Tracker Project
// Copyright (C) 2000-2009 Salford Software Ltd. and Contributors
//
// This software may be used and distributed according to the terms
// of the GNU General Public License, incorporated herein by reference.
//
// Authors: Ivan Lucas, <ivanlucas[at]users.sourceforge.net>
//          Tom Gerrard, <tomgerrard[at]users.sourceforge.net> - 2001 onwards
//          Martin Kilcoyne - 2000
//          Paul Heaney, <paulheaney[at]users.sourceforge.net>
//          Kieran Hogg, <kieran[at]sitracker.org>

// Many functions here simply extract various snippets of information from
// Most are legacy and can replaced by improving the pages that call them to
// use SQL joins.

// Prevent script from being run directly (ie. it must always be included
if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']))
{
    exit;
}
/*
include (APPLICATION_LIBPATH . 'classes.inc.php');
include (APPLICATION_LIBPATH . 'ldap.inc.php');
include (APPLICATION_LIBPATH . 'base.inc.php');
include_once (APPLICATION_LIBPATH . 'billing.inc.php');
include_once (APPLICATION_LIBPATH . 'user.inc.php');
include_once (APPLICATION_LIBPATH . 'sla.inc.php');
include_once (APPLICATION_LIBPATH . 'ftp.inc.php');
include_once (APPLICATION_LIBPATH . 'tags.inc.php');
include_once (APPLICATION_LIBPATH . 'string.inc.php');
include_once (APPLICATION_LIBPATH . 'html.inc.php');
include_once (APPLICATION_LIBPATH . 'tasks.inc.php');
include_once (APPLICATION_LIBPATH . 'export.inc.php');
*/
// function stripslashes_array($data)
// {
//     if (is_array($data))
//     {
//         foreach ($data as $key => $value)
//         {
//             $data[$key] = stripslashes_array($value);
//         }
//         return $data;
//     }
//     else
//     {
//         return stripslashes($data);
//     }
// }

if (version_compare(PHP_VERSION, "5.1.0", ">="))
{
    date_default_timezone_set($CONFIG['timezone']);
}

//Prevent Magic Quotes from affecting scripts, regardless of server settings
//Make sure when reading file data,
//PHP doesn't "magically" mangle backslashes!
set_magic_quotes_runtime(FALSE);

/*
if (get_magic_quotes_gpc())
{

//     All these global variables are slash-encoded by default,
//     because    magic_quotes_gpc is set by default!
//     (And magic_quotes_gpc affects more than just $_GET, $_POST, and $_COOKIE)
//     We don't strip slashes from $_FILES as of 3.32 as this should be safe without
//     doing and it will break windows file paths if we do
    $_SERVER = stripslashes_array($_SERVER);
    $_GET = stripslashes_array($_GET);
    $_POST = stripslashes_array($_POST);
    $_COOKIE = stripslashes_array($_COOKIE);
    $_ENV = stripslashes_array($_ENV);
    $_REQUEST = stripslashes_array($_REQUEST);
    $HTTP_SERVER_VARS = stripslashes_array($HTTP_SERVER_VARS);
    $HTTP_GET_VARS = stripslashes_array($HTTP_GET_VARS);
    $HTTP_POST_VARS = stripslashes_array($HTTP_POST_VARS);
    $HTTP_COOKIE_VARS = stripslashes_array($HTTP_COOKIE_VARS);
    $HTTP_POST_FILES = stripslashes_array($HTTP_POST_FILES);
    $HTTP_ENV_VARS = stripslashes_array($HTTP_ENV_VARS);
    if (isset($_SESSION))
    {
        #These are unconfirmed (?)
        $_SESSION = stripslashes_array($_SESSION, '');
        $HTTP_SESSION_VARS = stripslashes_array($HTTP_SESSION_VARS, '');
    }
//     The $GLOBALS array is also slash-encoded, but when all the above are
//     changed, $GLOBALS is updated to reflect those changes.  (Therefore
//     $GLOBALS should never be modified directly).  $GLOBALS also contains
//     infinite recursion, so it's dangerous...
}

*/
/**
    * Authenticate a user with a username/password pair
    * @author Ivan Lucas
    * @param string $username. A username
    * @param string $password. A password (non-md5)
    * @return an integer to indicate whether the user authenticated against the database
    * @retval int 0 the credentials were wrong or the user was not found.
    * @retval int 1 to indicate user is authenticated and allowed to continue.
*/
function authenticateSQL($username, $password)
{
    global $dbUsers;

    $password = md5($password);
    if ($_SESSION['auth'] == TRUE)
    {
        // Already logged in
        return 1;
    }

    // extract user
    $sql  = "SELECT id FROM `{$dbUsers}` ";
    $sql .= "WHERE username = '{$username}' AND password = '{$password}' AND status != 0 ";
    // a status of 0 means the user account is disabled
    $result = mysql_query($sql);
    if (mysql_error()) trigger_error(mysql_error(),E_USER_WARNING);

    // return appropriate value
    if (mysql_num_rows($result) == 0)
    {
        mysql_free_result($result);
        return 0;
    }
    else
    {
        journal(CFG_LOGGING_MAX,'User Authenticated',"{$username} authenticated from " . getenv('REMOTE_ADDR'),CFG_JOURNAL_LOGIN,0);
        return 1;
    }
}


function user_permission($userid,$permission)
{
    // Default is no access
    $accessgranted = FALSE;

    if (!is_array($permission))
    {
        $permission = array($permission);
    }

    foreach ($permission AS $perm)
    {
        if (@in_array($perm, $_SESSION['permissions']) == TRUE) $accessgranted = TRUE;
        else $accessgranted = FALSE;
        // Permission 0 is always TRUE (general acess)
        if ($perm == 0) $accessgranted = TRUE;
    }
    return $accessgranted;
}

/**
  * Make an external variable safe for database and HTML display
  * @author Ivan Lucas, Kieran Hogg
  * @param mixed $var variable to replace
  * @param bool $striphtml whether to strip html
  * @param bool $transentities whether to translate all aplicable chars (true) or just special chars (false) into html entites
  * @param bool $mysqlescape whether to mysql_escape()
  * @param array $disallowedchars array of chars to remove
  * @param array $replacechars array of chars to replace as $orig => $replace
  * @returns variable
*/
function cleanvar($vars, $striphtml = TRUE, $transentities = FALSE,
                $mysqlescape = TRUE, $disallowedchars = array(),
                $replacechars = array())
{
    if (is_array($vars))
    {
        foreach ($vars as $key => $singlevar)
        {
            $var[$key] = cleanvar($singlevar, $striphtml, $transentities, $mysqlescape,
                    $disallowedchars, $replacechars);
        }
    }
    else
    {
        $var = $vars;
        if ($striphtml === TRUE)
        {
            $var = strip_tags($var);
        }

        if (!empty($disallowedchars))
        {
            $var = str_replace($disallowedchars, '', $var);
        }

        if (!empty($replacechars))
        {
            foreach ($replacechars as $orig => $replace)
            {
                $var = str_replace($orig, $replace, $var);
            }
        }

        if ($transentities)
        {
            $var = htmlentities($var, ENT_COMPAT, $GLOBALS['i18ncharset']);
        }
        else
        {
            $var = htmlspecialchars($var, ENT_COMPAT, $GLOBALS['i18ncharset']);
        }

        $var = trim($var);
    }
    return $var;
}

$i18n_codes = array(
                    'ar' => 'العربية',
                    'bg-BG' => 'български',
                    'bn-IN' => 'বাংলা',
                    'cs-CZ' => 'Český',
                    'en-GB' => 'English (British)',
                    'en-US' => 'English (US)',
                    'ca-ES' => 'Català',
                    'cy-GB' => 'Cymraeg',
                    'da-DK' => 'Dansk',
                    'de-DE' => 'Deutsch',
                    'el-GR' => 'Ελληνικά',
                    'es-ES' => 'Español',
                    'es-CO' => 'Español (Colombia)',
                    'es-MX' => 'Español (Mexicano)',
                    'et-EE' => 'Eesti',
                    'eu-ES' => 'Euskara',
                    'fa-IR' => 'فارسی',
                    'fi-FI' => 'Suomi',
                    'fo-FO' => 'føroyskt',
                    'fr-FR' => 'Français',
                    'he-IL' => 'עִבְרִית',
                    'hr-HR' => 'Hrvatski',
                    'hu-HU' => 'Magyar',
                    'id-ID' => 'Bahasa Indonesia',
                    'is-IS' => 'Íslenska',
                    'it-IT' => 'Italiano',
                    'ja-JP' => '日本語',
                    'ka' => 'ქართული',
                    'ko-KR' => '한국어',
                    'lt-LT' => 'Lietuvių',
                    'ms-MY' => 'بهاس ملايو',
                    'nb-NO' => 'Norsk (Bokmål)',
                    'nl-NL' => 'Nederlands',
                    'nn-NO' => 'Norsk (Nynorsk)',
                    'pl-PL' => 'Polski',
                    'pt-BR' => 'Português (Brasil)',
                    'pt-PT' => 'Português',
                    'ro-RO' => 'Română',
                    'ru-RU' => 'Русский',
                    'sl-SL' => 'Slovenščina',
                    'sk-SK' => 'Slovenčina',
                    'sr-YU' => 'Српски',
                    'sv-SE' => 'Svenska',
                    'th-TH' => 'ภาษาไทย',
                    'tr_TR' => 'Türkçe',
                    'uk-UA' => 'Украї́нська мо́ва',
                    'zh-CN' => '简体中文',
                    'zh-TW' => '繁體中文',
                    );


?>
