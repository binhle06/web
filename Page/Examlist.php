<?php

include('header.php');

?>
 <div class="Exam_List_Ct">
	<h1 id="Header_Examlist">Exam List</h1>
	
	<table id="course_table" class="table table-bordered table-striped">
            <thead bgcolor="#6cd8dc">
                        <tr class="table-primary">
                            <th width="5%">Test ID</th>
                           <th width="40%">Test Name</th>
                           <th width="5%">Question Total</th>
                           <th width="10%">Time limit</th>
						   <th width="30%">Arthor</th>
						   <th width="10%">Take Test</th>
                        </tr>
            </thead>
		</table>
		
	<br>
</div>









<script type="text/javascript" language="javascript">

$(document).ready(function(){

	var dataTable = $('#course_table').DataTable({
        "paging":true,
        "processing":true,
        "serverSide":true,
        "order": [],
        "info":true,
        "ajax":{
            url:"../DatabaseConn/Ajax_func.php",
            type:"POST",
			data:{student_action:'fetch', student:'exam_list'}
        },
        "columnDefs":[
           {
            "targets":[0,5],
            "orderable":false,
           },
        ],
     });


     $('#exam_form').parsley();

	$('#exam_form').on('submit', function(event){
		event.preventDefault();

		$('#TestId').attr('required', 'required');

		$('#TestName').attr('required', 'required');

		$('#total_question').attr('required', 'required');

		$('#online_exam_duration').attr('required', 'required');

		if($('#exam_form').parsley().validate())
		{
			$.ajax({
				url:"../DatabaseConn/Ajax_func.php",
				method:"POST",
				data:$(this).serialize(),
				dataType:"json",
				beforeSend:function(){
					$('#button_action').attr('disabled', 'disabled');
					$('#button_action').val('Validate...');
				},
				success:function(data)
				{
					if(data.success)
					{
						$('#message_operation').html('<div class="alert alert-success">'+data.success+'</div>');
						reset_form();

						dataTable.ajax.reload();

						$('#formModal').modal('hide');
					}

				
				}
			});
		}
	});

	


	var TestId = '';

	$(document).on('click', '.edit', function(){
		TestId = $(this).attr('id');

		reset_form();

		$.ajax({
			url:"../DatabaseConn/Ajax_func.php",
			method:"POST",
			data:{student_action:'edit_fetch', TestId:TestId, student:'exam_list'},
			dataType:"json",
			success:function(data)
			{
				$('#TestId').val(data.TestId);

				$('#TestName').val(data.TestName);

				$('#total_question').val(data.Question_total);

				$('#online_exam_duration').val(data.Time_limit_minute);

				$('#modal_title').text('Edit Exam Details');

				$('#button_action').val('Edit');

				$('#student_action').val('Edit');

				$('#formModal').modal('show');
			}
		})
	});




	$(document).on('click', '.delete', function(){
		TestId = $(this).attr('id');
		$('#deleteModal').modal('show');
	});

	$('#ok_button').click(function(){
		$.ajax({
			url:"../DatabaseConn/Ajax_func.php",
			method:"POST",
			data:{TestId:TestId, student_action:'delete', student:'exam_list'},
			dataType:"json",
			success:function(data)
			{
	
				$('#deleteModal').modal('hide');
				dataTable.ajax.reload();
			}
		})
	});	


	
	$(document).on('click', '.dark', function(){
		TestId = $(this).attr('id');
		$('#ChangeModal').modal('show');
	});


	$('#ok_button2').click(function(){
		$.ajax({
			url:"../DatabaseConn/Ajax_func.php",
			method:"POST",
			data:{TestId:TestId, student_action:'change', student:'exam_list'},
			dataType:"json",
			success:function(data)
			{
	
				$('#ChangeModal').modal('hide');
				dataTable.ajax.reload();
			}
		})
	});	


	$(document).on('click', '.view', function(){
		TestId = $(this).attr('id');
		console.log(TestId);


		$.ajax({
			url:"../DatabaseConn/Ajax_func.php",
			method:"POST",
			data:{student_action:'To_Question', TestId:TestId, student:'exam_list'},
			dataType:"json",
			success:function(data)
			{
				location.href = "QuestionList_student.php";
			}
		})
		
		
	});
	
		



});

</script>
</body>
</html>
