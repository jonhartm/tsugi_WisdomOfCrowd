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

create_question_input_form();

echo '<br><br>';
echo '<h3>Responses so far:</h3>';
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

function create_question_input_form() {
  // Create the input form for adding a question
  echo '<br>';
  echo '<form method="post">';
    echo '<label for="question_type">Question Type:&nbsp;</label>';
    echo '<select name="question_type">';
      echo '<option value="short_answer">Short Answer</option>';
      echo '<option value="picture">Upload Picture</option>';
      echo '<option value="multi_text">Multiple Answer</option>';
      echo '</select>';
    echo '<br>';
    echo '<label for="question">Enter a question:&nbsp;</label>';
    echo '<input type="text" name="question" id="question" size=40>';
    echo '<label for="answer">&nbsp;Answer:&nbsp;</label>';
    echo '<input type="text" name="answer" id="answer" size=20>';
    echo '<label for="hint">&nbsp;Provide a Hint:&nbsp;</label>';
    echo '<input type="text" name="hint" id="hint" size=20>';
    echo '<br>';
    echo '<label for="enforce_case">Case Matters?</label>';
    echo '<input type="checkbox" name="enforce_case" id="enforce_case"></input>';
    echo '<label for="answer_type">Answer Type</label>';
    echo '<select name="answer_type">';
      echo '<option value="number">Numerical</option>';
      echo '<option value="text">Short Answer</option>';
    echo '</select>';
    echo '<br>';
    echo '<input type="submit">';
  echo '</form>';
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
      $json_result = json_decode($a_value['json'], True);
      if (!empty($json_result)) {
        $answer = explode(",",$json_result['answers'])[$q];
        if (isset($answer_counts['q'.$q]['responses'][$answer])){
          $answer_counts['q'.$q]['responses'][$answer] += 1;
        } else {
          $answer_counts['q'.$q]['responses'][$answer] = 1;
        }
      }
    }
  }
  return $answer_counts;
}
?>
