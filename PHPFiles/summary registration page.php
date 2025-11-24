<?php
header('Content-Type: text/html; charset=utf-8');

$regFile = __DIR__ . '/../JSONFiles/registration_data.json';

if (!file_exists($regFile)) {
    die("<h2>No registration data found.</h2>");
}

$json = file_get_contents($regFile);
$data = json_decode($json, true);

$total = count($data);

$userTypes = [];
$interestsCount = [
    "news" => 0,
    "event" => 0
];

foreach ($data as $entry) {

    $type = $entry['usertype'] ?? 'ไม่ระบุ';
    if (!isset($userTypes[$type])) {
        $userTypes[$type] = 0;
    }
    $userTypes[$type]++;

    if (isset($entry['interests']) && is_array($entry['interests'])) {
        foreach ($entry['interests'] as $i) {
            if (isset($interestsCount[$i])) {
                $interestsCount[$i]++;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Registration Summary</title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <style>
        body {
            background: #f4f6f9;
        }
        .card {
            border-radius: 15px;
        }
        .header-box {
            background: #fa6aeeff;
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 25px;
        }
        table {
            background: white;
        }
    </style>
</head>

<body class="container py-4">

<div class="header-box text-center">
    <h2>Registration Summary</h2>
    <p>Total Registrations: <strong><?= $total ?></strong></p>
</div>

<div class="row g-4">

    <div class="col-md-4">
        <div class="card shadow p-3">
            <h4>ประเภทผู้ใช้</h4>
            <ul class="list-group mt-3">
                <?php foreach ($userTypes as $type => $count): ?>
                    <li class="list-group-item">
                        <?= htmlspecialchars($type) ?> : <?= $count ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="card shadow p-3 mt-4">
            <h4>ความสนใจ</h4>
            <ul class="list-group mt-3">
                <li class="list-group-item">ข่าวสาร (News): <?= $interestsCount['news'] ?></li>
                <li class="list-group-item">กิจกรรม (Event): <?= $interestsCount['event'] ?></li>
            </ul>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow p-3">
            <h4>รายละเอียดผู้ลงทะเบียน</h4>
            <table class="table table-striped mt-3">
                <thead>
                <tr>
                    <th>#</th>
                    <th>ชื่อ - นามสกุล</th>
                    <th>อีเมล</th>
                    <th>โทร</th>
                    <th>ประเภท</th>
                    <th>ความสนใจ </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $i => $row): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= ($row['firstname'] ?? '') . " " . ($row['lastname'] ?? '') ?></td>
                        <td><?= $row['email'] ?? '-' ?></td>
                        <td><?= $row['phone'] ?? '-' ?></td>
                        <td><?= $row['usertype'] ?? '-' ?></td>
                        <td>
                            <?php
                            if (!empty($row['interests'])) {
                                echo implode(", ", $row['interests']);
                            } else {
                                echo "-";
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>
