<div id='side'>
 <img src='http://sitracker.sourceforge.net/newlogo.png' alt='SiT logo' />
<?php
include_once 'magpie/rss_fetch.inc';

echo "<h2><div style='float:right;margin-top:6px;'><a href='{$relfeedurl}' title='Releases feed (RSS)'>";
echo "<img src='feed-icon-12x12.png' width='12' height='12' alt='' /></a></div>Releases</h2>";


$relrss = fetch_rss($relfeedurl);
if (is_object($relrss))
{
    $version = str_replace('Support Incident Tracker (SiT!) ','',$relrss->items[0]['title']);
    $version = str_replace(' - Released','',$version);
    $reldate = date('d F Y',strtotime($relrss->items[0]['pubdate']));

    $html .= "<h3>Latest release: {$version} ({$reldate})</h3>\n";

    echo $html;
}
// <h3>Latest release: v3.41 (17 December 2008)</h3>
?>
<a href='/Download'>
 <img class='left' src='http://sitracker.sourceforge.net/download.png'  style='padding-top:5px;' alt='' /><br />
 <strong>Download</strong><br />Download the latest release</a>
 <p>See the <a href='/ReleaseNotes'>release notes</a> for details.</p>
<!-- Ohloh, I use it button -->
<script type="text/javascript" src="http://www.ohloh.net/p/15567/widgets/project_users_logo.js"></script>
</div>