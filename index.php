<?php
define('MAGPIE_CACHE_AGE', 15*60); // 15 minutes
$newsfeedurl = 'http://sitracker.wordpress.com/category/news/feed/';
// NUmber of news items to show
$newsitems = 4;

$relfeedurl = 'http://sourceforge.net/export/rss2_projnews.php?group_id=160319';

$microblogurl = 'http://identi.ca/group/sit';
$microblogfeedurl = 'http://identi.ca/group/sit/rss';

include 'top.inc.php';
include_once 'magpie/rss_fetch.inc';
?>
<h2>Welcome</h2>
<p>Support Incident Tracker (or SiT!) is a <a href="http://www.gnu.org/philosophy/free-sw.html" target="_blank">Free Software</a>/Open Source (<a href="http://www.gnu.org/licenses/gpl-2.0.html" target="_blank" rel="license">GPL</a>) web based application which uses <a href="http://www.php.net/" target="_blank">PHP</a> and <a href="http://www.mysql.com/" target="_blank">MySQL</a> for tracking technical support calls/emails (also commonly known as a 'Help Desk' or 'Support Ticket System'). Manage contacts, sites, technical support contracts and support incidents in one place. Send emails directly from SiT!, attach files and record every communication in the incident log. SiT is aware of Service Level Agreements and incidents are flagged if they stray outside of them.</p>
<img class='pretty' src='http://sitracker.sourceforge.net/screenshots.jpg' width='300' height='196' alt='SiT Screenshot' />

<?php
echo "<h2><span style='float:right;margin-top:6px;'><a href='{$newsfeedurl}' title='News feed (RSS)'>";
echo "<img src='feed-icon-12x12.png' width='12' height='12' alt='' /></a></span>News</h2>";
$newsrss = @fetch_rss($newsfeedurl);
if (is_object($newsrss))
{
    $html = '';
    $itemcount = 1;
    foreach ($newsrss->items AS $item)
    {
        $item['description'] = str_replace('[...]', "[...] <a href='{$item['link']}' class='more'>Read more&hellip;</a>", $item['description']);
        $html .= "\n<h3 class='headline'>{$item['title']}</h3>\n";
        $html .= "<p class='underheadline'>Published {$item['pubdate']} by {$item['dc']['creator']}.  (<a href='{$item['link']}' rel='bookmark'>Permalink</a>)</p>";
        $html .= "<p class='newsstory'>{$item['description']}</p>\n";
        $itemcount++;
        if ($itemcount > $newsitems) break;
    }
    echo utf8_encode($html);
    echo "<p><a href='http://sitracker.wordpress.com/category/news/'>&lt; Older news</a></p>";
}
else
{
    echo "<p>Temporarily unavailable, check the <a href='http://sitracker.wordpress.com'>blog</a> in the meantime.</p>";
}
?>
<?php include 'bottom.inc.php'; ?>