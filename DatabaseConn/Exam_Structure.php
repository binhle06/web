<?php

class Exam_Struct
{
	var $host;
	var $username;
	var $password;
	var $database;
	var $connect;
	var $query;
	var $data;
	var $statement;
	var $filedata;

	function __construct()
	{
		$this->host = 'localhost';
		$this->username = 'root';
		$this->password = '';
		$this->database = 'nlcn';

		$this->connect = new PDO('mysql:host=localhost;dbname=nlcn;port=3306', "$this->username", "$this->password");

		session_start();
	}

	function execute_query()
	{
		$this->statement = $this->connect->prepare($this->query);
		$this->statement->execute($this->data);
	}

	

	function total_row()
	{
		$this->execute_query();
		return $this->statement->rowCount();
	}

	function redirect($page)
	{
		header('location:'.$page.'');
		exit;
	}

	
	function User_session_public()
	{
		if(isset($_SESSION['UserId']) && isset($_SESSION['Role']) && $_SESSION['Role'] == 'Student')
		{
			$this->redirect('index.php');
		}
		elseif(isset($_SESSION['UserId']) && isset($_SESSION['Role']) && $_SESSION['Role'] == 'Teacher')
		{
			$this->redirect('index_teacher.php');
		}
		elseif(isset($_SESSION['UserId']) && isset($_SESSION['Role']) && $_SESSION['Role'] == 'Admin')
		{
			$this->redirect('index_admin.php');
		}
		
		
	}


	function Student_session_private()
	{
		if(!isset($_SESSION['UserId']) || $_SESSION['User_role'] != 'Student')
		{
			$this->redirect('../login.php');
		} 
	}

	function Teacher_session_private()
	{
		if(!isset($_SESSION['UserId']) || $_SESSION['User_role'] != 'Teacher')
		{
			$this->redirect('../login.php');
		} 
	}

	function Admin_session_private()
	{
		if(!isset($_SESSION['UserId']) || $_SESSION['User_role'] != 'Admin')
		{
			$this->redirect('../login.php');
		} 
	}

	function query_result()
	{
		$this->execute_query();
		return $this->statement->fetchAll();
	}



	function clean_data($data)
	{
	 	$data = trim($data);
	  	$data = stripslashes($data);
	  	$data = htmlspecialchars($data);
	  	return $data;
	}

	function Get_exam_question_limit($exam_id)
	{
		$this->query = "
		SELECT Question_total FROM Test_list 
		WHERE TestId = '$exam_id'
		";

		$result = $this->query_result();

		foreach($result as $row)
		{
			return $row['Question_total'];
		}
	}

	function Get_exam_total_question($exam_id)
	{
		$this->query = "
		SELECT question_id FROM question_table 
		WHERE TestId = '$exam_id'
		";

		return $this->total_row();
	}

	function Is_allowed_add_question($exam_id)
	{
		$exam_question_limit = $this->Get_exam_question_limit($exam_id);

		$exam_total_question = $this->Get_exam_total_question($exam_id);

		if($exam_total_question >= $exam_question_limit)
		{
			return false;
		}
		return true;
	}

	function execute_question_with_last_id()
	{
		$this->statement = $this->connect->prepare($this->query);

		$this->statement->execute($this->data);

		return $this->connect->lastInsertId();
	}

	function Get_exam_id($exam_code)
	{
		$this->query = "
		SELECT TestId FROM Test_list 
		WHERE online_exam_code = '$exam_code'
		";

		$result = $this->query_result();

		foreach($result as $row)
		{
			return $row['TestId'];
		}
	}

