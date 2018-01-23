<?php
require_once "../config.php";

use \Tsugi\Core\LTIX;
use \Tsugi\Core\Settings;

$LTI = LTIX::requireData();

// Handle the POST Data
$p = $CFG->dbprefix;
$questions = $LTI->link->getJsonKey('question', '');
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

if ($USER->instructor){

  for ($i=0;$i<count($questions);$i++){
    echo "Question $i: {$questions[$i]['question']} ({$questions[$i]['answer']})<br>";
  }

  echo '<form method="post">';
    echo '<label for="question">Enter a question:&nbsp;</label>';
    echo '<input type="text" name="question" id="question" size=80><br>';
    echo '<label for="answer">Enter the answer:&nbsp;</label>';
    echo '<input type="text" name="answer" id="answer" size=20><br>';
    echo '<input type="submit">';
  echo '</form>';
} else {
  echo '<form method="post">';
    echo '<label for="question">'.$questions[$current_question]['question'].'&nbsp;</label>';
    echo '<input type="text" name="answer" id="answer" size=10><br>';
    echo '<input type="submit">';
  echo '</form>';
}

$OUTPUT->footer();
?>
