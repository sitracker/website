<div id='side'>
 <img src='http://static.sitracker.org/newlogo.png' alt='SiT logo' />
<?php
include_once 'magpie/rss_fetch.inc';

echo "<h2><span style='float:right;margin-top:6px;'><a href='{$relfeedurl}' title='Releases feed (RSS)'>";
echo "<img src='http://static.sitracker.org/feed-icon-12x12.png' width='12' height='12' alt='' /></a></span>Releases</h2>";


if (!empty($relfeedurl)) $relrss = fetch_rss($relfeedurl);
if (is_object($relrss))
{
    $version = str_replace('Support Incident Tracker (SiT!) ','',$relrss->items[0]['title']);
    $version = str_replace('Support Incident Tracker','',$relrss->items[0]['title']);
    $version = str_replace(' - Released','',$version);
    $reldate = date('d F Y',strtotime($relrss->items[0]['pubdate']));

    $html .= "<h3>Current release: {$version} ({$reldate})</h3>\n";
    // FIXME release version number hardcoded while sf news is broken - 5 oct 09 INL
    //$html .= "<h3>Current release: v3.50 (15 October 2009)</h3>\n";

    echo $html;
}
// <h3>Current release: v3.41 (17 December 2008)</h3>
?>
<a href='/wiki/Download'>
 <img class='left' src='http://static.sitracker.org/download.png'  style='padding-top:5px;' alt='' /><br />
 <strong>Download</strong><br />Grab your copy now!</a>

<!-- Ohloh, I use it button -->
<div id='ohlohbutton'><script type="text/javascript" src="http://www.ohloh.net/p/15567/widgets/project_users_logo.js"></script></div>

 <p>See the <a href='/wiki/ReleaseNotes'>release notes</a> for details.</p>


<div style='border-top: 1px solid #CCC;  border-bottom: 1px solid #CCC;  margin-top: 1em; padding-top: 1em; padding-bottom: 2em;'>
<div id='flattr' style='margin-top: 20px; margin-right: 3px; clear:both; float:right;'><a class="FlattrButton" style="display:none;" rev="flattr;button:compact;" href="http://sitracker.org"></a></div>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick"/>
<input type="hidden" name="hosted_button_id" value="8254393"/>
<input type="image" src="x-click-butcc-donate.gif" style="float: left; padding-right: 0.5em;border:0px;" name="submit" alt="PayPal - The safer, easier way to pay online." title="Thank you for your kind donation!"/>
<img alt="" border="0" src="pixel.gif" width="1" height="1"/><span style='color: #333;'>Your donation will help us to build a better application. Thank you.</span>
</form>
<span style='margin-top: 15px; clear:both;'></span>
</div>



<br />

<?php
if (!empty($microblogfeedurl))
{
    echo "<h2><span style='float:right;margin-top:6px;'><a href='{$microblogfeedurl}' title='Identi.ca (RSS)'>";
    echo "<img src='http://static.sitracker.org/feed-icon-12x12.png' width='12' height='12' alt='' /></a></span>";
    echo "<a href='http://identi.ca/'><img src='http://static.sitracker.org/identicaicon.png' width='16' height='16' alt='' /></a> ";
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
echo "<h2>Translation Status</h2>";

echo translation_percent_bar('English (British), en-GB', 100);
echo translation_percent_bar('English (US), en-US', 99.50);
echo translation_percent_bar('Cymraeg, cy-GB', 99.50);
echo translation_percent_bar('Dansk, da-DK', 100);
echo translation_percent_bar('Français, fr-FR', 100);
echo translation_percent_bar('Deutsch, de-DE', 99.50);
echo translation_percent_bar('Español (Mexicano), es-MX', 99.50);
echo translation_percent_bar('Português, pt-PT', 99.50);
echo translation_percent_bar('Русский, ru-RU', 99.50);
echo translation_percent_bar('Español, es-ES', 99.50);
echo translation_percent_bar('Norsk Bokmål, nb-NO', 99.99);
echo translation_percent_bar('Nederlands, nl-NL', 98.82);
echo translation_percent_bar('български, bg-BG', 96.58);
echo translation_percent_bar('Polski, pl-PL', 90.47);
echo translation_percent_bar('فارسی, fa-ir', 90.19);
echo translation_percent_bar('Italiano , it-IT', 87);
echo translation_percent_bar('Română, ro-RO', 76.01);
echo translation_percent_bar('Português brasileiro, pt-BR', 71.08);
echo translation_percent_bar('Ελληνικά, el-GR', 52.75);
echo translation_percent_bar('Slovenščina, sl-SL', 51.79);
echo translation_percent_bar('繁體中文, zh-TW', 51.23);
echo translation_percent_bar('简体中文, zh-CN', 51.19);
echo translation_percent_bar('Español (Colombia), es-CO', 47.42);
echo translation_percent_bar('Català, ca-ES', 38.71);
echo translation_percent_bar('日本語, ja-JP', 31.21);
echo translation_percent_bar('العربية, ar', 29.99);
echo translation_percent_bar('Lietuvių, lt-LT', 23.09);
echo translation_percent_bar('Afrikaans, af', 18.78);
echo translation_percent_bar('Svenska, sv-SE', 7.34);


echo "<p><a href=\"http://sitracker.org/wiki/Translation\">Please help us to translate SiT!</a></p>";
//echo "</div>";

echo "<div style='padding-bottom: .5em;'>";
echo "<h2>Share this</h2>";
?>
<div id='sociallinks' style=''>
<a href="http://delicious.com/save" onclick="window.open('http://delicious.com/save?v=5&amp;noui&amp;jump=close&amp;url='+encodeURIComponent(location.href)+'&amp;title='+encodeURIComponent(document.title), 'delicious','toolbar=no,width=550,height=550'); return false;"><img src='http://static.sitracker.org/delicious.png' alt='' title='Bookmark with del.icio.us'/></a>
<a href="http://www.google.com/bookmarks/mark?op=edit&amp;bkmk=http%3A%2F%2Fsitracker.org&amp;title=Support%20Incident%20Tracker"><img src='http://static.sitracker.org/googlebookmark.png' alt='' title='Google Bookmarks' /></a>
<a href="http://www.facebook.com/share.php?u=http%3A%2F%2Fsitracker.org"><img src='http://static.sitracker.org/facebook.png' alt='' title='Bookmark via Facebook' /></a>
<a href="http://www.netvibes.com/share?title=Support%20Incident%20Tracker&amp;url=http%3A%2F%2Fsitracker.org"><img src='http://static.sitracker.org/netvibes.png' alt='' title='Bookmark via Netvibes' /></a>
<a href="http://www.stumbleupon.com/submit?url=http%3A%2F%2Fsitracker.org&amp;title=Support%20Incident%20Tracker"><img src='http://static.sitracker.org/stumbleupon.png' alt='' title='Share via StumbleUpon' /></a>
<a href='http://identi.ca/notice/new?status_textarea=http%3A%2F%2Fsitracker.org%20!sit'><img src='http://static.sitracker.org/identica.png' alt='' title='Share via Identi.ca' /></a>
<a href="http://twitter.com/home?status=Support%20Incident%20Tracker%20http%3A%2F%2Fsitracker.org"><img src='http://static.sitracker.org/twitter.png' alt='' title='Share via Twitter' /></a>
</div>
</div>


</div>

