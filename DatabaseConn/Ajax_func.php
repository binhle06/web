<?php
	include('Exam_Structure.php');

 $exam = new Exam_Struct();

	if(isset($_POST['action'])){
 	if($_POST['action'] == 'Login')
			{
				$exam->data = array(
					':username'	=>	$_POST['username']
				);

				$exam->query = "
				select * from User_info where UserId = :username
				";

				$total_row = $exam->total_row();

				if($total_row > 0)
				{
					$result = $exam->query_result();

					foreach($result as $row)
					{

						if($_POST['password'] == $row['pass'])
							{
								$_SESSION['UserId'] = $row['UserId'];
								$_SESSION['Name'] = $row['UserFullname'];
								$_SESSION['User_role'] = $row['User_role'];
								if($row['User_role'] == 'Admin'){									
									$output = array(
										'success'=>	3
									);
								}
								if($row['User_role'] == 'Teacher'){
									$output = array(
										'success'=>	2
									);
								}
								if($row['User_role'] == 'Student'){
									$output = array(
										'success'=>	1
									);
								}															
							}
							else
							{
								$output = array(
									'error'=>	'Wrong info'
								);
							}
					}
	
					
				}
				else
				{
					$output = array(
						'error'	=>	'Wrong Email Address'
					);
				}
				echo json_encode($output);
			}

		}

	if(isset($_POST['admin']))
	{					
		if($_POST['admin'] == 'exam_list')
		{
			if($_POST['admin_action'] == 'fetch')
			{
				$output = array();

			$exam->query = "
			SELECT * FROM Test_list WHERE
			";
			if(isset($_POST["search"]["value"]))
			{	
				$exam->query .= ' TestId LIKE "%'.$_POST["search"]["value"].'%" ';
				$exam->query .= 'OR TestName LIKE "%'.$_POST["search"]["value"].'%" ';
				$exam->query .= 'OR Question_total LIKE "%'.$_POST["search"]["value"].'%" ';
				$exam->query .= 'OR Time_limit_minute LIKE "%'.$_POST["search"]["value"].'%" ';
				$exam->query .= 'OR UserFullname LIKE "%'.$_POST["search"]["value"].'%" ';
				$exam->query .= 'OR exam_status LIKE "%'.$_POST["search"]["value"].'%" ';
			}

			if(isset($_POST['order']))
			{
				$exam->query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
			}
			else
			{
				$exam->query .= 'ORDER BY TestId Desc ';
			}
		

			if($_POST["length"] != -1)
			{
				$exam->query .= 'LIMIT ' .$_POST['start']. ', ' .$_POST['length'];
			}
			
			$filtered_rows = $exam->total_row();
			$result = $exam->query_result();
			
			
			
			$exam->query = "
			SELECT * FROM Test_list;
			";
			$total_rows = $exam->total_row();

			$data = array();
			foreach($result as $row)
			{
				$sub_array = array();

				$sub_array[] = $row["TestId"];
				$sub_array[] = $row["TestName"];
				$sub_array[] = $row["Question_total"];
				$sub_array[] = $row["Time_limit_minute"];
				$sub_array[] = $row["UserFullname"];
				$sub_array[] = $row["exam_status"];
				$sub_array[] = '<button type="button" name="edit" id="'.$row["TestId"].'" class="btn btn-primary btn-sm edit">Edit</button>';
				$sub_array[] = '<button type="button" name="delete" id="'.$row["TestId"].'" class="btn btn-danger btn-sm delete">Delete</button>';
				$sub_array[] = '<button type="button" name="View" id="'.$row["TestId"].'" class="btn btn-warning btn-sm view">View Question</button>';
				$sub_array[] = '<button type="button" name="dark" id="'.$row["TestId"].'" class="btn btn-dark btn-sm dark">Change</button>';
				$data[] = $sub_array;
			}
			$output = array(
				"draw"              =>  intval($_POST["draw"]),
				"recordsTotal"      =>   $total_rows,
				"recordsFiltered"   =>  $filtered_rows,
				"data"              =>  $data
			);
			echo json_encode($output);
		 	}



			if($_POST['admin_action'] == 'Add')
			{
				$exam->data = array(
					':TestId'				=>	$_POST['TestId'],
					':TestName'	=>	$exam->clean_data($_POST['TestName']),
					':Question_total'	=>	$_POST['total_question'],
					':Time_limit_minute'	=>	$_POST['online_exam_duration'],
					':UserFullname'		=>	$_SESSION['Name'],
					':UserId'=>	$_SESSION['UserId'],
					':exam_status'	=>	'Deactivated',
				);

				$exam->query = "
				INSERT INTO Test_list 
				(TestId, TestName, Question_total, Time_limit_minute, UserFullname, UserId, exam_status) 
				VALUES (:TestId, :TestName, :Question_total, :Time_limit_minute, :UserFullname, :UserId, :exam_status)
				";

				$exam->execute_query();

				$output = array(
					'success'	=>	'New Exam Details Added'
				);

				echo json_encode($output);
			}

			if($_POST['admin_action'] == 'delete')
		{
			$exam->data = array(
				':TestId'	=>	$_POST['TestId']
			);

			$exam->query = "
			DELETE FROM Test_list
			WHERE TestId = :TestId
			";

			$exam->execute_query();

			$output = array(
				'success'	=>	'Exam Details has been removed'
			);

			echo json_encode($output);
		}

		if($_POST['admin_action'] == 'change')
		{
			$exam->data = array(
				':TestId'	=>	$_POST['TestId']
			);

			$exam->query = "
			Select exam_status FROM test_list
			WHERE TestId = :TestId
			";
			$result = $exam->query_result();
			foreach($result as $row)
			{
				if($row["exam_status"] == "Activated"){
					$exam->query = "
						update Test_list set exam_status = 'Deactivated'
						WHERE TestId = :TestId
						";
				}
				elseif($row["exam_status"] == "Deactivated"){
					$exam->query = "
					update Test_list set exam_status = 'Activated'
					WHERE TestId = :TestId
					";
				}

			}

			$exam->execute_query();

			$output = array(
				'success'	=>	'Exam Details has been change'
			);

			echo json_encode($output);
		}


		if($_POST['admin_action'] == 'edit_fetch')
		{
			$exam->query = "
			SELECT * FROM Test_list 
			WHERE TestId = '".$_POST["TestId"]."'
			";

			$result = $exam->query_result();

			$_SESSION['TempId'] = $_POST["TestId"];

			foreach($result as $row)
			{
				$output['TestId'] = $row['TestId'];

				$output['TestName'] = $row['TestName'];

				$output['Question_total'] = $row['Question_total'];

				$output['Time_limit_minute'] = $row['Time_limit_minute'];
			}

			echo json_encode($output);
		}
	


		if($_POST['admin_action'] == 'Edit')
		{
			$exam->data = array(
				':TestId'				=>	$_POST['TestId'],
				':TestName'	=>	$exam->clean_data($_POST['TestName']),
				':Question_total'	=>	$_POST['total_question'],
				':Time_limit_minute'	=>	$_POST['online_exam_duration'],
			);

			$exam->query = "
			UPDATE Test_list
			SET  TestId = :TestId, TestName = :TestName , Question_total = :Question_total, Time_limit_minute = :Time_limit_minute
			WHERE TestId = '".$_SESSION['TempId']."'
			";

			$exam->execute_query($exam->data);

			$output = array(
				'success'	=>	'Exam Details has been changed'
			);
			unset($_SESSION['TempId']);
			echo json_encode($output);
		}


		if($_POST['admin_action'] == 'To_Question')
		{
			$_SESSION['TempId'] = $_POST["TestId"];

			$output = array(
				'success'	=>	'Go To Exam Qquestion List'
			);
			echo json_encode($output);
		}

		
	}






	if($_POST['admin'] == 'Question_list')
		{
			if($_POST['admin_action'] == 'fetch')
			{
				$output = array();

				$exam->query = "
				SELECT * FROM Question_List 
				WHERE TestId = '".$_SESSION['TempId']."' 
				AND (
				";
				if(isset($_POST["search"]["value"]))
				{	
					$exam->query .= ' question_detail LIKE "%'.$_POST["search"]["value"].'%" ';
					$exam->query .= 'OR answer_option LIKE "%'.$_POST["search"]["value"].'%" ';
		
				}

				$exam->query .= ')';

				if(isset($_POST['order']))
				{
					$exam->query .= '
					ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' 
					';
				}
				else
				{
					$exam->query .= 'ORDER BY question_id Asc ';
				}
			
				$extra_query = '';

				if($_POST["length"] != -1)
				{
					$exam->query .= 'LIMIT ' .$_POST['start']. ', ' .$_POST['length'];
				}
				
				$filtered_rows = $exam->total_row();
				$result = $exam->query_result();
				
				
				
				$exam->query = "
				SELECT * FROM Question_List 
				WHERE TestId = '".$_SESSION['TempId']."' ;
				";
				$total_rows = $exam->total_row();

				$data = array();
				foreach($result as $row)
				{
					$sub_array = array();

					$sub_array[] = $row["question_id"];
					$sub_array[] = $row["question_detail"];
					$sub_array[] = $row["answer_option"];
					$sub_array[] = '<button type="button" name="edit" id="'.$row["question_id"].'" class="btn btn-primary btn-sm edit">Edit</button>';
					$sub_array[] = '<button type="button" name="delete" id="'.$row["question_id"].'" class="btn btn-danger btn-sm delete">Delete</button>';
					$data[] = $sub_array;
				}
				$output = array(
					"draw"              =>  intval($_POST["draw"]),
					"recordsTotal"      =>   $total_rows,
					"recordsFiltered"   =>  $filtered_rows,
					"data"              =>  $data
				);
				echo json_encode($output);
				}


			if($_POST['admin_action'] == 'Add')
				{
					$exam->data = array(
						':TestId'				=>	$_SESSION['TempId'],
						':question_detail'		=>	$exam->clean_data($_POST['question_detail']),
						':answer_option'		=>	$_POST['answer_option']
					);
		
					$exam->query = "
					INSERT INTO Question_List 
					(TestId, question_detail, answer_option) 
					VALUES (:TestId, :question_detail, :answer_option)
					";
		
					$question_id = $exam->execute_question_with_last_id($exam->data);
		
					for($count = 1; $count <= 4; $count++)
					{
						$exam->data = array(
							':question_id'		=>	$question_id,
							':option_number'	=>	$count,
							':option_detail'		=>	$exam->clean_data($_POST['option_detail_' 
							. $count])
						);
		
						$exam->query = "
						INSERT INTO option_table 
						(question_id, option_number, option_detail) 
						VALUES (:question_id, :option_number, :option_detail)
						";
		
						$exam->execute_query($exam->data);
					}
		
					$output = array(
						'success'		=>	'Question Added'
					);
		
					echo json_encode($output);
				}


			if($_POST['admin_action'] == 'edit_fetch')
				{
					$exam->query = "
					SELECT * FROM Question_List
					WHERE question_id = '".$_POST["question_id"]."'
					";
		
					$result = $exam->query_result();
		
					foreach($result as $row)
					{
						$output['question_detail'] = html_entity_decode($row['question_detail']);
		
						$output['answer_option'] = $row['answer_option'];
		
						for($count = 1; $count <= 4; $count++)
						{
							$exam->query = "
							SELECT option_detail FROM option_table 
							WHERE question_id = '".$_POST["question_id"]."' 
							AND option_number = '".$count."'
							";
		
							$sub_result = $exam->query_result();
		
							foreach($sub_result as $sub_row)
							{
								$output["option_detail_" . $count] = html_entity_decode($sub_row["option_detail"]);
							}
						}
					}
		
					echo json_encode($output);
				}



				if($_POST['admin_action'] == 'Edit')
				{
					$exam->data = array(
						':question_detail'		=>	$_POST['question_detail'],
						':answer_option'		=>	$_POST['answer_option'],
						':question_id'			=>	$_POST['question_id']
					);
		
					$exam->query = "
					UPDATE Question_List 
					SET question_detail = :question_detail, answer_option = :answer_option 
					WHERE question_id = :question_id
					";
		
					$exam->execute_query();
		
					for($count = 1; $count <= 4; $count++)
					{
						$exam->data = array(
							':question_id'		=>	$_POST['question_id'],
							':option_number'	=>	$count,
							':option_detail'		=>	$_POST['option_detail_' . $count]
						);
		
						$exam->query = "
						UPDATE option_table 
						SET option_detail = :option_detail 
						WHERE question_id = :question_id 
						AND option_number = :option_number
						";
						$exam->execute_query();
					}
		
					$output = array(
						'success'	=>	'Question Edit'
					);
		
					echo json_encode($output);
				}
			
			if($_POST['admin_action'] == 'delete')
				{
					$exam->data = array(
						':question_id'	=>	$_POST['question_id']
					);

					$exam->query = "
					DELETE FROM Question_List
					WHERE question_id = :question_id;
					DELETE FROM option_table
					WHERE question_id = :question_id;
				
					";

					$exam->execute_query();

					$output = array(
						'success'	=>	'Question Details has been removed'
					);

					echo json_encode($output);
				}



			

		
		}




		if($_POST['admin'] == 'User_list')
		{
				if($_POST['admin_action'] == 'fetch')
				{
					$output = array();

				$exam->query = "
				SELECT * FROM User_info WHERE
				";
				if(isset($_POST["search"]["value"]))
				{	
					$exam->query .= ' Id LIKE "%'.$_POST["search"]["value"].'%" ';
					$exam->query .= 'OR UserId LIKE "%'.$_POST["search"]["value"].'%" ';
					$exam->query .= 'OR UserFullname LIKE "%'.$_POST["search"]["value"].'%" ';
					$exam->query .= 'OR User_role LIKE "%'.$_POST["search"]["value"].'%" ';
				}

				if(isset($_POST['order']))
				{
					$exam->query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
				}
				else
				{
					$exam->query .= 'ORDER BY Id Desc ';
				}
			

				if($_POST["length"] != -1)
				{
					$exam->query .= 'LIMIT ' .$_POST['start']. ', ' .$_POST['length'];
				}
				
				$filtered_rows = $exam->total_row();
				$result = $exam->query_result();
				
				
				
				$exam->query = "
				SELECT * FROM User_info;
				";
				$total_rows = $exam->total_row();

				$data = array();
				foreach($result as $row)
				{
					$sub_array = array();

					$sub_array[] = $row["Id"];
					$sub_array[] = $row["UserId"];
					$sub_array[] = $row["UserFullname"];
					$sub_array[] = $row["User_role"];
					$sub_array[] = '<button type="button" name="edit" id="'.$row["UserId"].'" class="btn btn-primary btn-sm edit">Edit</button>';
					$sub_array[] = '<button type="button" name="delete" id="'.$row["UserId"].'" class="btn btn-danger btn-sm delete">Delete</button>';
					$data[] = $sub_array;
				}
				$output = array(
					"draw"              =>  intval($_POST["draw"]),
					"recordsTotal"      =>   $total_rows,
					"recordsFiltered"   =>  $filtered_rows,
					"data"              =>  $data
				);
				echo json_encode($output);
				}



				if($_POST['admin_action'] == 'Add')
				{
					$exam->data = array(
						':UserId'				=>	$_POST['UserId'],
						':UserFullname'	=>	$exam->clean_data($_POST['UserFullname']),
						':pass'	=>	$_POST['pass'],
						':role'	=>	$_POST['role'],
					);

					$exam->query = "
					insert into User_info 
					(UserId, UserFullname, pass, User_role)
					VALUES (:UserId, :UserFullname, :pass, :role)
					";

					$exam->execute_query();

					$output = array(
						'success'	=>	'New Exam Details Added'
					);

					echo json_encode($output);
				}

				if($_POST['admin_action'] == 'delete')
			{
				$exam->data = array(
					':UserId'	=>	$_POST['user']
				);

				$exam->query = "
				DELETE FROM User_info
				WHERE UserId = :UserId
				";

				$exam->execute_query();

				$output = array(
					'success'	=>	'Exam Details has been removed'
				);

				echo json_encode($output);
			}


			if($_POST['admin_action'] == 'edit_fetch')
			{
				$exam->query = "
				SELECT * FROM User_info 
				WHERE UserId = '".$_POST["user"]."'
				";

				$result = $exam->query_result();

				$_SESSION['TempUser'] = $_POST["user"];
				foreach($result as $row)
				{
					$output['UserId'] = $row['UserId'];

					$output['UserFullname'] = $row['UserFullname'];

					$output['pass'] = $row['pass'];

					$output['role'] = $row['User_role'];
				}

				echo json_encode($output);
			}
		


			if($_POST['admin_action'] == 'Edit')
			{
				$exam->data = array(
					':UserId'				=>	$_POST['UserId'],
					':UserFullname'	=>	$exam->clean_data($_POST['UserFullname']),
					':pass'	=>	$_POST['pass'],
					':role'	=>	$_POST['role'],
				);

				$exam->query = "
				UPDATE User_info
				SET  UserId = :UserId, UserFullname = :UserFullname , pass = :pass, User_role = :role
				WHERE UserId = '".$_SESSION['TempUser']."'
				";

				$exam->execute_query($exam->data);

				$output = array(
					'success'	=>	'Exam Details has been changed'
				);
				unset($_SESSION['TempUser']);
				echo json_encode($output);
			}
			
		}


		if($_POST['admin'] == 'Profile_list')
		{
			if($_POST['admin_action'] == 'edit_fetch')
			{
				$exam->query = "
				SELECT * FROM User_info 
				WHERE UserId = '".$_SESSION['UserId']."'
				";

				$result = $exam->query_result();
				foreach($result as $row)
				{

					$output['pass'] = $row['pass'];
				}

				echo json_encode($output);
			}
		


			if($_POST['admin_action'] == 'Edit')
			{
				$exam->data = array(
					':pass'	=>	$_POST['pass'],
				);

				$exam->query = "
				UPDATE User_info
				SET  pass = :pass
				WHERE UserId = '".$_SESSION['UserId']."'
				";

				$exam->execute_query($exam->data);

				$output = array(
					'success'	=>	'Details has been changed'
				);
				echo json_encode($output);
			}
			
		}


		
	}

	if(isset($_POST['teacher']))
		{				
			if($_POST['teacher'] == 'exam_list')
				{
			if($_POST['teacher_action'] == 'fetch')
			{
				$output = array();

			$exam->query = "
			SELECT * FROM Test_list WHERE UserId = '".$_SESSION['UserId']."' and (
			";
			if(isset($_POST["search"]["value"]))
			{	
				$exam->query .= ' TestId LIKE "%'.$_POST["search"]["value"].'%" ';
				$exam->query .= 'OR TestName LIKE "%'.$_POST["search"]["value"].'%" ';
				$exam->query .= 'OR Question_total LIKE "%'.$_POST["search"]["value"].'%" ';
				$exam->query .= 'OR Time_limit_minute LIKE "%'.$_POST["search"]["value"].'%" ';
				$exam->query .= 'OR UserFullname LIKE "%'.$_POST["search"]["value"].'%" ';
				$exam->query .= 'OR exam_status LIKE "%'.$_POST["search"]["value"].'%" ';
			}
			$exam->query .= ')';

			if(isset($_POST['order']))
			{
				$exam->query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
			}
			else
			{
				$exam->query .= 'ORDER BY TestId Desc ';
			}
		

			if($_POST["length"] != -1)
			{
				$exam->query .= 'LIMIT ' .$_POST['start']. ', ' .$_POST['length'];
			}
			
			$filtered_rows = $exam->total_row();
			$result = $exam->query_result();
			
			
			
			$exam->query = "
			SELECT * FROM Test_list;
			";
			$total_rows = $exam->total_row();

			$data = array();
			foreach($result as $row)
			{
				$sub_array = array();

				$sub_array[] = $row["TestId"];
				$sub_array[] = $row["TestName"];
				$sub_array[] = $row["Question_total"];
				$sub_array[] = $row["Time_limit_minute"];
				$sub_array[] = $row["UserFullname"];
				$sub_array[] = $row["exam_status"];
				$sub_array[] = '<button type="button" name="edit" id="'.$row["TestId"].'" class="btn btn-primary btn-sm edit">Edit</button>';
				$sub_array[] = '<button type="button" name="delete" id="'.$row["TestId"].'" class="btn btn-danger btn-sm delete">Delete</button>';
				$sub_array[] = '<button type="button" name="View" id="'.$row["TestId"].'" class="btn btn-warning btn-sm view">View Question</button>';
				$sub_array[] = '<button type="button" name="dark" id="'.$row["TestId"].'" class="btn btn-dark btn-sm dark">Change</button>';
				$data[] = $sub_array;
			}
			$output = array(
				"draw"              =>  intval($_POST["draw"]),
				"recordsTotal"      =>   $total_rows,
				"recordsFiltered"   =>  $filtered_rows,
				"data"              =>  $data
			);
			echo json_encode($output);
		 	}

			
			 if($_POST['teacher_action'] == 'Add')
			{
				$exam->data = array(
					':TestId'				=>	$_POST['TestId'],
					':TestName'	=>	$exam->clean_data($_POST['TestName']),
					':Question_total'	=>	$_POST['total_question'],
					':Time_limit_minute'	=>	$_POST['online_exam_duration'],
					':UserFullname'		=>	$_SESSION['Name'],
					':UserId'=>	$_SESSION['UserId'],
					':exam_status'	=>	'Deactivated',
				);

				$exam->query = "
				INSERT INTO Test_list 
				(TestId, TestName, Question_total, Time_limit_minute, UserFullname, UserId, exam_status) 
				VALUES (:TestId, :TestName, :Question_total, :Time_limit_minute, :UserFullname, :UserId, :exam_status)
				";

				$exam->execute_query();

				$output = array(
					'success'	=>	'New Exam Details Added'
				);

				echo json_encode($output);
			}

			if($_POST['teacher_action'] == 'delete')
		{
			$exam->data = array(
				':TestId'	=>	$_POST['TestId']
			);

			$exam->query = "
			DELETE FROM Test_list
			WHERE TestId = :TestId
			";

			$exam->execute_query();

			$output = array(
				'success'	=>	'Exam Details has been removed'
			);

			echo json_encode($output);
		}

		if($_POST['teacher_action'] == 'change')
		{
			$exam->data = array(
				':TestId'	=>	$_POST['TestId']
			);

			$exam->query = "
			Select exam_status FROM test_list
			WHERE TestId = :TestId
			";
			$result = $exam->query_result();
			foreach($result as $row)
			{
				if($row["exam_status"] == "Activated"){
					$exam->query = "
						update Test_list set exam_status = 'Deactivated'
						WHERE TestId = :TestId
						";
				}
				elseif($row["exam_status"] == "Deactivated"){
					$exam->query = "
					update Test_list set exam_status = 'Activated'
					WHERE TestId = :TestId
					";
				}

			}

			$exam->execute_query();

			$output = array(
				'success'	=>	'Exam Details has been change'
			);

			echo json_encode($output);
		}


		if($_POST['teacher_action'] == 'edit_fetch')
		{
			$exam->query = "
			SELECT * FROM Test_list 
			WHERE TestId = '".$_POST["TestId"]."'
			";

			$result = $exam->query_result();

			$_SESSION['TempId'] = $_POST["TestId"];

			foreach($result as $row)
			{
				$output['TestId'] = $row['TestId'];

				$output['TestName'] = $row['TestName'];

				$output['Question_total'] = $row['Question_total'];

				$output['Time_limit_minute'] = $row['Time_limit_minute'];
			}

			echo json_encode($output);
		}
	


		if($_POST['teacher_action'] == 'Edit')
		{
			$exam->data = array(
				':TestId'				=>	$_POST['TestId'],
				':TestName'	=>	$exam->clean_data($_POST['TestName']),
				':Question_total'	=>	$_POST['total_question'],
				':Time_limit_minute'	=>	$_POST['online_exam_duration'],
			);

			$exam->query = "
			UPDATE Test_list
			SET  TestId = :TestId, TestName = :TestName , Question_total = :Question_total, Time_limit_minute = :Time_limit_minute
			WHERE TestId = '".$_SESSION['TempId']."'
			";

			$exam->execute_query($exam->data);

			$output = array(
				'success'	=>	'Exam Details has been changed'
			);
			unset($_SESSION['TempId']);
			echo json_encode($output);
		}
			 





		 if($_POST['teacher_action'] == 'To_Question')
				{
					$_SESSION['TempId'] = $_POST["TestId"];

					$output = array(
						'success'	=>	'Go To Exam Qquestion List'
					);
					echo json_encode($output);
				}

		}

		if($_POST['teacher'] == 'Profile_list')
		{
			if($_POST['teacher_action'] == 'edit_fetch')
			{
				$exam->query = "
				SELECT * FROM User_info 
				WHERE UserId = '".$_SESSION['UserId']."'
				";

				$result = $exam->query_result();
				foreach($result as $row)
				{

					$output['pass'] = $row['pass'];
				}

				echo json_encode($output);
			}


			if($_POST['teacher_action'] == 'Edit')
			{
				$exam->data = array(
					':pass'	=>	$_POST['pass'],
				);

				$exam->query = "
				UPDATE User_info
				SET  pass = :pass
				WHERE UserId = '".$_SESSION['UserId']."'
				";

				$exam->execute_query($exam->data);

				$output = array(
					'success'	=>	'Details has been changed'
				);
				echo json_encode($output);
			}
		}





		if($_POST['teacher'] == 'Question_list')
		{
			if($_POST['teacher_action'] == 'fetch')
			{
				$output = array();

				$exam->query = "
				SELECT * FROM Question_List 
				WHERE TestId = '".$_SESSION['TempId']."' 
				AND (
				";
				if(isset($_POST["search"]["value"]))
				{	
					$exam->query .= ' question_detail LIKE "%'.$_POST["search"]["value"].'%" ';
					$exam->query .= 'OR answer_option LIKE "%'.$_POST["search"]["value"].'%" ';
		
				}

				$exam->query .= ')';

				if(isset($_POST['order']))
				{
					$exam->query .= '
					ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' 
					';
				}
				else
				{
					$exam->query .= 'ORDER BY question_id Asc ';
				}
			
				$extra_query = '';

				if($_POST["length"] != -1)
				{
					$exam->query .= 'LIMIT ' .$_POST['start']. ', ' .$_POST['length'];
				}
				
				$filtered_rows = $exam->total_row();
				$result = $exam->query_result();
				
				
				
				$exam->query = "
				SELECT * FROM Question_List 
				WHERE TestId = '".$_SESSION['TempId']."' ;
				";
				$total_rows = $exam->total_row();

				$data = array();
				foreach($result as $row)
				{
					$sub_array = array();

					$sub_array[] = $row["question_id"];
					$sub_array[] = $row["question_detail"];
					$sub_array[] = $row["answer_option"];
					$sub_array[] = '<button type="button" name="edit" id="'.$row["question_id"].'" class="btn btn-primary btn-sm edit">Edit</button>';
					$sub_array[] = '<button type="button" name="delete" id="'.$row["question_id"].'" class="btn btn-danger btn-sm delete">Delete</button>';
					$data[] = $sub_array;
				}
				$output = array(
					"draw"              =>  intval($_POST["draw"]),
					"recordsTotal"      =>   $total_rows,
					"recordsFiltered"   =>  $filtered_rows,
					"data"              =>  $data
				);
				echo json_encode($output);
				}


			if($_POST['teacher_action'] == 'Add')
				{
					$exam->data = array(
						':TestId'				=>	$_SESSION['TempId'],
						':question_detail'		=>	$exam->clean_data($_POST['question_detail']),
						':answer_option'		=>	$_POST['answer_option']
					);
		
					$exam->query = "
					INSERT INTO Question_List 
					(TestId, question_detail, answer_option) 
					VALUES (:TestId, :question_detail, :answer_option)
					";
		
					$question_id = $exam->execute_question_with_last_id($exam->data);
		
					for($count = 1; $count <= 4; $count++)
					{
						$exam->data = array(
							':question_id'		=>	$question_id,
							':option_number'	=>	$count,
							':option_detail'		=>	$exam->clean_data($_POST['option_detail_' 
							. $count])
						);
		
						$exam->query = "
						INSERT INTO option_table 
						(question_id, option_number, option_detail) 
						VALUES (:question_id, :option_number, :option_detail)
						";
		
						$exam->execute_query($exam->data);
					}
		
					$output = array(
						'success'		=>	'Question Added'
					);
		
					echo json_encode($output);
				}


			if($_POST['teacher_action'] == 'edit_fetch')
				{
					$exam->query = "
					SELECT * FROM Question_List
					WHERE question_id = '".$_POST["question_id"]."'
					";
		
					$result = $exam->query_result();
		
					foreach($result as $row)
					{
						$output['question_detail'] = html_entity_decode($row['question_detail']);
		
						$output['answer_option'] = $row['answer_option'];
		
						for($count = 1; $count <= 4; $count++)
						{
							$exam->query = "
							SELECT option_detail FROM option_table 
							WHERE question_id = '".$_POST["question_id"]."' 
							AND option_number = '".$count."'
							";
		
							$sub_result = $exam->query_result();
		
							foreach($sub_result as $sub_row)
							{
								$output["option_detail_" . $count] = html_entity_decode($sub_row["option_detail"]);
							}
						}
					}
		
					echo json_encode($output);
				}



				if($_POST['teacher_action'] == 'Edit')
				{
					$exam->data = array(
						':question_detail'		=>	$_POST['question_detail'],
						':answer_option'		=>	$_POST['answer_option'],
						':question_id'			=>	$_POST['question_id']
					);
		
					$exam->query = "
					UPDATE Question_List 
					SET question_detail = :question_detail, answer_option = :answer_option 
					WHERE question_id = :question_id
					";
		
					$exam->execute_query();
		
					for($count = 1; $count <= 4; $count++)
					{
						$exam->data = array(
							':question_id'		=>	$_POST['question_id'],
							':option_number'	=>	$count,
							':option_detail'		=>	$_POST['option_detail_' . $count]
						);
		
						$exam->query = "
						UPDATE option_table 
						SET option_detail = :option_detail 
						WHERE question_id = :question_id 
						AND option_number = :option_number
						";
						$exam->execute_query();
					}
		
					$output = array(
						'success'	=>	'Question Edit'
					);
		
					echo json_encode($output);
				}
			
			if($_POST['teacher_action'] == 'delete')
				{
					$exam->data = array(
						':question_id'	=>	$_POST['question_id']
					);

					$exam->query = "
					DELETE FROM Question_List
					WHERE question_id = :question_id;
					DELETE FROM option_table
					WHERE question_id = :question_id;
				
					";

					$exam->execute_query();

					$output = array(
						'success'	=>	'Question Details has been removed'
					);

					echo json_encode($output);
				}



			

		
		}
	}



	if(isset($_POST['student']))
	{					
		if($_POST['student'] == 'exam_list')
		{
			if($_POST['student_action'] == 'fetch')
			{
				$output = array();

			$exam->query = "
			SELECT * FROM Test_list WHERE exam_status = 'Activated' And (
			";
			if(isset($_POST["search"]["value"]))
			{	
				$exam->query .= ' TestId LIKE "%'.$_POST["search"]["value"].'%" ';
				$exam->query .= 'OR TestName LIKE "%'.$_POST["search"]["value"].'%" ';
				$exam->query .= 'OR Question_total LIKE "%'.$_POST["search"]["value"].'%" ';
				$exam->query .= 'OR Time_limit_minute LIKE "%'.$_POST["search"]["value"].'%" ';
				$exam->query .= 'OR UserFullname LIKE "%'.$_POST["search"]["value"].'%" )';
			}

			if(isset($_POST['order']))
			{
				$exam->query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
			}
			else
			{
				$exam->query .= 'ORDER BY TestId Desc ';
			}
		

			if($_POST["length"] != -1)
			{
				$exam->query .= 'LIMIT ' .$_POST['start']. ', ' .$_POST['length'];
			}
			
			$filtered_rows = $exam->total_row();
			$result = $exam->query_result();
			
			
			
			$exam->query = "
			SELECT * FROM Test_list WHERE exam_status = 'Activated';
			";
			$total_rows = $exam->total_row();

			$data = array();
			foreach($result as $row)
			{
				$sub_array = array();

				$sub_array[] = $row["TestId"];
				$sub_array[] = $row["TestName"];
				$sub_array[] = $row["Question_total"];
				$sub_array[] = $row["Time_limit_minute"];
				$sub_array[] = $row["UserFullname"];
				$sub_array[] = '<button type="button" name="View" id="'.$row["TestId"].'" class="btn btn-warning btn-sm view">View Question</button>';
				$data[] = $sub_array;
			}
			$output = array(
				"draw"              =>  intval($_POST["draw"]),
				"recordsTotal"      =>   $total_rows,
				"recordsFiltered"   =>  $filtered_rows,
				"data"              =>  $data
			);
			echo json_encode($output);
		 	}
		}
	}






?>