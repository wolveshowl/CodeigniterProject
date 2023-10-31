<?


function print_rr($arr){
    print "<pre>";
    print_r($arr);
    print "</pre>";
}
function str_delimiter_merge($delimiter,$str,$str2){
	$result=$str.$delimiter.$str2;
	if(!$str){$result=$str2;}
	if(!$str2){$result=$str;}
	return $result;
}
function filecheck($filename, $type) {
	// 파일명 검증
	if (empty($filename)) {
		return false;
	}
	// 확장자 분리
	$ext = pathinfo($filename,PATHINFO_EXTENSION);
	// 파일 형식 설정
	$haystack = array();
	switch($type) {
		case "img":
			$haystack = array("jpg", "jpeg", "png", "gif");
			break;
		case "excel":
			$haystack = array("xlsx", "xls", "csv");
			break;
		// 필요한 파일형식 조건 추가
	}
	// 확장자 검증
	if (in_array($ext, $haystack)) {
		return true;
	}
	return false;
}
function pagingNumber($total,$pageset,$page_paging,$status=false){
	$number=array();
	$num=$total - ($pageset*($page_paging-1));

	for($i=0;$i<$pageset;$i++){
		$no=$num-$i;
		if($no > 0){array_push($number,$no);}
	}		
	return $number;
}

function alert($msg,$url=""){
	echo "<script type='text/javascript'>alert('".$msg."');";
	if ($url)
		echo "location.replace('".$url."');";
	else
		echo "history.go(-1);";
	echo "</script>";
	exit;
}

//php 숫자 한글 변환 함수
function number2hangul($number){
	$num = array('', '일', '이', '삼', '사', '오', '육', '칠', '팔', '구');
	$unit4 = array('', '만', '억', '조', '경');
	$unit1 = array('', '십', '백', '천');

	$res = array();

	$number = str_replace(',','',$number);
	$split4 = str_split(strrev((string)$number),4);

	for($i=0;$i<count($split4);$i++){
			$temp = array();
			$split1 = str_split((string)$split4[$i], 1);
			for($j=0;$j<count($split1);$j++){
					$u = (int)$split1[$j];
					if($u > 0) $temp[] = $num[$u].$unit1[$j];
			}
			if(count($temp) > 0) $res[] = implode('', array_reverse($temp)).$unit4[$i];
	}
	return implode('', array_reverse($res));
}

?>