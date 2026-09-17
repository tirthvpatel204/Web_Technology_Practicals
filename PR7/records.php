<?php

$file = __DIR__ . "/data/registrations.csv";

$records = [];

if (file_exists($file)) {
    $handle = fopen($file, "r");

    if ($handle !== false) {

        $headers = fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) === count($headers)) {
                $records[] = array_combine($headers, $row);
            }
        }

        fclose($handle);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Students</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container wide">

    <div class="records-header">

        <div>
            <div class="records-icon">✓</div>

            <h1>Registered <span>Students</span></h1>

            <p>
                <?php echo count($records); ?>
                registered student<?php echo count($records) != 1 ? "s" : ""; ?>
            </p>
        </div>

        <a href="index.php" class="new-registration">
            + New Registration
        </a>

    </div>

    <?php if (empty($records)): ?>

        <div class="empty-records">
            <div class="empty-icon">📋</div>
            <h2>No Records Found</h2>
            <p>There are no registered students yet.</p>

            <a href="index.php" class="new-registration">
                Create Registration →
            </a>
        </div>

    <?php else: ?>

        <div class="table-card">

            <div class="table-title">
                <div>
                    <h2>Registration Records</h2>
                    <p>Student information submitted through the registration form.</p>
                </div>

                <div class="record-count">
                    <?php echo count($records); ?> Records
                </div>
            </div>

            <div class="table-container">

                <table>

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Course</th>
                            <th>Message</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php foreach ($records as $index => $record): ?>

                        <tr>

                            <td>
                                <span class="number">
                                    <?php echo $index + 1; ?>
                                </span>
                            </td>

                            <td>
                                <strong class="student-name">
                                    <?= htmlspecialchars($record["Name"]) ?>
                                </strong>
                            </td>

                            <td>
                                <span class="email">
                                    <?= htmlspecialchars($record["Email"]) ?>
                                </span>
                            </td>

                            <td>
                                <?= htmlspecialchars($record["Phone"]) ?>
                            </td>

                            <td>
                                <span class="course">
                                    <?= htmlspecialchars($record["Course"]) ?>
                                </span>
                            </td>

                            <td>
                                <span class="message">
                                    <?= htmlspecialchars($record["Message"]) ?>
                                </span>
                            </td>

                            <td>
                                <span class="date">
                                    <?= htmlspecialchars($record["Date"]) ?>
                                </span>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>

    <?php endif; ?>

    <a href="index.php" class="records-link">
        ← Back to Registration
    </a>

</div>

</body>
</html>