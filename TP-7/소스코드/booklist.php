<?php
    session_start();
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
?>

<?php
    function tryRental() {
        $username = "c##d201902656";
        $password = "8960";
        $conn = oci_connect($username, $password, "xe", "utf8");
        $sql = "SELECT * FROM EBOOK WHERE CNO = {$_SESSION['cno']}";
        $stmt = oci_parse($conn, $sql);
        $ret = oci_execute($stmt);
        $num_rows = OCIFetchStatement($stmt,$results);
        if($num_rows > 2) {
            echo "<script>alert('도서 대출 한도 초과 (최대 3권)');</script>";
        } else {
            $isbn = $_GET['rental'];
            echo "<script>alert('$isbn 대출되었습니다');</script>";
            $sql1 = "UPDATE EBOOK SET CNO = {$_SESSION['cno']} WHERE ISBN='$isbn'";
            $sql2 = "UPDATE EBOOK SET DATERENTED = date('y/m/d') WHERE ISBN='$isbn'";
            $sql3 = "UPDATE EBOOK SET DATEDUE = date('y/m/d', strtotime(date('y/m/d').'+10 days')) WHERE ISBN='$isbn'";
            $sql4 = "UPDATE EBOOK SET EXTTIMES = 0 WHERE ISBN='$isbn'";
            $upd = "UPDATE EBOOK SET (CNO, DATERENTED, DATEDUE, EXTTIMES) = ( {$_SESSION['cno']}, date('y/m/d'), 
            date('y/m/d', strtotime(date('y/m/d').'+10 days')), 0) WHERE ISBN='$isbn'";
            
            
        }
    }

        if(array_key_exists('rental', $_GET)){ tryRental(); }
?>



<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
    </head>   
    <body>
        <div>
            <p><a href='mypage.php?mode=rent'>마이페이지</a></p>
            <h1>자료실</h1>
            <h4><a href='booklist.php'>도서목록</a>
            <a href='booksearch.php'>도서검색</a></h4>
            
        </div>
        <div>
            <table border="1" >
                <th>제목</th>
                <th>출판사</th>
                <th>발행년도</th>
                <th>저자</th>
                <th>대출/예약</th>
                <tbody>
<?php 
$sql = "SELECT * from EBOOK ORDER BY ISBN";
$stmt = oci_parse($conn, $sql);
$ret = oci_execute($stmt);

while($row = oci_fetch_array($stmt)) {
?>
                    <tr>
                        <td><?=$row['TITLE']?></td>
                        <td><?=$row['PUBLISHER']?></td>
                        <td><?=$row['YEAR']?></td>
                    <?php 
                        $ISBN = $row['ISBN'];
                        $sql_author = "SELECT * from AUTHOR where ISBN = '$ISBN'";
                        $stmt_author = oci_parse($conn, $sql_author);
                        $ret_author = oci_execute($stmt_author);
                        $row_author = oci_fetch_array($stmt_author);
                        if($row_author) {
                    ?>
                        <td><?=$row_author['AUTHOR']?></td>
                    <?php } else { ?>
                        <td>UNKNOWN</td>
                    <?php } ?>
                    <?php 
                        if($row['DATERENTED']) {
                    ?>
                        <td><input type="button" name="reserve" value="예약" style="WIDTH: 60pt; COLOR:RED"></td>
                    <?php } else { ?>
                        <td>
                            <form action="booklist.php" method="get">
                            <button type="submit" name="rental" value=<?=$row['ISBN']?> onClick="tryRental()" style="WIDTH:60pt;COLOR:BLUE">대출</button>
                            </form>
                        </td>
                        
                    <?php } ?>
                   </tr>
<?php } ?>
                </tbody>
            </table>
        </div>
    </body>
</html>