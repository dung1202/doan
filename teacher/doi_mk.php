<?php
session_start();
include("../connectSQL.php");

$id = $_SESSION["id"];
$sql = "SELECT * FROM sv WHERE id = '$id'";
$result = $conn->query($sql);
$row = $result->fetch_array(MYSQLI_NUM);
$name = $row[4];
$username = $row[1];
$phonenumber = $row[5];
$email = $row[6];
$avatar = "../avatar/". $row[7];
$_SESSION["avatar"] = $avatar;

$avatar = $_SESSION["avatar"];
$id = $_SESSION["id"];
if (isset($_POST["chpwd"])) {
    $oldpwd = $_POST["oldpwd"];
    $newpwd = $_POST["newpwd"];

    if (empty($oldpwd) || empty($newpwd)) {
        $err = "Cần nhập đầy đủ";
    } else {
        $encrypt_oldpwd = md5($oldpwd);
        $encrypt_newpwd = md5($newpwd);
        $sql = "SELECT * FROM sv WHERE id = '$id' AND pass = '$encrypt_oldpwd'";
        $result = $conn->query($sql);
        $count = $result->num_rows;

        if ($count > 0) {
            $sql = "UPDATE sv SET pass = '$encrypt_newpwd' WHERE id = '$id'";
            if ($conn->query($sql) == true) {
                $err = "Thành công";
            } else {
                $err = "Lỗi, thử lại";
            }
        } else {
            $err = "Sai mật khẩu";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Đổi mật khẩu</title>
    <link rel="stylesheet" href="student.css">
    <script src="https://kit.fontawesome.com/50e4937a61.js" crossorigin="anonymous"></script>
</head>
</head>

<body class="conf">
    <div class="sidebar">
        <div class="code">Sinh viên</div>
        <ul class="menu">
            <li>
                <a href="me.php"> <img class="avatar" src="<?php echo $avatar?>"><?php echo "<div>" . $_SESSION["username"] . "</div>"; ?> </a>
            </li>
            <li>
                <a href="teacher.php">Danh sách người dùng</a>
            </li>
            <li>
                <a href="them_ngD.php">Thêm người dùng</a>
            </li>
            <li>
                <a href="doi_thongtin.php">Đổi thông tin</a>
            </li>
            <li>
                <a href="doi_mk.php" class="chon">Đổi mật khẩu</a>
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
            <label for="oldpwd">Mật khẩu cũ:</label><br>
            <input type="password" name="oldpwd" onmouseover="change_t(this)" onmouseout="change_p(this)"><br>
            <label for="newpwd">Mật khẩu mới:</label><br>
            <input type="password" name="newpwd" onmouseover="change_t(this)" onmouseout="change_p(this)"><br>
            <?php
            if (!(empty($err))) {
                echo "<div class='error' style='background: black; padding: 10px; text-align: center; border-radius:10px;'>" . $err . "</div>";
            }
            ?>
            <input type="submit" value="Lưu" name="chpwd">
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