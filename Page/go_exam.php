<?php



include('header.php');

$exam_id = '';
$exam_status = '';
$remaining_minutes = '';

if(isset($_SESSION['ToTestId']))
{
	$exam_id = $_SESSION['ToTestId'];
	$exam->query = "
	SELECT exam_status,  Time_limit_minute
	 FROM Test_list 
	WHERE TestId = '$exam_id'
	";
	$result = $exam->query_result();

	foreach($result as $row)
	{
		$exam_status = $row['exam_status'];
		$duration = $row['Time_limit_minute'] . ' minute';
		$exam_end_time = strtotime($duration);

		$exam_end_time = date('Y-m-d H:i:s', $exam_end_time);
		$remaining_minutes = strtotime($exam_end_time) - time();
	}
}
else
{
	header('location:Examlist.php');
}


?>

<br />
<?php
if($exam_status == 'Activated')
{
	$exam->data = array(
		':user_id'		=>	$_SESSION['UserId'],
		':exam_id'		=>	$exam_id,
		':attendance_status'	=>	'Present'
	);

?>
<div class="row">
	<div class="col-md-8">
		<div class="card">
			<div class="card-header">Online Exam</div>
			<div class="card-body">
				<div id="single_question_area"></div>
			</div>
		</div>
		<br />
		<div id="question_navigation_area"></div>
	</div>
	<div class="col-md-4">
		<br />
		<div align="center">
			<div id="exam_timer" data-timer="<?php echo $remaining_minutes; ?>" style="max-width:400px; width: 100%; height: 200px;"></div>
		</div>
		<br />
		<div id="user_details_area"></div>		
	</div>
</div>

<script>

$(document).ready(function(){
	var exam_id = "<?php echo $exam_id; ?>";

	load_question();
	question_navigation();

	function load_question(question_id = '')
	{
		$.ajax({
			url:"../DatabaseConn/Ajax_func.php",
			method:"POST",
			data:{exam_id:exam_id, question_id:question_id, student:'view_exam', student_action:'load_question'},
			success:function(data)
			{
				$('#single_question_area').html(data);
			}
		})
	}

	$(document).on('click', '.next', function(){
		var question_id = $(this).attr('id');
		load_question(question_id);
	});

	$(document).on('click', '.previous', function(){
		var question_id = $(this).attr('id');
		load_question(question_id);
	});

	function question_navigation()
	{
		$.ajax({
			url:"../DatabaseConn/Ajax_func.php",
			method:"POST",
			data:{exam_id:exam_id, student:'view_exam', student_action:'question_navigation'},
			success:function(data)
			{
				$('#question_navigation_area').html(data);
			}
		})
	}

	$(document).on('click', '.question_navigation', function(){
		var question_id = $(this).data('question_id');
		load_question(question_id);
	});

	function load_user_details()
	{
		$.ajax({
			url:"../DatabaseConn/Ajax_func.php",
			method:"POST",
			data:{student:'view_exam', student_action:'user_detail'},
			success:function(data)
			{
				$('#user_details_area').html(data);
			}
		})
	}

	load_user_details();

	$("#exam_timer").TimeCircles({ 
		time:{
			Days:{
				show: false
			},
			Hours:{
				show: false
			}
		}
	});

	setInterval(function(){
		var remaining_second = $("#exam_timer").TimeCircles().getTime();
		if(remaining_second < 1)
		{
			alert('Exam time over');
			location.reload();
		}
	}, 1000);
	var answer_option = 'Testing';
	console.log(answer_option);
	$(document).on('click', '.answer_option', function(){
		var question_id = $(this).data('question_id');

		 answer_option = $(this).data('id');

		console.log(answer_option);
		console.log(question_id);
		$.ajax({
			url:"../DatabaseConn/Ajax_func.php",
			method:"POST",
			data:{question_id:question_id, answer_option:answer_option, exam_id:exam_id, student:'view_exam', student_action:'answer'},
			success:function(data)
			{

			}
		})
	});

});
</script>
<?php
}

?>
	

</div>
</body>
</html>

