<?php
use \Tsugi\Blob\BlobUtil;

function student_view($questions){
  if ($questions){
    echo '<form method="post">';
    for ($i=0;$i<count($questions);$i++){
      if ($questions[$i]['question_type'] == 'short_answer') {
      echo '<label for="answer'.$i.'">'.$questions[$i]['question'].'&nbsp;</label>';
      echo '<input type="text" name="answer'.$i.'" id="answer'.$i.'" size=10><br>';
    } elseif ($questions[$i]['question_type'] == 'picture') {
      $serve = BlobUtil::getAccessUrlForBlob($questions[$i]['pic_blob_id']);
      echo '<img src="'.addSession($serve).'" height="400"><br>';
      echo '<label for="answer'.$i.'">'.$questions[$i]['question'].'&nbsp;</label>';
      echo '<input type="text" name="answer'.$i.'" id="answer'.$i.'" size=10><br>';
    } elseif ($questions[$i]['question_type'] == 'multi_text') {
      echo '<label for="multi_answer'.$i.'">'.$questions[$i]['question'].'</label><br>';
      echo '<div id="multi_answer'.$i.'">';
      echo '<input type="text" name="multi_answer'.$i.'" id="answer'.$i.'" size=10>';
      echo '<button type="button" id="addInput'.$i.'" onclick=AddInputBox('.$i.',5)>+</button>'; // The add line button
      echo '</div>';
    }
  }
      echo '<br><br><input type="submit">';
    echo '</form>';
  } else {
    echo 'No questions posted at this time.';
  }
}
