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

  // Show the current answers
  echo '<div id="accordion">';
  foreach (sort_answers($questions, $answers) as $key => $value) {
    echo '<h3>Q:'.$value['question'].' (A: '.$value['answer'].')</h3>';
    echo '<p>';
    foreach ($value['responses'] as $k_res => $v_res) {
          echo 'Response: '.$k_res.' - Count('.$v_res.')<br>';
    }
    echo '</p>';
  }
  echo '</div>';
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
