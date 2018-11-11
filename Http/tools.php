<?php
	define ("MAIN_PAGE", "main.php");
	define ("NUM_LINES", 10); // 한 페이지에 출력할 게시글 수
	define ("NUM_PAGE_LINKS", 10); // 한 페이지에 출력할 페이지 링크 수
	// NUM_PAGE_LINKS(5): startPage 값은 1, 6, 11, 16, ...
	// NUM_PAGE_LINKS(10): startPage 값은 1, 11, 21, 31, 41,...

	function requestValue($name) {
		return isset($_REQUEST[$name])?$_REQUEST[$name]:"";
	}

	function errorBack($msg) {
?>
		<!doctype html>
		<html>
		<head>
				<meta charset="utf-8">
		</head>
		<body>
				<script>
					alert('<?= $msg ?>');
					history.back();
				</script>
		</body>
		</html>
<?php		
		exit();
	}

	function okGo($msg, $url) {
?>
		<!doctype html>
		<html>
		<head>
				<meta charset="utf-8">
		</head>
		<body>
				<script>
					alert('<?= $msg ?>');
					location.href='<?=$url?>';

				</script>
		</body>
		</html>

<?php		
		exit();
	}
?>

