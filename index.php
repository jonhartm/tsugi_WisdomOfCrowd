<?php
require_once "../config.php";
include "instructor_view.php";
include "student_view.php";

use \Tsugi\Core\LTIX;
use \Tsugi\Core\Settings;
use \Tsugi\Blob\BlobUtil;

$LTI = LTIX::requireData();

// Handle the POST Data
$p = $CFG->dbprefix;
$questions = $LTI->link->getJsonKey('question', False);
// Check to see if the student has submitted answers already
$has_answered = $LTI->result->getJsonKey('answers', False);

$answers = False;

if ($USER->instructor){
  if (isset($_POST['submit_new_question'])) { // did the user submit a new question?
    $new_question = false;
    if ($_POST['question_type'] == 'short_answer') {
      // Set a short answer question
      $new_question =
        array(
          'question_type'=>$_POST['question_type'],
          'question'=>$_POST['question'],
          'answer'=>$_POST['answer'],
          'hint'=>$_POST['hint'],
          'enforce_case'=>$_POST['enforce_case'],
          'answer_type'=>$_POST['answer_type']
        );
      $_SESSION['success'] = 'Question added';
      header( 'Location: '.addSession('index.php') ) ;
    } else if (isset($_FILES['uploaded_file']) && $_POST['question_type'] == 'picture') {
      // We're uploading a picture
      $file_destination = $_FILES['uploaded_file'];
      $filename = isset($file_destination['name']) ? basename($file_destination['name']) : false;

      // check for errors uploading
      $safety = BlobUtil::validateUpload($file_destination);
      if ($safety !== true) {
        $_SESSION['error'] = "Error: ".$safety.' - filedest: '.$file_destination;
        header( 'Location: '.addSession('index.php') ) ;
        return;
      }

      // check for an error uploading
      $blob_id = BlobUtil::uploadToBlob($file_destination);
      if ($blob_id === false) {
        $_SESSION['error'] = 'Problem storing file in server: '.$filename;
        header( 'Location: '.addSession('index.php') ) ;
        return;
      }

      // if we made it here we're good
      $new_question =
        array(
          'question_type'=>$_POST['question_type'],
          'question'=>$_POST['question'],
          'pic_blob_id'=>$blob_id
        );

      $_SESSION['success'] = 'File uploaded and Question added';
      header( 'Location: '.addSession('index.php') ) ;
    } else if ($_POST['question_type'] == 'multi_text') {
      $new_question =
        array(
          'question_type'=>$_POST['question_type'],
          'question'=>$_POST['question'],
          'max_entries'=>$_POST['max_entries']
        );
      $_SESSION['success'] = 'Question added';
      header( 'Location: '.addSession('index.php') ) ;
    }

    // TODO: this feels clunky - there has to be a better way
    // Append the questions to the array so we can save them again
    if (!$questions) {
      $questions = array($new_question); // $questions was blank, so start a new array with this question
    } else {
      array_push($questions, $new_question); // otherwise append this array on the questions array
    }
    $LTI->link->setJsonKey('question', $questions);

  } else if (isset($_POST['clear_student_data']) || isset($_POST['clear_all_data'])) { // We are clearing some or all of the data
    // Clear student answers
    $PDOX->queryDie("UPDATE {$p}lti_result SET json=NULL WHERE link_id={$LINK->id}");
    if (isset($_POST['clear_all_data'])){ // Clear all the question data as well
      // Clear the blob data
      $blobs = array();
      foreach ($questions as $key => $value) {
        if ($value['question_type'] == 'picture') {
          array_push($blobs, $value['pic_blob_id']);
        }
      }

      $PDOX->queryDie("DELETE FROM {$p}blob_file WHERE file_id IN (:BLOBS)",
        array(':BLOBS'=>implode(',',$blobs)));
      // Clear question Json
      $LTI->link->SetJson(NULL);
    }
    $_SESSION['success'] = 'Data cleared.';
    header( 'Location: '.addSession('index.php') ) ;
  }
  // Load the answers so far
  $answers = $PDOX->allRowsDie("SELECT json FROM {$p}lti_result WHERE link_id={$LINK->id}");

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
echo '<link rel="stylesheet" type="text/css" href="css/style.css">';
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

$OUTPUT->footerStart();
echo '<script type="text/javascript" src="scripts.js"></script>';
$OUTPUT->footerEnd();
?>
