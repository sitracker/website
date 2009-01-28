<?php
// Fetch news, from sit blog
// Must be run from a machine that isn't sourceforge

// Note: we can't use Magpie on sourceforge because they helpfully block all
// outbound connectivity
// see http://alexandria.wiki.sourceforge.net/Project+Web%2C+Shell%2C+VHOST+and+Database+Services#tocProject%20Web,%20Shell,%20VHOST%20and%20Database%20Services20

include 'magpie/rss_fetch.inc';
ini_set('display_errors', '1');
error_reporting(E_ALL);
$newsrss = fetch_rss('http://sitracker.wordpress.com/category/news/feed/');
$relrss = fetch_rss('http://sourceforge.net/export/rss2_projnews.php?group_id=160319');

$html = '';
foreach ($newsrss->items AS $item)
{
    $item['description'] = str_replace('[...]', "[...] <a href='{$item['link']} class='more'>Read more&hellip;</a>", $item['description']);
    $html .= "\n<h3 class='headline'>{$item['title']}</h3>\n";
    $html .= "<p class='underheadline'>Published {$item['pubdate']} by {$item['dc']['creator']}.  (<a href='{$item['link']}' rel='bookmark'>Permalink</a>)</p>";
    $html .= "<p class='newsstory'>{$item['description']}</p>\n";
}

echo "<pre>".print_r($newsrss, true)."</pre>";
echo "<hr />";

//file_put_contents('news.html', $html);
//echo "news.html updated\n";

echo nl2br(htmlentities($html));


$html = '';

$version = str_replace('Support Incident Tracker (SiT!) ','',$relrss->items[0]['title']);
$version = str_replace(' - Released','',$version);
$reldate = date('d F Y',strtotime($relrss->items[0]['pubdate']));

$html .= "<h3>Latest release: {$version} ({$reldate})</h3>\n";
//file_put_contents('release.html', $html);
//echo "release.html updated\n";

?>