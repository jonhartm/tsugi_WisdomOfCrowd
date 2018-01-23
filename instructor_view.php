<?php
function instructor_view($questions) {
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
}
?>
