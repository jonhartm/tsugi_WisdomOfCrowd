<script>
// Function for creating the accordian view for responses
$( function() {
    $( "#accordion" ).accordion({
      collapsible: true
    });
  } );
</script>

<script>
function QuestionTypeChanged() {
  type = document.forms[0].question_type.value;
  alert("change form to " + type);
}
</script>
