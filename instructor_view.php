<?php
function instructor_view($questions) {
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
}
?>
