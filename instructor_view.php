<?php
use \Tsugi\Blob\BlobUtil;

function instructor_view($questions, $answers) {
  if ($questions){
    echo 'Questions entered:<br>';
    for ($i=0;$i<count($questions);$i++){
      if ($questions[$i]['question_type'] == 'short_answer'){
        echo "Question $i: {$questions[$i]['question']} ({$questions[$i]['answer']})<br>";
      } else if ($questions[$i]['question_type'] == 'picture'){
        echo "Question $i: {$questions[$i]['question']}";
        $serve = BlobUtil::getAccessUrlForBlob($questions[$i]['pic_blob_id']);
        echo '<img src="'.addSession($serve).'" height="40"><br>';
      }
    }
  } else {
    echo 'No Questions currently posted.<br><br>';
  }

create_question_input_form();

if ($questions) {
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
  echo '<br>';
  echo '<form method="post">';
    echo '<input type="submit" value="Clear Student Data" name="clear_student_data">';
    echo '<input type="submit" value="Clear All Data" name="clear_all_data">';
  echo '</form>';
}

// function to display different versions of the form based on the dropdown selection
function create_question_input_form($question_type = "short_answer") {
?>
  <script>
  function changeForm() {
    var form_option = document.getElementById("question_type").value;
    var htmlString = '';
    if (form_option=="short_answer") {
      htmlString += '<label for="question">Enter a question:&nbsp;</label>';
      htmlString += '<input type="text" name="question" id="question" size=40>';
      htmlString += '<label for="answer">&nbsp;Answer:&nbsp;</label>';
      htmlString += '<input type="text" name="answer" id="answer" size=20>';
      htmlString += '<label for="hint">&nbsp;Provide a Hint:&nbsp;</label>';
      htmlString += '<input type="text" name="hint" id="hint" size=20>';
      htmlString += '<br>';
      htmlString += '<label for="enforce_case">Case Matters?</label>';
      htmlString += '<input type="checkbox" name="enforce_case" id="enforce_case"></input>';
      htmlString += '<label for="answer_type">Answer Type</label>';
      htmlString += '<select name="answer_type">';
        htmlString += '<option value="number">Numerical</option>';
        htmlString += '<option value="text">Short Answer</option>';
      htmlString += '</select>';
    } else if (form_option=="picture") {
      htmlString += '<label for="question">Enter a prompt:&nbsp;</label>';
      htmlString += '<input type="text" name="question" id="question" size=40>';
      htmlString += '<br>';
      htmlString += 'Upload File: (max <?php echo(BlobUtil::maxUpload());?>MB)<input name="uploaded_file" type="file">';
    } else if (form_option=="multi_text") {
      htmlString += 'not ready';
    }
    htmlString += '<br><input type="submit" name="submit_new_question">'; // tack a submit button to the end of any form
  	document.getElementById("form_display").innerHTML = htmlString;   // replaces the html in the div "form_display"
  }
  </script>

  <form enctype="multipart/form-data" method="post">
  Add a new question:
  <select id="question_type" name="question_type" onchange="changeForm()">
    <option value="">Select a Question Type</option>
    <option value="short_answer">Short Answer</option>
    <option value="picture">Upload Picture</option>
    <option value="multi_text">Multiple Answer</option>
  </select>
  <br><br>
  <div id="form_display">
  </div>
  </form>
<?php
}

function sort_answers($questions, $answers) {
  $answer_counts = array();
  // Get each question in turn and prepare an array for the submitted answers
  for ($q=0; $q < count($questions); $q++) {
    $answer_counts['q'.$q] = array(
      'question' => $questions[$q]['question'],
      'answer' => isset($questions[$q]['answer']) ? $questions[$q]['answer'] : false,
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
