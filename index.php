<?php
require_once "../config.php";
include "instructor_view.php";
include "student_view.php";

use \Tsugi\Core\LTIX;
use \Tsugi\Core\Settings;

$LTI = LTIX::requireData();

// Handle the POST Data
$p = $CFG->dbprefix;
$questions = $LTI->link->getJsonKey('question', False);
$current_question = $LTI->link->getJsonKey('cur_question', '1');

if ($USER->instructor){
  if ((isset($_POST['question'])) && isset($_POST['answer'])) {
    $new_question = array('question'=>$_POST['question'], 'answer'=>$_POST['answer']);
    array_push($questions, $new_question);
    $LTI->link->setJsonKey('question', $questions);
    $_SESSION['success'] = 'Question added';
    header( 'Location: '.addSession('index.php') ) ;
  }
} else {
  // Student Response
  if (isset($_POST['answer'])) {
    $PDOX->queryDie("INSERT INTO {$p}wisdomOfCrowdAnswers
            (link_id, user_id, question_id, answer_text)
            VALUES ( :LI, :UI, $current_question, {$_POST['answer']} )",
            array(
                ':LI' => $LINK->id,
                ':UI' => $USER->id
            )
        );
    $_SESSION['success'] = 'Answer Submitted';
    header( 'Location: '.addSession('index.php') ) ;
  }
}

// Create the view
$OUTPUT->header();
$OUTPUT->bodyStart();
$OUTPUT->topNav();
$OUTPUT->flashMessages();

// DEBUG
// echo '<pre>';
// print_r($questions);
// echo '</pre>';

if ($USER->instructor){
  instructor_view($questions);
} else {
  student_view($questions);
}

$OUTPUT->footer();
?>
