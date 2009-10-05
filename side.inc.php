<div id='side'>
 <img src='http://sitracker.sourceforge.net/newlogo.png' alt='SiT logo' />
<?php
include_once 'magpie/rss_fetch.inc';

echo "<h2><span style='float:right;margin-top:6px;'><a href='{$relfeedurl}' title='Releases feed (RSS)'>";
echo "<img src='feed-icon-12x12.png' width='12' height='12' alt='' /></a></span>Releases</h2>";


if (!empty($relfeedurl)) $relrss = fetch_rss($relfeedurl);
if (is_object($relrss))
{
    $version = str_replace('Support Incident Tracker (SiT!) ','',$relrss->items[0]['title']);
    $version = str_replace(' - Released','',$version);
    $reldate = date('d F Y',strtotime($relrss->items[0]['pubdate']));

    //$html .= "<h3>Current release: {$version} ({$reldate})</h3>\n";
    // FIXME release version number hardcoded while sf news is broken - 5 oct 09 INL
    $html .= "<h3>Current release: v3.51rc3 (17 September 2009)</h3>\n";

    echo $html;
}
// <h3>Current release: v3.41 (17 December 2008)</h3>
?>
<a href='/wiki/Download'>
 <img class='left' src='http://sitracker.sourceforge.net/download.png'  style='padding-top:5px;' alt='' /><br />
 <strong>Download</strong><br />Grab your copy now!</a>
 <p>See the <a href='/wiki/ReleaseNotes'>release notes</a> for details.</p>

<!-- Ohloh, I use it button -->
<script type="text/javascript" src="http://www.ohloh.net/p/15567/widgets/project_users_logo.js"></script>

<br />

<?php
if (!empty($microblogfeedurl))
{
    echo "<h2><span style='float:right;margin-top:6px;'><a href='{$microblogfeedurl}' title='Identi.ca (RSS)'>";
    echo "<img src='feed-icon-12x12.png' width='12' height='12' alt='' /></a></span>";
    echo "<a href='http://identi.ca/'><img src='identicaicon.png' width='16' height='16' alt='' /></a> ";
    echo "<a href='{$microblogurl}' title='Identi.ca Microblogging'>Identi.ca</a></h2>";

    $mbrss = fetch_rss($microblogfeedurl);
    // Fixes the encoding to uf8
    function fixEncoding($in_str)
    {
      $cur_encoding = mb_detect_encoding($in_str) ;
      if($cur_encoding == "UTF-8" && mb_check_encoding($in_str,"UTF-8"))
        return $in_str;
      else
        return utf8_encode($in_str);
    } // fixEncoding 
  
    $count = 1;
    foreach ($mbrss->items as $post)
    {
        if ($count <= 3)
        {
            $post['title'] = fixEncoding($post['title']);
            $post['dc']['date'] = str_replace("T", " @ ", $post['dc']['date']);
            $post['dc']['date'] = str_replace("+00:00", "", $post['dc']['date']);
            $post['title'] = str_replace("!sit", "<a href='http://identi.ca/group/sit'>!sit</a>", $post['title']);
            $post['title'] = preg_replace("/^(.*?):/s", "<a href='http://identi.ca/$1'>$1</a>:", $post['title']);
            $post['title'] = preg_replace("!([\n\t ]+)(http[s]?:/{2}[\w\.]{2,}[/\w\-\.\?\&\=\#\$\%|;|\[|\]~:]*)!e", "'\\1<a href=\"\\2\" title=\"\\2\">\\2</a>'", $post['title']);
            echo "<p>{$post['title']}<br />";
            echo "<small><a href='{$post['link']}'>{$post['dc']['date']}</a></small></p>";
            $count++;
        }
    }
}
?>

<div style='border-top: 1px solid #203894; border-bottom: 1px solid #203894; margin-top: 3em; padding-top: 1em; padding-bottom: 2em;'>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="8254393">
<input type="image" src="x-click-butcc-donate.gif" border="0" style="float: left; padding-right: 0.5em;" name="submit" alt="PayPal - The safer, easier way to pay online.">
<img alt="" border="0" src="pixel.gif" width="1" height="1"><span style='color: #333;'>Your donation will help us to build a better application. Thank you.</span>
</form>
</div>

<div id='sociallinks' style=''>
<a href="http://delicious.com/save" onclick="window.open('http://delicious.com/save?v=5&amp;noui&amp;jump=close&amp;url='+encodeURIComponent(location.href)+'&amp;title='+encodeURIComponent(document.title), 'delicious','toolbar=no,width=550,height=550'); return false;"><img src='delicious.png' alt='' title='Bookmark with del.icio.us'/></a>
<a href="http://www.google.com/bookmarks/mark?op=edit&amp;bkmk=http%3A%2F%2Fsitracker.org&amp;title=Support%20Incident%20Tracker"><img src='googlebookmark.png' alt='' title='Google Bookmarks' /></a>
<a href="http://www.facebook.com/share.php?u=http%3A%2F%2Fsitracker.org"><img src='facebook.png' alt='' title='Bookmark via Facebook' /></a>
<a href="http://www.netvibes.com/share?title=Support%20Incident%20Tracker&amp;url=http%3A%2F%2Fsitracker.org"><img src='netvibes.png' alt='' title='Bookmark via Netvibes' /></a>
<a href="http://www.stumbleupon.com/submit?url=http%3A%2F%2Fsitracker.org&amp;title=Support%20Incident%20Tracker"><img src='stumbleupon.png' alt='' title='Share via StumbleUpon' /></a>
<a href='http://identi.ca/notice/new?status_textarea=http%3A%2F%2Fsitracker.org%20!sit'><img src='identica.png' alt='' title='Share via Identi.ca' /></a>
<a href="http://twitter.com/home?status=Support%20Incident%20Tracker%20http%3A%2F%2Fsitracker.org"><img src='twitter.png' alt='' title='Share via Twitter' /></a>
</div>


</div>

