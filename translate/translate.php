<?php
// translate.php - A simple interface for aiding translation.
//
// SiT (Support Incident Tracker) - Support call tracking system
// Copyright (C) 2010 The Support Incident Tracker Project
// Copyright (C) 2000-2009 Salford Software Ltd. and Contributors
//
// This software may be used and distributed according to the terms
// of the GNU General Public License, incorporated herein by reference.
//

// Authors: Kieran Hogg <kieran[at]sitracker.org>
//          Ivan Lucas <ivan_lucas[at]users.sourceforge.net>
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB" lang="en-GB">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<style type='text/css'>
body { background-color:#FFFFFF; color:#000000; font-family:Tahoma,Helvetica,Arial,'sans serif'; font-size:8pt; margin:0 0 15px; }
h2 { font-size:14pt; }
tr.shade1, .shade1 { background-color: #CCCCFF; }
tr.shade2, .shade2 { background-color: #DDDDFF; }
tr.shade1:hover, tr.shade2:hover { background-color: #F2F2F2; }

</style>

<?php

$permission = 0; // not required
//require ('core.php');

//ini_set('default_charset', $i18ncharset);
require ("lib/functions.inc.php");


//require (APPLICATION_LIBPATH . 'auth.inc.php');

$title = "Translate";

//include (APPLICATION_INCPATH . 'htmlheader.inc.php');

$tolang = cleanvar($_REQUEST['lang']);
$fromlang = cleanvar($_REQUEST['from']);

if (!$_REQUEST['mode'])
{
    echo "<div align='center'><h2>Translation</h2>";
    echo "<div align='center'><p>Help To Translate";
    echo "<p>Choose Language</p>";
    echo "<form action='{$_SERVER['PHP_SELF']}?mode=show' method='get'>";
    //FIXME
    echo "<input name='mode' value='show' type='hidden' />";
    echo "<strong>From</strong>: ";
    echo "<select name='from'>";
    foreach ($i18n_codes AS $langcode => $language)
    {
        echo "<option value='{$langcode}'";
        if ($langcode == 'en-GB') echo " selected = 'selected' ";
        echo ">{$langcode} - {$language}</option>\n";
    }
    echo "</select> <strong>To</strong>: ";
    echo "<select name='lang'>";
    foreach ($i18n_codes AS $langcode => $language)
    {
        if ($langcode != 'en-GB') echo "<option value='{$langcode}'>{$langcode} - {$language}</option>\n";
    }
    echo "</select>";
    echo "<br /><br />";
    echo "<input type='submit' value='Translate' />";
    echo "</form></div>\n";
}
elseif ($_REQUEST['mode'] == "show")
{
    $from = cleanvar($_REQUEST['from']);
    //open english file
    $fromfile = "i18n/{$from}.inc.php";
    $fh = fopen($fromfile, 'r');
    $theData = fread($fh, filesize($fromfile));
    fclose($fh);
    $lines = explode("\n", $theData);
    $langstrings[$from];
    $fromvalues = array();

    foreach ($lines as $values)
    {
        $badchars = array("$", "\"", "\\", "<?php", "?>");
        $values = trim(str_replace($badchars, '', $values));

        //get variable and value
        $vars = explode("=", $values);

        //remove spaces
        $vars[0] = trim($vars[0]);
        $vars[1] = trim($vars[1]);

        if (substr($vars[0], 0, 3) == "str")
        {
            //remove leading and trailing quotation marks
            $vars[1] = substr_replace($vars[1], "",-2);
            $vars[1] = substr_replace($vars[1], "",0, 1);
            $fromvalues[$vars[0]] = $vars[1];
        }
        elseif (substr($vars[0], 0, 2) == "# ")
        {
            $comments[$lastkey] = substr($vars[0], 2, 1024);
        }
        else
        {
            if (substr($values, 0, 4) == "lang")
                $languagestring=$values;
            if (substr($values, 0, 8) == "i18nchar")
                $i18ncharset=$values;
        }
        $lastkey = $vars[0];
    }
    $origcount = count($fromvalues);
    unset($lines);

    //open foreign file
    $myFile = "i18n/{$tolang}.inc.php";
    if (file_exists($myFile))
    {
        $foreignvalues = array();

        $fh = fopen($myFile, 'r');
        $theData = fread($fh, filesize($myFile));
        fclose($fh);
        $lines = explode("\n", $theData);
        //print_r($lines);
        foreach ($lines AS $introcomment)
        {
            if (substr($introcomment, 0, 2) == "//")
            {
                $meta[] = substr($introcomment, 3);
            }
            if (trim($introcomment) == '') break;
        }


        foreach ($lines as $values)
        {
            $badchars = array("$", "\"", "\\", "<?php", "?>");
            $values = trim(str_replace($badchars, '', $values));
            if (substr($values, 0, 3) == "str")
            {
                $vars = explode("=", $values);
                $vars[0] = trim($vars[0]);
                $vars[1] = trim(substr_replace($vars[1], "",-2));
                $vars[1] = substr_replace($vars[1], "",0, 1);
                $foreignvalues[$vars[0]] = $vars[1];
            }
            elseif (substr($values, 0, 12) == "i18nAlphabet")
            {
                $values = explode('=',$values);
                $delims = array("'", ';');
                $i18nalphabet=str_replace($delims,'',$values[1]);;
            }

        }
    }
    else
    {
        $meta[] = "SiT! Language File - {$languages[$tolang]} ($tolang) by {$_SESSION['realname']} <{$_SESSION['email']}>";
    }

    echo "<h2 align='center'>Word List</h2>";
    echo "<p align='center'>Translate the english string on the left to your requested language on the right<br/>";
    echo "<strong>When translating, please do not translate anything that looks like the following: [b] {word} %s. These are placeholders, leave them in the appropriate place in your translated string</strong></p>";
    echo "<form method='post' action='{$_SERVER[PHP_SELF]}?mode=save'>";
    echo "<table align='center' style='table-layout:fixed'>";
    echo "<col width='33%'/><col width='33%'/><col width='33%'/>";
    echo "<tr class='shade1'><td colspan='3'>";
    if (is_array($meta))
    {
        foreach ($meta AS $metaline)
        {
            echo "<input type='text' name='meta[]' value=\"{$metaline}\" size='80' style='width: 100%;' /><br />";
        }
    }
    echo "</td></tr>";
    echo "<tr class='shade2'><td><code>i18nAlphabet</code></td>";
    echo "<td colspan='2'><input type='text' name='i18nalphabet' value=\"{$i18nalphabet}\" size='80' style='width: 100%;' /></td></tr>";
    echo "<tr><th>Variable</th><th>{$from}</th><th>{$tolang}</th></tr>";

    $shade = 'shade1';
    foreach (array_keys($fromvalues) as $key)
    {
        if ($_REQUEST['lang'] == 'zz') $foreignvalues[$key] = $key;
        echo "<tr class='$shade'><td><label for=\"{$key}\"><code>{$key}</code></label></td>";
        echo "<td><input name='english_{$key}' value=\"".htmlentities($fromvalues[$key], ENT_QUOTES, 'UTF-8')."\" size=\"50\" readonly='readonly' /></td>";
        echo "<td><input id=\"{$key}\" ";
        if (empty($foreignvalues[$key])) echo "class='notice' onblur=\"if ($('{$key}').value != '') { $('{$key}').removeClassName('notice'); $('{$key}').addClassName('idle');} \"";
        echo "name=\"{$key}\" value=\"".htmlentities($foreignvalues[$key], ENT_QUOTES, 'UTF-8')."\" size=\"50\" />";
        if (empty($foreignvalues[$key])) echo "<span style='color:red;'>*</span>";
        echo "</td></tr>\n";
        if ($shade=='shade1') $shade='shade2';
        else $shade='shade1';
        if (!empty($comments[$key])) echo "<tr><td colspan='3' class='{$shade}'><strong>Notes:</strong> {$comments[$key]}</td></tr>\n";
    }
    echo "</table>";
    echo "<input type='hidden' name='origcount' value='{$origcount}' />";
    echo "<input name='lang' value='{$_REQUEST['lang']}' type='hidden' /><input name='mode' value='save' type='hidden' />";
    echo "<div align='center'>";
    if (is_writable($myFile))
    {
        echo "<input type='submit' value='Save' />";
    }
    else
    {
        echo "<input type='submit' value='Save / Display' />";
    }
    echo "</div>";

    echo "</form>\n";
}
elseif ($_REQUEST['mode'] == "save")
{
    $badchars = array('.','/','\\');

    $lang = cleanvar($_REQUEST['lang']);
    $lang = str_replace($badchars, '', $lang);
    $origcount = cleanvar($_REQUEST['origcount']);
    $i18nalphabet = cleanvar($_REQUEST['i18nalphabet'], TRUE, FALSE);

    $filename = "{$lang}.inc.php";
    echo "<p>Send Translation, <code>{$filename}</code>", "<code>i18n</code>", "<a href='mailto:sitracker-devel-discuss@lists.sourceforge.net'>sitracker-devel-discuss@lists.sourceforge.net</a> </p>";
    $i18nfile = '';
    $i18nfile .= "<?php\n";
    foreach ($_REQUEST['meta'] AS $meta)
    {
        $meta = cleanvar($meta);
        $i18nfile .= "// $meta\n";
    }
    $i18nfile .= "\n";
    $i18nfile .= "\$languagestring = '{$languages[$lang]} ($lang)';\n";
    $i18nfile .= "\$i18ncharset = 'UTF-8';\n\n";

    if (!empty($i18nalphabet))
    {
        $i18nfile .= "// List of letters of the alphabet for this language\n";
        $i18nfile .= "// in standard alphabetical order (upper case, where applicable)\n";
        $i18nfile .= "\$i18nAlphabet = '{$i18nalphabet}';\n\n";
    }

    $i18nfile .= "// list of strings (Alphabetical by key)\n";

    $lastchar='';
    $translatedcount=0;
    foreach (array_keys($_POST) as $key)
    {
        if (!empty($_POST[$key]) AND substr($key, 0, 3) == "str")
        {
            if ($lastchar!='' AND substr($key, 3, 1) != $lastchar) $i18nfile .= "\n";
            $i18nfile .= "\${$key} = '".addslashes($_POST[$key])."';\n";
            $lastchar = substr($key, 3, 1);
            $translatedcount++;
        }
    }
    $percent = number_format($translatedcount / $origcount * 100,2);
    echo "<p>Translation: <strong>{$translatedcount}</strong>/{$origcount} = {$percent}% Complete.</p>";
    $i18nfile .= "?>\n";

    $myFile = "i18n/{$filename}";
    $fp = @fopen($myFile, 'w');
    if (!$fp)
    {
        echo "<p class='warn'>Cannot Write File, <code>{$myFile}</code></p>";
    }
    else
    {
        fwrite($fp, $i18nfile);
        fclose($fp);
        echo "<p class='info'>File Saved As, <code>{$myFile}</code></p>";
    }

    echo "<div style='margin-left: 5%; margin-right: 5%; background-color: white; border: 1px solid #ccc; padding: 1em;'>";
    highlight_string($i18nfile);
    echo "</div>";
}
?>
