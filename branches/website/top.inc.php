<?php
include 'lib.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB" lang="en-GB">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<title>SiT! Support Incident Tracker</title>
<link rel='SHORTCUT ICON' href='http://static.sitracker.org/sit_favicon.png' />
<link rel="alternate" type="application/rss+xml" title="SiT! News RSS 2.0" href="http://sitracker.wordpress.com/category/news/feed/" />
<link rel="alternate" type="application/rss+xml" title="SiT! Feeds Agregated" href="http://feeds.feedburner.com/sitfeeds" />

<script src='http://static.sitracker.org/prototype.js' type='text/javascript'></script>
<style type='text/css'>@import url('sit.css');</style>
<script type="text/javascript">
/* <![CDATA[ */
var isIE = /*@cc_on!@*/false;
function clearjumpto()
{
    $('searchfield').value = "";
}
/* ]]> */
</script>
<script type="text/javascript">
/* <![CDATA[ */
    (function() {
        var s = document.createElement('script'), t = document.getElementsByTagName('script')[0];
        s.type = 'text/javascript';
        s.async = true;
        s.src = 'http://api.flattr.com/js/0.6/load.js?mode=auto';
        t.parentNode.insertBefore(s, t);
    })();
/* ]]> */
</script>
</head>
<body>
<div id='masthead'><h1 id='apptitle'><span>SiT! Support Incident Tracker</span></h1></div>
 <div id='menu'>
 <ul id='menuList'>
 <li class='menuitem'><a href='http://sitracker.org/'>Home</a></li>
 <li class='menuitem'><a href='http://sitracker.org/wiki/'>Wiki</a></li>
 <li class='menuitem'><a href='http://sitracker.wordpress.com'>Blog</a></li>
 <li class='menuitem'><a href='http://sitracker.org/forum/'>Forum</a></li>
 <li class='menuitem'><a href='http://bugs.sitracker.org/'>Bugs</a></li>
 <li class='menuitem'><a href='http://sourceforge.net/projects/sitracker/'>Sourceforge Project</a></li>
 <?php /* <li class='menuitem'><a href='http://sitracker.org/wiki/Demo'>Demo</a></li> */?>
 <li class='menuimte'><a href='http://sitracker.org/wiki/Screenshots'>Screenshots</a></li>
 <li class='menuimte'><a href='http://sitracker.spreadshirt.co.uk/'>Merchandise</a></li>
 </ul>
 <div id='topsearch'><form name='jumptoincident' action='/wiki/Special:Search'><input type='text' name='search' id='searchfield' size='30' value='Wiki search'
    onblur="if ($('searchfield').value == '') { if (!isIE) { $('searchfield').style.color='#888;'; } $('searchfield').value='Wiki search';}"
    onfocus="if ($('searchfield').value == 'Wiki search') { if (!isIE) { $('searchfield').style.color='#000;'; } $('searchfield').value=''; }"
    onclick='clearjumpto()'/> </form></div>
</div>

<div id='container'>
<?php include ('side.inc.php'); ?>
<div id='main'>
