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
						   <th width="20%">Arthor</th>
						   <th width="10%">Take Test</th>
                           <th width="10%">Check result</th>
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


	


	var TestId = '';


	$(document).on('click', '.view', function(){
		TestId = $(this).attr('id');
		console.log(TestId);


		$.ajax({
			url:"../DatabaseConn/Ajax_func.php",
			method:"POST",
			data:{student_action:'To_Test', TestId:TestId, student:'exam_list'},
			dataType:"json",
			success:function(data)
			{
                if (data.success  == "1")
                {
                    location.href = "go_exam.php";
                }
                else if (data.success == "2")
                {
                    alert("You Have already taken your test, No more for you!");
                    location.href = "Examlist.php";
                }




				
			}
		})
		
		
	});


    $(document).on('click', '.info', function(){
		TestId = $(this).attr('id');
		console.log(TestId);


		$.ajax({
			url:"../DatabaseConn/Ajax_func.php",
			method:"POST",
			data:{student_action:'To_Result', TestId:TestId, student:'exam_list'},
			dataType:"json",
			success:function(data)
			{
				location.href = "ResultList.php";
			}
		})
		
		
	});	
	
		



});

</script>
</body>
</html>
