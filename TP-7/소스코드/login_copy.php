<?php
$tns = "localhost:";
$dns = "oci:dbname=".$tns.";charset=utf8";
$username = "c##d201902656";
$password = "8960";
$searchword = $_GET['searchword'] ?? '';
try {
    $conn = oci_connect($username, $password, "xe", "utf8");
} catch (PDOException $e) {
    echo("에러 내용: ".$e -> getMessage());
}

$cno = $_POST["cno"];
$password = $_POST["password"];
$sql = "SELECT * from CUSTOMER where CNO='$cno' and PASSWD='$password'";
$stmt = oci_parse($conn, $sql);
$ret = oci_execute($stmt);
$row = oci_fetch_array($stmt);
session_start();
if($row) {
    $_SESSION['cno'] = $cno;
    $_SESSION['password'] = $password;
    if(isset($_SESSION['cno']) && isset($_SESSION['password'])) {
        #header("Location : booklist.php?cno=getcno");
        echo "<script>location.href='booklist.php'</script>";
    }
    else {
        echo "<script>alert('세션 생성 실패');</script>";
        echo "<script>location.href='login.html'</script>";
    }
}
else {
    # echo "회원번호 또는 비밀번호가 잘못되었습니다";
    echo "<script>alert('회원번호 또는 비밀번호가 잘못되었습니다');</script>";
    echo "<script>location.href='login.html'</script>";
}
?>

