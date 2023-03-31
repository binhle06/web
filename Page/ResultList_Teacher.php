<?php

include('header_Teacher.php');


$exam->query = "
SELECT User_info.UserId, User_info.UserFullname, sum(user_exam_question_answer.marks) as total_mark,
user_exam_question_answer.Test_Date
FROM user_exam_question_answer  
INNER JOIN User_info 
ON User_info.UserId = user_exam_question_answer.UserId 
WHERE user_exam_question_answer.TestId = '".$_SESSION['TempId']."' 
GROUP BY user_exam_question_answer.UserId 
ORDER BY total_mark DESC 
";

$result = $exam->query_result();


?>



<h2 id="Result_List">Result List</h2>
<div class="table-wrapper1">   
<table class="fl-table">
		<thead>
		<tr class="Profile-tab1-tr">
			<th >ID</th>
			<th>Name</th>
			<th >Occupation</th>
			<th>Date Created</th>
			<th>Delete</th>
			</tr>
			</thead>
			<tbody>
	<?php
		foreach($result as $row)
		{
	?>
	
			
		
		<tr>
			<td> <?php echo $row["UserId"]; ?> </td>
			<td> <?php echo $row["UserFullname"]; ?></td>
			<td> <?php echo $row["total_mark"]; ?></td>
			<td> <?php echo $row["Test_Date"]; ?></td>
			<td><button type="button" name="delete" id="<?php echo $row["UserId"] ?>" class="btn btn-primary btn-sm delete">Delete</button></td>
		</tr>
	
<?php
	}
?>
<tbody>
</table>
	<br>
	
</div>


<div class="modal" id="deleteModal">
  	<div class="modal-dialog">
    	<div class="modal-content">
      		<!-- Modal Header -->
      		<div class="modal-header">
        		<h4 class="modal-title">Delete Confirmation</h4>
        		<button type="button" class="close" data-dismiss="modal">&times;</button>
      		</div>

      		<!-- Modal body -->
      		<div class="modal-body">
        		<h3 align="center">Are you sure you want to delete this?</h3>
      		</div>

      		<!-- Modal footer -->
      		<div class="modal-footer">
      			<button type="button" name="ok_button" id="ok_button" class="btn btn-primary btn-sm">OK</button>
        		<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      		</div>
    	</div>
  	</div>
</div>







<script type="text/javascript" language="javascript">

$(document).ready(function(){


var UserId = '';
$(document).on('click', '.delete', function(){
	UserId = $(this).attr('id');
		$('#deleteModal').modal('show');
	});

	$('#ok_button').click(function(){
		$.ajax({
			url:"../DatabaseConn/Ajax_func.php",
			method:"POST",
			data:{UserId:UserId, teacher_action:'delete', teacher:'result_list'},
			dataType:"json",
			success:function(data)
			{
	
				$('#deleteModal').modal('hide');
				location.reload();
			}
		})
	});






});


</script>



</body>
</html>