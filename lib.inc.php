<?php

/**
  * Produces HTML for a percentage indicator
  * @author Ivan Lucas
  * @param int $percent. Number between 0 and 100
  * @returns string HTML
  * @note Half-inched from SiT! 3.51
*/
function percent_bar($percent)
{
    if ($percent == '') $percent = 0;
    if ($percent < 0) $percent = 0;
    if ($percent > 100) $percent = 100;
    // #B4D6B4;
    $html = "<div class='percentcontainer'>";
    $html .= "<div class='percentbar' style='width: {$percent}%;'>  <span class='percentbarpercent'>{$percent}&#037;</span>";
    $html .= "</div></div>\n";
    return $html;
}

function translation_percent_bar($label, $percent)
{
    $html = "<div class='languagepercent'>";
    $html .= "<span class='languagepercentlabel'>{$label}</span>";
    $html .= percent_bar($percent);
    $html .= "</div>";
    return $html;
}


?>