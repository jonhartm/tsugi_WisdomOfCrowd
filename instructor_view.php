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
  ?>
  <!-- Create the input form for adding a question -->
  <br>
  <form method="post">
    <label for="question_type">Question Type:&nbsp;</label>
    <select name="question_type" disabled>
      <option value="short_answer">Short Answer</option>
      <option value="picture">Upload Picture</option>
    </select>
    <br>
    <label for="question">Enter a question:&nbsp;</label>
    <input type="text" name="question" id="question" size=40>
    <label for="answer">&nbsp;Answer:&nbsp;</label>
    <input type="text" name="answer" id="answer" size=20>
    <label for="hint">&nbsp;Provide a Hint:&nbsp;</label>
    <input type="text" name="hint" id="hint" size=20>
    <br>
    <label for="enforce_case">Case Matters?</label>
    <input type="checkbox" name="enforce_case" id="enforce_case" disabled></input>
    <label for="answer_type">Answer Type</label>
    <select name="answer_type" disabled>
      <option value="number">Numerical</option>
      <option value="text">Short Answer</option>
    </select>
    <br>
    <input type="submit">
  </form>
<?php
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
