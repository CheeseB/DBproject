<?php

	require_once('./dbconfig.php');
	
	
	$w = '';

	$c_num = 'null';

	

	//2Depth & 수정 & 삭제

	if(isset($_POST['w'])) {

		$w = $_POST['w'];

		$c_num = $_POST['c_num'];

	}
	

	$bNo = $_POST['board_num'];


	$c_Password = $_POST['c_password'];
	
	

		if($w !== 'd') {//$w 변수가 d일 경우 $coContent와 $coId가 필요 없음.

		$c_content = $_POST['c_content'];

		if($w !== 'u') {//$w 변수가 u일 경우 $coId가 필요 없음.

			$user_id = $_POST['user_id'];

		}

	}

	

	if(empty($w) || $w === 'w') { //$w 변수가 비어있거나 w인 경우

		$msg = '작성';

		$sql = 'insert into comment values(' .$user_id . ', ' .$bNo. ',  "' . $c_content. '" , ' . $c_num . ', null , password("' . $c_Password . '"))';



		if(empty($w)) { //$w 변수가 비어있다면,

			$result = $db->query($sql);

			

			$c_num = $db->insert_id;

			$sql = 'update comment set depth = c_num where c_num = ' . $c_num;

		}

		

	} else if($w === 'u') { //작성

		$msg = '수정';

		

		$sql = 'select count(*) as cnt from comment where c_password=password("' . $c_Password . '") and c_num = ' . $c_num;

		$result = $db->query($sql);

		$row = $result->fetch_assoc();

		

		if(empty($row['cnt'])) { //맞는 결과가 없을 경우 종료

?>

			<script>

				alert('비밀번호가 맞지 않습니다.');

				history.back();

			</script>

<?php 

			exit;	

		}

		

		$sql = 'update comment set c_content = "' . $c_content . '" where c_password=password("' . $c_Password . '") and c_num = ' . $c_num;

		

	} else if($w === 'd') { //삭제

		$msg = '삭제';

		$sql = 'select count(*) as cnt from comment where c_password=password("' . $c_Password . '") and c_num = ' . $c_num;



		$result = $db->query($sql);

		$row = $result->fetch_assoc();

		

		if(empty($row['cnt'])) { //맞는 결과가 없을 경우 종료

?>

			<script>

				alert('비밀번호가 맞지 않습니다.');

				history.back();

			</script>

<?php 

			exit;	

		}

		$sql = 'delete from comment where c_password=password("' . $c_Password . '") and c_num = ' . $c_num;



	} else {

?>

		<script>

			alert('정상적인 경로를 이용해주세요.');

			history.back();

		</script>

<?php 

		exit;

	}

	

	$result = $db->query($sql);

	if($result) {

?>

		<script>

			alert('댓글이 정상적으로 <?php echo $msg?>되었습니다.');

			location.replace("./view.php?board_num=<?php echo $bNo?>");

		</script>

<?php

	} else {

?>

		<script>

			alert('댓글 <?php echo $msg?>에 실패했습니다.');

			history.back();

		</script>

<?php

		exit;

	}

	

?>

	