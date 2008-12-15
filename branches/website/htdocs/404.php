<?php include 'top.inc.php';
$title = str_replace("/", "", $_SERVER['REQUEST_URI']);
?>
<h2>Error</h2>
<p>Oops! It seems we can't find that file.</p>
<p>We've just moved to a new wiki so if you were accessing a wiki link, you can
click <a href='http://apps.sourceforge.net/mediawiki/sitracker/index.php?title=<?php echo $title?>'>
here</a> to find the link at the new wiki.</p>
<p>If that doesn't work, please use the links at the top to find your way around.</p>
<?php include 'bottom.inc.php'; ?>