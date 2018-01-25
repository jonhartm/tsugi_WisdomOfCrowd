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
// Check to see if the student has submitted answers already
$has_answered = $LTI->result->getJsonKey('answers', False);

$answers = False;

if ($USER->instructor){
  if ((isset($_POST['question'])) && isset($_POST['answer'])) {
    $new_question = array('question'=>$_POST['question'], 'answer'=>$_POST['answer']);
    // TODO: this feels clunky - there has to be a better way
    if (!$questions) {
      $questions = array($new_question); // $questions was blank, so start a new array with this question
    } else {
      array_push($questions, $new_question); // otherwise append this array on the questions array
    }
    $LTI->link->setJsonKey('question', $questions);
    $_SESSION['success'] = 'Question added';
    header( 'Location: '.addSession('index.php') ) ;
  }
  // Load the answers so far
  $answers = $PDOX->allRowsDie("SELECT json FROM {$p}lti_result WHERE link_id=9");

} else {
  // Student Response
  if (isset($_POST['answer0'])) {
    $LTI->result->setJsonKey('answers', implode(",", array_slice($_POST,1)));
    $_SESSION['success'] = 'Answer Submitted';
    header( 'Location: '.addSession('index.php') ) ;
  }
}

// Create the view
$OUTPUT->header();
?>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
$( function() {
    $( "#accordion" ).accordion({
      collapsible: true
    });
  } );
</script>
<?php
$OUTPUT->bodyStart();
$OUTPUT->topNav();
$OUTPUT->flashMessages();

// DEBUG
// echo '<pre>';
// print_r($has_answered);
// echo '</pre>';

if ($USER->instructor){
  instructor_view($questions, $answers);
} else {
  if (!$has_answered){
    student_view($questions);
  } else {
    echo 'Your answers have been submitted';
  }
}

$OUTPUT->footer();
?>
