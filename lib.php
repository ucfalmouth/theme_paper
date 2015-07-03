<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Theme paper lib.
 *
 * @package    theme_paper
 * @copyright  2014 Bas Brands
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Copied across from bootstrap/lib.php and function name altered where called in layouts
function paper_bootstrap_grid($pre, $post) {

    if ($pre && $post) {
        // pre & post
        $spre = 2; $smain = 6; $spost = 4;
        $lpre = 2; $lmain = 6; $lpost = 4;
        $regions = array('content' => "col-sm-$smain col-sm-push-$spre col-lg-$lmain col-lg-push-$lpre");
        $regions['pre'] = "col-sm-$spre col-sm-pull-$smain col-lg-$lpre col-lg-pull-$lmain";
        $regions['post'] = "col-sm-$spost col-lg-$lpost";
    } else if ($pre && !$post) {
        // pre only
        $spre = 4; $smain = 8; 
        $lpre = 4; $lmain = 8;
        $regions = array('content' => "col-sm-$smain col-sm-push-$spre col-lg-$lmain col-lg-push-$spre");
        $regions['pre'] = "col-sm-$spre col-sm-pull-$smain col-lg-$lpre col-lg-pull-$lmain";
        $regions['post'] = 'empty';
    } else if (!$pre && $post) {
        // post only
        $smain = 8; $spost = 4;
        // $mmain = 6; $mpost = 4;
        $lmain = 8; $lpost = 4;
        $regions = array('content' => "col-sm-$smain col-lg-$lmain");
        $regions['pre'] = 'empty';
        $regions['post'] = "col-sm-$spost col-lg-$lpost";
    } else if (!$pre && !$post) {
        $regions = array('content' => 'col-md-12');
        $regions['pre'] = 'empty';
        $regions['post'] = 'empty';
    }

    // if ('rtl' === get_string('thisdirection', 'langconfig')) {
    // }

    return $regions;
}


// function paper_bootstrap_grid($hassidepre, $hassidepost) {

//     if ($hassidepre && $hassidepost) {
//         $regions = array('content' => 'col-sm-6 col-sm-push-3 col-lg-8 col-lg-push-2');
//         $regions['pre'] = 'col-sm-3 col-sm-pull-6 col-lg-2 col-lg-pull-8';
//         $regions['post'] = 'col-sm-3 col-lg-2';
//     } else if ($hassidepre && !$hassidepost) {
//         $regions = array('content' => 'col-sm-9 col-sm-push-3 col-lg-10 col-lg-push-2');
//         $regions['pre'] = 'col-sm-3 col-sm-pull-9 col-lg-2 col-lg-pull-10';
//         $regions['post'] = 'emtpy';
//     } else if (!$hassidepre && $hassidepost) {
//         $regions = array('content' => 'col-sm-9 col-lg-10');
//         $regions['pre'] = 'empty';
//         $regions['post'] = 'col-sm-3 col-lg-2';
//     } else if (!$hassidepre && !$hassidepost) {
//         $regions = array('content' => 'col-md-12');
//         $regions['pre'] = 'empty';
//         $regions['post'] = 'empty';
//     }

//     return $regions;
// }

function theme_paper_process_css($css, $theme) {

    // Set the background image for the logo.
    $logo = $theme->setting_file_url('logo', 'logo');
    $css = theme_paper_set_logo($css, $logo);

    // Set custom CSS.
    if (!empty($theme->settings->customcss)) {
        $customcss = $theme->settings->customcss;
    } else {
        $customcss = null;
    }
    $css = theme_paper_set_customcss($css, $customcss);

    return $css;
}


function theme_paper_set_logo($css, $logo) {
    $logotag = '[[setting:logo]]';
    $logoheight = '[[logoheight]]';
    $logowidth = '[[logowidth]]';
    $logodisplay = '[[logodisplay]]';
    $width = '0';
    $height = '0';
    $display = 'none';
    $replacement = $logo;
    if (is_null($replacement)) {
        $replacement = '';
    } else {
        $dimensions = getimagesize('http:'.$logo);
        $width = $dimensions[0] . 'px';
        $height = $dimensions[1] . 'px';
        $display = 'block';
    }
    $css = str_replace($logotag, $replacement, $css);
    $css = str_replace($logoheight, $height, $css);
    $css = str_replace($logowidth, $width, $css);
    $css = str_replace($logodisplay, $display, $css);

    return $css;
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function theme_paper_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    if ($context->contextlevel == CONTEXT_SYSTEM && ($filearea === 'logo')) {
        $theme = theme_config::load('paper');
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    } else {
        send_file_not_found();
    }
}

/**
 * Adds any custom CSS to the CSS before it is cached.
 *
 * @param string $css The original CSS.
 * @param string $customcss The custom CSS to add.
 * @return string The CSS which now contains our custom CSS.
 */
function theme_paper_set_customcss($css, $customcss) {
    $tag = '[[setting:customcss]]';
    $replacement = $customcss;
    if (is_null($replacement)) {
        $replacement = '';
    }

    $css = str_replace($tag, $replacement, $css);

    return $css;
}
