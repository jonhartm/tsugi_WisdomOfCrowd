// Function for creating the accordian view for responses
$( function() {
    $( "#accordion" ).accordion({
      collapsible: true
    });
  } );

// Add a new input box if the student clicks the "+" button
function AddInputBox($div_id, $max) {
  var conveniancecount = $("div[class*='conveniancecount']").length;
  // alert("multi_answer" +$div_id);
  $("#addInput"+$div_id).remove();
  var addHTML = '<br>';
  addHTML += '<input type="text" name="multi_answer" id="answer' + $div_id + '" size=10>';
  addHTML += '<button type="button" id="addInput' + $div_id + '" onclick=AddInputBox(' + $div_id + ','+$max+')>+</button>';

  document.getElementById("multi_answer" +$div_id).innerHTML += addHTML;
}
