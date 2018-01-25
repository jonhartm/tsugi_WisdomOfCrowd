<?php
function instructor_view($questions, $answers) {
  if ($questions){
    echo 'Questions entered:<br>';
    for ($i=0;$i<count($questions);$i++){
      echo "Question $i: {$questions[$i]['question']} ({$questions[$i]['answer']})<br>";
    }
  } else {
    echo 'No Questions currently posted.';
  }
  echo '<form method="post">';
    echo '<label for="question">Enter a question:&nbsp;</label>';
    echo '<input type="text" name="question" id="question" size=60>';
    echo '<label for="answer">&nbsp;Answer:&nbsp;</label>';
    echo '<input type="text" name="answer" id="answer" size=20><br>';
    echo '<input type="submit">';
  echo '</form>';

  echo '<pre>';
  print_r(sort_answers($questions, $answers));
  echo '</pre>';
}

function sort_answers($questions, $answers) {
  $answer_counts = array();
  // Get each question in turn and prepare an array for the submitted answers
  for ($q=0; $q < count($questions); $q++) {
    $answer_counts['q'.$q] = array(
      'question' => $questions[$q]['question'],
      'answer' => $questions[$q]['answer'],
      'responses' => array()
    );
    foreach ($answers as $a_key => $a_value) {
      $answer = explode(",",$a_value['answer_text'])[$q];
      if (isset($answer_counts['q'.$q]['responses'][$answer])){
        $answer_counts['q'.$q]['responses'][$answer] += 1;
      } else {
        $answer_counts['q'.$q]['responses'][$answer] = 1;
      }
    }
  }
  return $answer_counts;
}
?>
