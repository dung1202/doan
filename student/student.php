<?php
session_start();
include("../connectSQL.php");

$id_dn = $_SESSION["id"];
$ssid = (string)$_COOKIE['session'];
$sqlkie = "INSERT INTO session(id_dn,session) VALUES ('$id_dn','$ssid')";
$resultkie = $conn->query($sqlkie);


$id = $_SESSION["id"];
$username = $_SESSION["username"];
$id_guest = "";
$name = $username_guest = $email = $phonenumber = "";
$messagelist = "";
$id_guest = $id;
$sql = "SELECT * FROM sv WHERE id = '$id_guest'";
$result = $conn->query($sql);
$row = $result->fetch_array(MYSQLI_NUM);
$username_guest = $row[1];
$name = $row[4];
$phonenumber = $row[5];
$email = $row[6];
$code = $row[3];
$avatar = "../avatar/" . $row[7];
$_SESSION["avatar"] = $avatar;


$sqltn = "SELECT * FROM nt WHERE id_nhan='$id_guest'";
$result = $conn->query($sqltn);
$row = $result->num_rows;
while ($row = $result->fetch_array(MYSQLI_NUM)) {
    $sqlmess = "SELECT * FROM sv WHERE id = '$row[1]'";
    $result1 = $conn->query($sqlmess);
    $row1 = $result1->fetch_array(MYSQLI_NUM);
    $messagelist .= "<form action='' method='POST'>";
    $messagelist .= "<span>" . $row1[1] . "</span><br>";
    $messagelist .= "<input type='text' name='message' value='" . $row[3] . "' >";
    $messagelist .= "<span style='font-size:0px'> . $row[3] . </span>";
    $messagelist .= "<input type='hidden' name='id' value='" . $row[0] . "' />";
    if ($row[1] == $id) {
        $messagelist .= "<input type='submit' name='edit' value='Sửa'>";
        $messagelist .= "<input type='submit' name='delete' value='Xóa'>";
    }
    $messagelist .= "</form>";
}


if (isset($_POST["send"])) {
    $nd = $_POST["newmessage"];
    $sl =  "SELECT * FROM nt";
    $data = $conn->query($sl);
    $sl = (int)$data->num_rows + 1;
    if (!(empty($nd))) {
        $sql = "INSERT INTO nt(id,id_gui,id_nhan,nd) VALUES ('$sl','$id','$id_guest','$nd')";
        if ($result = $conn->query($sql)) {
            header("location: ./student.php");
        } else $err = "Không gửi được";
    } else {
        header("location: ./student.php");
    }
}

if (isset($_POST["edit"])) {
    $id_gui = $_POST["id"];
    $nd = $_POST["message"];
    if (!empty($nd)) {
        $sql = "UPDATE nt SET nd='$nd' WHERE id='$id_gui'";
        if ($result = $conn->query($sql)) {
            header("location: ./student.php");
        } else $err = "Không sửa được";
    } else {
        header("location: ./student.php");
    }
}

if (isset($_POST["delete"])) {
    $id_gui = $_POST["id"];
    $sql = "DELETE FROM nt WHERE id='$id_gui'";
    if ($result = $conn->query($sql)) {
        header("location: ./student.php");
    } else $err = "Không xóa được";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Trang cá nhân</title>
    <link rel="stylesheet" href="student.css">
    <script src="https://kit.fontawesome.com/50e4937a61.js" crossorigin="anonymous"></script>
</head>

<body class="conf">
    <div class="sidebar">
        <div class="code">Sinh viên</div>
        <ul class="menu">
            <li>
                <a href="student.php" class="chon"> <img class="avatar" src="<?php echo $avatar ?>"><?php echo "<div>" . $_SESSION["username"] . "</div>"; ?> </a>
            </li>
            <li>
                <a href="doi_thongtin.php">Sửa thông tin</a>
            </li>
            <li>
                <a href="ds_user.php?code=2">Danh sách người dùng</a>
            </li>
            <li>
                <a href="../logout.php">Log out</a>
            </li>
        </ul>
    </div>

    <div class="container">
        <div class="row" style="display: flex;">
            <div class="add" style="float:right;">
                <?php
                $ssid = $_COOKIE['session'];
                //$sql = "SELECT * FROM session where session = '$ssid' AND SUBSTRING((SELECT pass FROM sv WHERE username = 'student1'), 1, 1) = 'f'";
                $sql = "SELECT * FROM session where session = '$ssid'";
                $res = $conn->query($sql);
                $row1 = $res->fetch_array(MYSQLI_NUM);
                if (!empty($row1[0])) {
                    $tbao = 'Chào mừng bạn';
                } else {
                    $tbao = '';
                }
                echo $tbao;
                ?>
            </div>
        </div>
        <div class="row">
            <img class="avatar1" src="<?php echo $avatar ?>">
            <p> Họ và tên: <?php echo $name; ?></p>
            <p> Chức vụ:
                <?php
                if ($code == 1) {
                    echo 'Giáo viên';
                } else {
                    echo 'Sinh viên';
                }
                ?>
            </p>
            <p> Số điện thoại: <?php echo $phonenumber; ?></p>
            <p> Email: <?php echo $email; ?></p>

        </div>
        <div class="row" style="height:200px; overflow: auto;">
            <?php
            echo $messagelist;
            ?>
        </div>
        <div class="row">
            <form action="" method="POST">
                <input type="text" name="newmessage">
                <input type="submit" name="send" value="Gửi">
            </form>
        </div>
    </div>

</body>

</html>