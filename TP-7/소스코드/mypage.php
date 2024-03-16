<?php
session_start();
    $tns = "localhost:";
    $dns = "oci:dbname=".$tns.";charset=utf8";
    $username = "c##d201902656";
    $password = "8960";
    $searchword = $_GET['searchword'] ?? '';
    try {
        $conn = oci_connect($username, $password, "xe", "utf8");
        echo "conn is set (booklist.php)";
        echo $_SESSION['cno'];
    } catch (PDOException $e) {
        echo("에러 내용: ".$e -> getMessage());
    }
    if(!isset($_SESSION['cno'])) {
        echo "로그인 후 이용해주세요";
        echo "<script>location.href='login.html'</script>";
    }
?>

<!DOCTYPE html>
<html>
    <head></head>
    <body>
        <div>
            <p><a href="booklist.php">자료실</a></p>
            <h1>마이페이지</h1>
            <h4><a href='mypage.php?mode=rent'>대출현황</a>
            <a href='mypage.php?mode=reserve'>예약현황</a></h4>
        </div>  
        
        <table border="1">
            <th>선택</th>
            <th>ISBN</th>
            <th>제목</th> 
<?php if($_GET['mode'] == 'rent') { ?>
            <th>대출일자</th>
            <th>반납예정일</th>
            <th>연장횟수</th>

<?php
    $cno = $_SESSION['cno'];
    $sql = "SELECT * from EBOOK WHERE CNO = '$cno'"; 
    $stmt = oci_parse($conn, $sql);
    oci_execute($stmt);
} 
else {  
    $sql = "SELECT * from RESERVATION, EBOOK WHERE RESERVATION.CNO = {$_SESSION['cno']} and RESERVATION.ISBN = EBOOK.ISBN";
    $stmt = oci_parse($conn, $sql);
    oci_execute($stmt);
} ?>

            <tbody> 
<?php while(($row = oci_fetch_array($stmt)) != false) { ?>
            <tr>
            <td><input type="CheckBox"></td>
            <td><?=$row['ISBN']?></td>
            <td><?=$row['TITLE']?></td>
<?php if($_GET['mode'] == 'rent') { ?>
            <td><?=$row['DATERENTED']?></td>
            <td><?=$row['DATEDUE']?></td>
            <td><?=$row['EXTTIMES']?></td>
            </tr>
<?php } 
} ?>
            </tbody>
        </table>
    </body>
</html>