	function Fill_exam_list()
	{
		$this->query = "
		SELECT TestId, online_exam_title 
			FROM Test_list 
			WHERE online_exam_status = 'Created' OR online_exam_status = 'Pending' 
			ORDER BY online_exam_title ASC
		";
		$result = $this->query_result();
		$output = '';
		foreach($result as $row)
		{
			$output .= '<option value="'.$row["TestId"].'">'.$row["online_exam_title"].'</option>';
		}
		return $output;
	}
	function If_user_already_enroll_exam($exam_id, $user_id)
	{
		$this->query = "
		SELECT * FROM user_exam_enroll_table 
		WHERE exam_id = '$exam_id' 
		AND user_id = '$user_id'
		";
		if($this->total_row() > 0)
		{
			return true;
		}
		return false;
	}

	function Change_exam_status($user_id)
	{
		$this->query = "
		SELECT * FROM user_exam_enroll_table 
		INNER JOIN Test_list 
		ON Test_list.TestId = user_exam_enroll_table.exam_id 
		WHERE user_exam_enroll_table.user_id = '".$user_id."'
		";

		$result = $this->query_result();

		$current_datetime = date("Y-m-d") . ' ' . date("H:i:s", STRTOTIME(date('h:i:sa')));

		foreach($result as $row)
		{
			$exam_start_time = $row["online_exam_datetime"];

			$duration = $row["online_exam_duration"] . ' minute';

			$exam_end_time = strtotime($exam_start_time . '+' . $duration);

			$exam_end_time = date('Y-m-d H:i:s', $exam_end_time);

			$view_exam = '';

			if($current_datetime >= $exam_start_time && $current_datetime <= $exam_end_time)
			{
				//exam started
				$this->data = array(
					':online_exam_status'	=>	'Started'
				);

				$this->query = "
				UPDATE Test_list 
				SET online_exam_status = :online_exam_status 
				WHERE TestId = '".$row['TestId']."'
				";

				$this->execute_query();
			}
			else
			{
				if($current_datetime > $exam_end_time)
				{
					//exam completed
					$this->data = array(
						':online_exam_status'	=>	'Completed'
					);

					$this->query = "
					UPDATE Test_list 
					SET online_exam_status = :online_exam_status 
					WHERE TestId = '".$row['TestId']."'
					";

					$this->execute_query();
				}					
			}
		}
	}

	function Get_user_question_option($question_id, $user_id)
	{
		$this->query = "
		SELECT user_answer_option FROM user_exam_question_answer 
		WHERE question_id = '".$question_id."' 
		AND user_id = '".$user_id."'
		";
		$result = $this->query_result();
		foreach($result as $row)
		{
			return $row["user_answer_option"];
		}
	}

	function Get_question_right_answer_mark($exam_id)
	{
		$this->query = "
		SELECT marks_per_right_answer FROM Test_list 
		WHERE TestId = '".$exam_id."' 
		";

		$result = $this->query_result();

		foreach($result as $row)
		{
			return $row['marks_per_right_answer'];
		}
	}

	function Get_question_wrong_answer_mark($exam_id)
	{
		$this->query = "
		SELECT marks_per_wrong_answer FROM Test_list 
		WHERE TestId = '".$exam_id."' 
		";

		$result = $this->query_result();

		foreach($result as $row)
		{
			return $row['marks_per_wrong_answer'];
		}
	}

	function Get_question_answer_option($question_id)
	{
		$this->query = "
		SELECT answer_option FROM question_table 
		WHERE question_id = '".$question_id."' 
		";

		$result = $this->query_result();

		foreach($result as $row)
		{
			return $row['answer_option'];
		}
	}

	function Get_exam_status($exam_id)
	{
		$this->query = "
		SELECT online_exam_status FROM Test_list 
		WHERE TestId = '".$exam_id."' 
		";
		$result = $this->query_result();
		foreach($result as $row)
		{
			return $row["online_exam_status"];
		}
	}
	function Get_user_exam_status($exam_id, $user_id)
	{
		$this->query = "
		SELECT attendance_status 
		FROM user_exam_enroll_table 
		WHERE exam_id = '$exam_id' 
		AND user_id = '$user_id'
		";
		$result = $this->query_result();
		foreach($result as $row)
		{
			return $row["attendance_status"];
		}
	}
}

?>