<?php
require_once "../config.php";

use \Tsugi\Core\LTIX;
use \Tsugi\Core\Settings;

$LTI = LTIX::requireData();

// Handle the POST Data
$p = $CFG->dbprefix;

// Create the view
$OUTPUT->header();
$OUTPUT->bodyStart();
$OUTPUT->topNav();
$OUTPUT->flashMessages();

// Body Goes here
echo "Hello";

$OUTPUT->footer();
?>
