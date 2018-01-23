<?php
function student_view($questions){
  if ($questions){
    echo '<form method="post">';
    for ($i=0;$i<count($questions);$i++){
      echo '<label for="answer'.$i.'">'.$questions[$i]['question'].'&nbsp;</label>';
      echo '<input type="text" name="answer'.$i.'" id="answer'.$i.'" size=10><br>';
    }
      echo '<input type="submit">';
    echo '</form>';
  } else {
    echo 'No questions posted at this time.';
  }
}
 ?>
