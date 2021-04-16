<?php
function loadRegistrations($filepath)
{
    $jsondata = file_get_contents($filepath);
    $arr_data = json_decode($jsondata, true);
    return $arr_data;
}

function saveDataJson($filepath, $name, $email, $phone)
{
    try {
        $contact = array(
            'name' => $name,
            'email' => $email,
            'phone' => $phone
        );

        $arr_data = loadRegistrations($filepath);
        array_push($arr_data, $contact);
        $jsonData = json_encode($arr_data, JSON_PRETTY_PRINT);
        file_put_contents($filepath, $jsonData);
        echo "<span style='color: blue'>luu du lieu thanh cong !</span>";
    } catch (Exception $e) {
        echo "loi: ", $e->getMessage(), "<br>";
    }
}

$nameErr = null;
$emailErr = null;
$phoneErr = null;
$name = null;
$email = null;
$phone = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $has_error = false;

    if (empty($name)) {
        $nameErr = "Nhap ten!";
        $has_error = true;
    }
    if (empty($email)) {
        $emailErr = "nhap Email!";
        $has_error = true;
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Dinh dang Email sai (name@abc.abc)!";
            $has_error = true;
        }
    }
    if (empty($phone)) {
        $phoneErr = "Nhap so dien thoai!";
        $has_error = true;
    }
    if ($has_error == false) {
        saveDataJson("user.json", $name, $email, $phone);
        $name = null;
        $email = null;
        $phone = null;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form method="post">
    <fieldset>
        <legend>Dang ky nguoi dung</legend>
        <label for="name">Ten: </label><br>
        <input type="text" name="name" id="name">
        <span style="color: red"><?php echo $nameErr; ?></span> <br>
        <label for="email"> Email: </label><br>
        <input type="text" name="email" id="email">
        <span style="color: red"><?php echo $emailErr; ?></span> <br>
        <label for="phone"> So dien thoai : </label><br>
        <input type="text" name="phone" id="phone">
        <span style="color: red"><?php echo $phoneErr; ?></span> <br>
        <button type="submit">dang ky</button>
    </fieldset>
</form>
<?php
$loadData = loadRegistrations('user.json');
?>
<h2>danh sách đã đăng ký</h2>
<table>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
    </tr>
    <?php foreach ($loadData as $key): ?>
        <tr>
            <td><?php echo $key['name'] ?></td>
            <td><?php echo $key['email'] ?></td>
            <td><?php echo $key['phone'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>