<?php
$title = str_replace("/", "", $_SERVER['REQUEST_URI']);
if (strpos($title, '.') === FALSE)
{
    header ('HTTP/1.1 301 Moved Permanently');
    header ("Location: http://sitracker.org/wiki/{$title}");
    exit;
}
else
{
    include 'top.inc.php';
    ?>
    <h2>Error</h2>
    <p>Oops! It seems we can't find that file.</p>
    <p>We've just moved to a new wiki so if you were accessing a wiki link, you can
    <a href='http://sitracker.org/wiki/<?php echo $title?>'>click
    here to find the link at the new wiki</a>.</p>
    <p>If that doesn't work, please use the links at the top to find your way around.</p>
    <?php include 'bottom.inc.php';
}