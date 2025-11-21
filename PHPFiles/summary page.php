<?php
// Path to JSON file
$feedbackFile = __DIR__ . '/JSONFiles/feedback.json';

if (!file_exists($feedbackFile)) {
    echo "<p>No feedback data available.</p>";
    exit;
}

$json = file_get_contents($feedbackFile);
$data = json_decode($json, true);

// Prepare summary
$totalFeedback = count($data);

$genderCount = [
    'female' => 0,
    'male' => 0,
    'lgbtqiaplus' => 0,
    'not_say' => 0
];

$ageSum = 0;
$ageCount = 0;

$boothRatings = [
    'booth1' => [],
    'booth2' => [],
    'booth3' => [],
    'booth4' => []
];

$comments = [];

foreach ($data as $entry) {

    // Gender
    if (!empty($entry['gender']) && isset($genderCount[$entry['gender']])) {
        $genderCount[$entry['gender']]++;
    } else {
        $genderCount['not_say']++;
    }

    // Age
    if (!empty($entry['age']) && is_numeric($entry['age'])) {
        $ageSum += intval($entry['age']);
        $ageCount++;
    }

    // Ratings
    if (isset($entry['boothRatings'])) {
        foreach ($entry['boothRatings'] as $booth => $rate) {
            if ($rate !== "" && $rate !== null) {
                $boothRatings[$booth][] = intval($rate);
            }
        }
    }

    // Comments
    if (!empty($entry['comment'])) {
        $comments[] = $entry['comment'];
    }
}

$averageAge = $ageCount > 0 ? round($ageSum / $ageCount, 1) : "-";

function avgRating($arr) {
    return count($arr) ? round(array_sum($arr) / count($arr), 2) : "-";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Feedback Summary</title>

<!-- Bootstrap 5 CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body { background: #f1f3f9; }
    .card { border-radius: 15px; }
</style>

</head>
<body>

<div class="container my-5">

    <h1 class="text-center mb-4 fw-bold">📊 Feedback Summary</h1>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">

        <div class="col-md-4">
            <div class="card p-3 shadow-sm text-center">
                <h5>Total Feedback</h5>
                <h2 class="text-primary"><?= $totalFeedback ?></h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 shadow-sm text-center">
                <h5>Average Age</h5>
                <h2 class="text-success"><?= $averageAge ?></h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 shadow-sm text-center">
                <h5>Total Comments</h5>
                <h2 class="text-warning"><?= count($comments) ?></h2>
            </div>
        </div>

    </div>

    <!-- Gender -->
    <div class="card shadow-sm mb-4 p-3">
        <h4>Gender Distribution</h4>
        <ul class="list-group">
            <li class="list-group-item">Female: <?= $genderCount['female'] ?></li>
            <li class="list-group-item">Male: <?= $genderCount['male'] ?></li>
            <li class="list-group-item">LGBTQIA+: <?= $genderCount['lgbtqiaplus'] ?></li>
            <li class="list-group-item">Not prefer to say: <?= $genderCount['not_say'] ?></li>
        </ul>
    </div>

    <!-- Booth Ratings -->
    <div class="card shadow-sm mb-4 p-3">
        <h4>Booth Rating Averages</h4>
        <table class="table table-bordered mt-2">
            <thead class="table-light">
                <tr>
                    <th>Booth</th>
                    <th>Average Rating</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>Booth 1</td><td><?= avgRating($boothRatings['booth1']) ?></td></tr>
                <tr><td>Booth 2</td><td><?= avgRating($boothRatings['booth2']) ?></td></tr>
                <tr><td>Booth 3</td><td><?= avgRating($boothRatings['booth3']) ?></td></tr>
                <tr><td>Booth 4</td><td><?= avgRating($boothRatings['booth4']) ?></td></tr>
            </tbody>
        </table>
    </div>

    <!-- Comments -->
    <div class="card shadow-sm p-3 mb-5">
        <h4>Comments</h4>
        <?php if ($comments): ?>
            <ul class="list-group mt-2">
                <?php foreach ($comments as $c): ?>
                    <li class="list-group-item"><?= htmlspecialchars($c) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No comments.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
