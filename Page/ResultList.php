<?php

include('header.php');


$exam->query = "
SELECT User_info.UserId, User_info.UserFullname, sum(user_exam_question_answer.marks) as total_mark,
user_exam_question_answer.Test_Date
FROM user_exam_question_answer  
INNER JOIN User_info 
ON User_info.UserId = user_exam_question_answer.UserId 
WHERE user_exam_question_answer.TestId = '".$_SESSION['UserId']."' 
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
			<th >Marks</th>
			<th>Date Created</th>
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
		</tr>
	
<?php
	}
?>
<tbody>
</table>
	<br>
	
</div>







<script type="text/javascript" language="javascript">

$(document).ready(function(){






});


</script>



</body>
</html>