<?php
session_start();
include("../connectSQL.php");

$err = "";
$id = $_SESSION["id"];
$sql = "SELECT * FROM sv WHERE id = '$id'";
$result = $conn->query($sql);
$row = $result->fetch_array(MYSQLI_NUM);
$name = $row[4];
$username = $row[1];
$phonenumber = $row[5];
$email = $row[6];
$avatar = "../avatar/" . $row[7];
$_SESSION["avatar"] = $avatar;

$sql2 = "SELECT * FROM sv";
$result2 = $conn->query($sql2);
$soT = $soS = 0;
$idmax = 0;
while ($row = $result2->fetch_array(MYSQLI_NUM)) {
    $loai = substr($row[1], 0, 7);
    if ($loai === 'teacher') {
        if (substr($row[1], 7, strlen($row[1]) - 7) > $soT)
            $soT = substr($row[1], 7, strlen($row[1]) - 7);
    } else if ($loai === 'student') {
        if (substr($row[1], 7, strlen($row[1]) - 7) > $soS)
            $soS = substr($row[1], 7, strlen($row[1]) - 7);
    }
    $idmax = $row[0];
}
$soT++;
$soS++;
$idmax++;

if (isset($_POST["update"])) {
    $email = $_POST["email"];
    $username = $_POST['type'];
    $name = $_POST['name'];
    $mk1 = $_POST['mk1'];
    $mk2 = $_POST['mk2'];

    if (substr($username, 0, 7) === 'student') {
        $type = 2;
    } else $type = 1;

    if (empty($email) or empty($name) or empty($mk1) or empty($mk2)) {
        $err = "Thiếu thông tin";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $err = "Sai định dạng email";
    } elseif ($mk1 !== $mk2) {
        $err = "Mật khẩu xác nhận không khớp";
    } else {
        $mk1 = (string)md5(trim($mk1));
        $sql = "INSERT INTO sv (id, username, pass, code, name, SDT, email, avatar) values ('" . $idmax . "','" . $username . "','" . $mk1 . "','" . $type . "','" . $name . "','','" . $email . "','')";
        $res = $conn->query($sql);
        header("location: ./me.php");
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Đổi thông tin</title>
    <link rel="stylesheet" href="student.css">
    <script src="https://kit.fontawesome.com/50e4937a61.js" crossorigin="anonymous"></script>
</head>

<body class="conf">
    <div class="sidebar">
        <div class="code">Giáo viên</div>
        <ul class="menu">
            <li>
                <a href="me.php"> <img class="avatar" src="<?php echo $avatar ?>">
                    <?php echo "<div>" . $_SESSION["username"] . "</div>"; ?> </a>
            </li>
            <li>
                <a href="teacher.php">Danh sách người dùng</a>
            </li>
            <li>
                <a href="them_ngD.php" class="chon">Thêm người dùng</a>
            </li>
            <li>
                <a href="doi_thongtin.php">Đổi thông tin</a>
            </li>
            <li>
                <a href="../logout.php">Log out</a>
            </li>
        </ul>
    </div>

    <div class="content">
        <!-- <div class="row">
            <a href="student.php" class="add" style="float:right;">Trở lại</a>
        </div> -->

        <form action="" method="post">
            <p style="text-align: center;">Thêm người dùng mới</p>
            <label for="username">Tên đăng nhập:</label><br>
            <select name='type' id='luachon'>
                <option value='<?php echo 'teacher' . $soT; ?>' selected='selected'><?php echo 'teacher' . $soT; ?></option>
                <option value='<?php echo 'student' . $soS; ?>'><?php echo 'student' . $soS; ?></option>
            </select><br>
            <label for="name">Họ và tên:</label><br>
            <input type="text" name="name"><br>
            <label for="email">Email:</label><br>
            <input type="text" name="email"><br>
            <label for="mk1">Nhập mật khẩu:</label><br>
            <input type="password" onmouseover="change_t(this)" onmouseout="change_p(this)" name="mk1"><br>
            <label for="mk2">Nhập lại mật khẩu:</label><br>
            <input type="password" onmouseover="change_t(this)" onmouseout="change_p(this)" name="mk2"><br>
            <?php
            if (!(empty($err))) {
                echo "<div class='error' style='background: black; padding: 10px; text-align: center; border-radius:10px;'>" . $err . "</div>";
            }
            ?>
            <input type="submit" value="Lưu" name="update">
        </form>
    </div>
    <script>
        function change_t(a) {
            a.type = "text"
        }

        function change_p(a) {
            a.type = "password"
        }
    </script>


</body>

</html>