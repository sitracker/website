<div id='side'>
 <img src='http://sitracker.sourceforge.net/newlogo.png' alt='SiT logo' />
<?php
include_once 'magpie/rss_fetch.inc';

echo "<h2><span style='float:right;margin-top:6px;'><a href='{$relfeedurl}' title='Releases feed (RSS)'>";
echo "<img src='feed-icon-12x12.png' width='12' height='12' alt='' /></a></span>Releases</h2>";


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
<a href='/wiki/Download'>
 <img class='left' src='http://sitracker.sourceforge.net/download.png'  style='padding-top:5px;' alt='' /><br />
 <strong>Download</strong><br />Download the latest release</a>
 <p>See the <a href='/wiki/ReleaseNotes'>release notes</a> for details.</p>

<!-- Ohloh, I use it button -->
<script type="text/javascript" src="http://www.ohloh.net/p/15567/widgets/project_users_logo.js"></script>

<br />

<?php
echo "<h2><span style='float:right;margin-top:6px;'><a href='{$microblogfeedurl}' title='Identi.ca (RSS)'>";
echo "<img src='feed-icon-12x12.png' width='12' height='12' alt='' /></a></span>";
echo "<a href='http://identi.ca/'><img src='identicaicon.png' width='16' height='16' alt='' /></a> ";
echo "<a href='{$microblogurl}' title='Identi.ca Microblogging'>Identi.ca</a></h2>";

$mbrss = fetch_rss($microblogfeedurl);
$count = 1;
foreach ($mbrss->items as $post)
{
    if ($count <= 3)
    {
        $post['dc']['date'] = str_replace("T", " @ ", $post['dc']['date']);
        $post['dc']['date'] = str_replace("+00:00", "", $post['dc']['date']);
        $post['title'] = str_replace("!sit", "<a href='http://identi.ca/groups/sit'>!sit</a>", $post['title']);
        $post['title'] = preg_replace("/^(.*?):/s", "<a href='http://identi.ca/$1'>$1</a>:", $post['title']);
        $post['title'] = preg_replace("!([\n\t ]+)(http[s]?:/{2}[\w\.]{2,}[/\w\-\.\?\&\=\#\$\%|;|\[|\]~:]*)!e", "'\\1<a href=\"\\2\" title=\"\\2\">\\2</a>'", $post['title']);
        echo "<p>{$post['title']}<br />";
        echo "<small><a href='{$post['link']}'>{$post['dc']['date']}</a></small></p>";
        $count++;
    }
}
?>
</div>

