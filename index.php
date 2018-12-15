<?php   
include "code/dbconfig.php";
session_start();
	

?>

<!DOCTYPE html>
<head>
	<title>메인페이지</title>
	<style>
		body {
			background:#DBF0F8;
		}
	</style>
	<link rel= "stylesheet" href="css/searchbox.css">
	<link rel= "stylesheet" href="css/menubar.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>


<body>

	
<ul class="menubar">
  <li><a href="index.php">홈</a></li>
  <li><a href="code/board.php">게시판</a></li>
	
	<?php
		if(!$_SESSION['is_login']){
			echo ' <li><a href="code/login.php">로그인</a></li>
				<li><a href="code/signup.php">회원가입</a></li>';
		}
		else if($_SESSION['is_login']){
			echo $_SESSION["nickname"].' 님 환영합니다!';
			
			if($_SESSION['authority']==77){
				echo '<li><a href="code/hospital_info.php">병원정보 수정</a></li>';
				if($_SESSION['userid']=='admin'){
					echo '<li><a href="code/user_manage.php">관리자 페이지</a><li>';
				}
			}
			
			echo '<li><a href="code/logout.php">로그아웃</a><li>';
		}
	?>
	
</ul>
		
<div id="searchbox" class="container">
	<form method="get" action="code/search_result.php" class = "Search">
	
      <select name="searchColumn" class="select">
        <option value="h_name" selected="selected">병원이름/진료과목</option>
        <option value="h_symptom">증상/내용</option>
      </select>
      <button id="sr" class="Search-label" for="Search-box"><i class="fa fa-search"></i></button>
      <input type="text" name="search" class="Search-box" autocomplete="off">
    </form>
</div>

	
<div id="searchbox" class="container" style="margin: 250px auto auto; width: 800px">
	<form method="get" action="index.php" class = "Search">
      <button id="sr" class="Search-label" style="font-size: 30px" for="Search-box"><i class="fa fa-search"></i></button>
      <input type="text" name="map_search" class="Search-box" style="height: 60px; font-size: 2.0em" autocomplete="off">
    </form>
</div>

	

<?php 
	if(isset($_GET['map_search']))
	{
		$hospi_name = $_GET['map_search'];
		$hospi_name = preg_replace("/\s+/", "", $hospi_name);
		$sql = 'select * from hospital where Hospital_Name like "%'. $hospi_name .'%"';
		$result = $db->query($sql);

		
		
		?>
	<div id="map" style="margin:40px auto 150px auto; width:1000px;height:600px;"></div>
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=f722b2f37d3075fced8b4fa988359be7&libraries=services"></script>
<script>
		var container = document.getElementById('map');
		var options = {
			center: new daum.maps.LatLng(35.8343547, 127.1292019),
			level: 8
		};

		var map = new daum.maps.Map(container, options);
		// 주소-좌표 변환 객체를 생성합니다
		var geocoder = new daum.maps.services.Geocoder();
		
		<?php
			while($row = $result->fetch_array())
			{
				
		?>
		geocoder.addressSearch('<?php echo $row['Address']; ?>', function(result, status) {

		// 정상적으로 검색이 완료됐으면 
			 if (status === daum.maps.services.Status.OK) {

				var coords = new daum.maps.LatLng(result[0].y, result[0].x);

				// 결과값으로 받은 위치를 마커로 표시합니다
				var marker = new daum.maps.Marker({
					map: map,
					position: coords
				});

				// 인포윈도우로 장소에 대한 설명을 표시합니다
				var infowindow = new daum.maps.InfoWindow({
					content: '<div style="width:180px;text-align:center;padding:6px 0;"><?php echo $row['Hospital_Name']."<br>".$row['Call_Number']?></div>'
				});
				//infowindow.open(map, marker);
				 
				 daum.maps.event.addListener(marker, 'mouseover', makeOverListener(map, marker, infowindow));
				 daum.maps.event.addListener(marker, 'mouseout', makeOutListener(infowindow));
				
				 function makeOverListener(map, marker, infowindow) {
				return function() {
					infowindow.open(map, marker);
				};
			}

			// 인포윈도우를 닫는 클로저를 만드는 함수입니다 
			function makeOutListener(infowindow) {
				return function() {
					infowindow.close();
				};
			}
			
			}
			

		});
		
		<?php }?>
<?php	
	}
	else{
		
		$sql = 'select * from hospital';
		$result = $db->query($sql);

		?>
		<div id="map" style="margin:40px auto 150px auto; width:1000px;height:600px;"></div>
	<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=f722b2f37d3075fced8b4fa988359be7&libraries=services"></script>
	<script>
		var container = document.getElementById('map');
		var options = {
			center: new daum.maps.LatLng(35.8343547, 127.1292019),
			level: 8
		};

		var map = new daum.maps.Map(container, options);
		// 주소-좌표 변환 객체를 생성합니다
		var geocoder = new daum.maps.services.Geocoder();
		
		<?php
			while($row = $result->fetch_array())
			{
				
		?>
		geocoder.addressSearch('<?php echo $row['Address']; ?>', function(result, status) {

		// 정상적으로 검색이 완료됐으면 
			 if (status === daum.maps.services.Status.OK) {

				var coords = new daum.maps.LatLng(result[0].y, result[0].x);

				// 결과값으로 받은 위치를 마커로 표시합니다
				var marker = new daum.maps.Marker({
					map: map,
					position: coords
				});

				// 인포윈도우로 장소에 대한 설명을 표시합니다
				var infowindow = new daum.maps.InfoWindow({
					content: '<div style="width:180px;text-align:center;padding:6px 0;"><?php echo $row['Hospital_Name']."<br>".$row['Call_Number']?></div>'
				});
				//infowindow.open(map, marker);
				 
				 daum.maps.event.addListener(marker, 'mouseover', makeOverListener(map, marker, infowindow));
				 daum.maps.event.addListener(marker, 'mouseout', makeOutListener(infowindow));
				
				 function makeOverListener(map, marker, infowindow) {
				return function() {
					infowindow.open(map, marker);
				};
			}

			// 인포윈도우를 닫는 클로저를 만드는 함수입니다 
			function makeOutListener(infowindow) {
				return function() {
					infowindow.close();
				};
			}
			
			}
			

		});
		
		<?php }
	}
	?>
	
	
	

	</script>
</div>

	
</body>
</html>