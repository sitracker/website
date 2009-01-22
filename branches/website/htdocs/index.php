<?php
include 'top.inc.php';
?>
<h2>Welcome</h2>
<p>Support Incident Tracker (or SiT!) is a <a href="http://www.gnu.org/philosophy/free-sw.html" target="_blank">Free Software</a>/Open Source (<a href="http://www.opensource.org/licenses/gpl-license.php" target="_blank">GPL</a>) web based application which uses <a href="http://www.php.net/" target="_blank">PHP</a> and <a href="http://www.mysql.com/" target="_blank">MySQL</a> for tracking technical support calls/emails (also commonly known as a 'Help Desk' or 'Support Ticket System'). Manage contacts, sites, technical support contracts and support incidents in one place. Send emails directly from SiT!, attach files and record every communication in the incident log. SiT is aware of Service Level Agreements and incidents are flagged if they stray outside of them.</p>
<img class='pretty' src='http://sitracker.sourceforge.net/screenshots.jpg' alt='SiT Screenshot' />
<h2>News</h2>
<?php
if (file_exists('news.html'))
{
    echo utf8_encode(file_get_contents('news.html'));
}
else
{
    echo "<p>Temporarily unavailable, check the <a href='http://sitracker.wordpress.com'>blog</a> in the meantime.</p>";
}
?>
<?php include 'bottom.inc.php'; ?>