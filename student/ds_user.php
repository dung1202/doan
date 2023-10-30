<?php
session_start();
include("../connectSQL.php");
$student_list = "";
$avatar = $_SESSION["avatar"];
// $code = "1 or 1=1";

if (isset($_POST["tk"]) and !empty($_POST["tk"])) {
    $code1 = '%' . (string)$_POST["tk"] . '%';
    $sql = "SELECT * FROM sv where username like '$code1' and code = 2";
    $sql_test = "SELECT * FROM sv where name like '$code1' and code = 2";
} else if (isset($_GET["code"]) and !empty($_GET["code"])) {
    $code = (string)$_GET["code"];
    // $code = "2 union select null,username,null,null,pass,null,null,null from sv";
    $sql = "SELECT * FROM sv where code = $code";
}
$id_user = $_SESSION["id"];
$result = $conn->query($sql);
$dem = 0;
while ($row = $result->fetch_array(MYSQLI_NUM)) {
    $dem++;
    $student_list .= "<tr>";
    $student_list .= "<th>" . $dem . "</th>";
    $student_list .= "<th>" . $row[1] . "</th>";
    if ($row[3] == 1) {
        $student_list .= "<th> Giáo viên </th>";
    } else {
        $student_list .= "<th> Sinh viên </th>";
    }

    $student_list .= "<th>" . $row[4] . "</th>";
    $student_list .= "<th>" . $row[5] . "</th>";
    $student_list .= "<th>" . $row[6] . "</th>";
    if ($id_user != $row[0]) {
        $student_list .= "<th> <a class='link' href='info_user.php?idguest=" . $row[0] . "'> Cá nhân </a> </th>";
    } else {
        $student_list .= "<th></th>";
    }
    $student_list .= "</tr>";
}
if (isset($_POST["tk"]) and !empty($_POST["tk"])) {
    $result = $conn->query($sql_test);
    $dem = 0;
    while ($row = $result->fetch_array(MYSQLI_NUM)) {
        $dem++;
        $student_list .= "<tr>";
        $student_list .= "<th>" . $dem . "</th>";
        $student_list .= "<th>" . $row[1] . "</th>";
        if ($row[3] == 1) {
            $student_list .= "<th> Giáo viên </th>";
        } else {
            $student_list .= "<th> Sinh viên </th>";
        }

        $student_list .= "<th>" . $row[4] . "</th>";
        $student_list .= "<th>" . $row[5] . "</th>";
        $student_list .= "<th>" . $row[6] . "</th>";
        if ($id_user != $row[0]) {
            $student_list .= "<th> <a class='link' href='info_user.php?idguest=" . $row[0] . "'> Cá nhân </a> </th>";
        } else {
            $student_list .= "<th></th>";
        }
        $student_list .= "</tr>";
    }
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Danh sách người dùng</title>
    <link rel="stylesheet" href="student.css">
    <script src="https://kit.fontawesome.com/50e4937a61.js" crossorigin="anonymous"></script>
</head>

<body class="conf">
    <div class="sidebar">
        <div class="code">Sinh viên</div>
        <ul class="menu">
            <li>
                <a href="student.php"> <img class="avatar" src="<?php echo $avatar ?>"><?php echo "<div>" . $_SESSION["username"] . "</div>"; ?> </a>
            </li>
            <li>
                <a href="doi_thongtin.php">Sửa thông tin</a>
            </li>
            <li>
                <a href="ds_user.php?code=2" class="chon">Danh sách người dùng</a>
            </li>
            <li>
                <a href="../logout.php">Log out</a>
            </li>
        </ul>
    </div>

    <div class="content">
        <div>
            <form method="POST">
                <div style="margin-top: 20px;">Tìm kiếm bạn</div>
                <input type="text" name="tk">
                <button>Gửi</button>
            </form>
            <?php
            if (isset($_POST['tk']) and !empty($_POST['tk'])) {
                $tk = $_POST['tk'];
            }
            ?>
        </div>
        <div class="title"><?php $text = empty($_POST['tk']) ? '<div>Danh sách người dùng</div>' : '<span>' . "Tìm kiếm: " . $tk . ' </span>';
                            echo $text; ?></div>
        <div class="students-list">
            <table>
                <tr>
                    <th>STT</th>
                    <th>Tên đăng nhập</th>
                    <th>Chức vụ</th>
                    <th>Họ và tên</th>
                    <th>SĐT</th>
                    <th>Email</th>
                    <th></th>
                    <th></th>
                </tr>
                <?php
                echo $student_list;
                ?>
            </table>
        </div>
    </div>

</body>

</html>