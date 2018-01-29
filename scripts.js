<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
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
