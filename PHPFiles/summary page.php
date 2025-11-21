<?php
header('Content-Type: text/plain');

// Path to JSON file
$feedbackFile = __DIR__ . '/JSONFiles/feedback.json';

// Read file
if (!file_exists($feedbackFile)) {
    echo "No feedback data available.";
    exit;
}

$json = file_get_contents($feedbackFile);
$data = json_decode($json, true);

// Prepare summary variables
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


// Process each feedback entry
foreach ($data as $entry) {

    // Count gender
    if (isset($entry['gender']) && $entry['gender'] !== '') {
        if (isset($genderCount[$entry['gender']])) {
            $genderCount[$entry['gender']]++;
        }
    } else {
        $genderCount['not_say']++;
    }

    // Age
    if (isset($entry['age']) && is_numeric($entry['age'])) {
        $ageSum += intval($entry['age']);
        $ageCount++;
    }

    // Booth ratings
    if (isset($entry['boothRatings'])) {
        foreach ($entry['boothRatings'] as $booth => $rate) {
            if ($rate !== null && $rate !== "") {
                $boothRatings[$booth][] = intval($rate);
            }
        }
    }

    // Comments
    if (!empty($entry['comment'])) {
        $comments[] = $entry['comment'];
    }
}


// Summary calculations
$averageAge = $ageCount > 0 ? round($ageSum / $ageCount, 1) : "-";

function avgRating($arr) {
    return count($arr) > 0 ? round(array_sum($arr) / count($arr), 2) : "-";
}


// OUTPUT SUMMARY
echo "===== FEEDBACK SUMMARY =====\n\n";

echo "Total feedback entries: $totalFeedback\n\n";

echo "----- Gender Count -----\n";
echo "Female: " . $genderCount['female'] . "\n";
echo "Male: " . $genderCount['male'] . "\n";
echo "LGBTQIA+: " . $genderCount['lgbtqiaplus'] . "\n";
echo "Not prefer to say: " . $genderCount['not_say'] . "\n\n";

echo "----- Age Summary -----\n";
echo "Average Age: " . $averageAge . "\n\n";

echo "----- Booth Rating Averages -----\n";
echo "Booth 1 Average: " . avgRating($boothRatings['booth1']) . "\n";
echo "Booth 2 Average: " . avgRating($boothRatings['booth2']) . "\n";
echo "Booth 3 Average: " . avgRating($boothRatings['booth3']) . "\n";
echo "Booth 4 Average: " . avgRating($boothRatings['booth4']) . "\n\n";

echo "----- Comments -----\n";
if (count($comments) > 0) {
    foreach ($comments as $i => $c) {
        echo ($i+1) . ". " . $c . "\n";
    }
} else {
    echo "No comments.\n";
}

echo "\n============================\n";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>summary page</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Caprasimo&family=Instrument+Serif:ital@0;1&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./CSSFiles/Basestyle.css">
    <link rel="stylesheet" href="./CSSFiles/BD.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Leave Your Feedback Page</title>
</head>
<body>
    
</body>
</html>