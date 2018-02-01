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
    }
  }
      echo '<input type="submit">';
    echo '</form>';
  } else {
    echo 'No questions posted at this time.';
  }
}
