<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report Card - <?php echo \App\Core\Security::escape($student['first_name'] . ' ' . $student['last_name']); ?></title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 40px; color: #333; line-height: 1.6; }
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 28px; text-transform: uppercase; }
        .student-info { display: flex; justify-content: space-between; margin-bottom: 30px; border: 1px solid #ddd; padding: 15px; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #000; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; text-transform: uppercase; font-size: 14px; }
        .summary-box { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
        .summary-item { border: 1px solid #000; padding: 15px; text-align: center; }
        .summary-label { font-size: 12px; text-transform: uppercase; color: #666; margin-bottom: 5px; }
        .summary-value { font-size: 20px; font-weight: bold; }
        .footer { margin-top: 50px; }
        .signature-line { border-top: 1px solid #000; width: 250px; margin-top: 40px; padding-top: 10px; font-weight: bold; }
        .comments-section { border: 1px solid #ddd; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        @media print { .no-print { display: none; } body { padding: 0; } }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #2563eb; color: white; border: none; border-radius: 5px; cursor: pointer;">Print Report Card</button>
    </div>

    <div class="header">
        <h1><?php echo \App\Core\Security::escape($_SESSION['school_name'] ?? 'UGANDA SECONDARY SCHOOL'); ?></h1>
        <p><?php echo \App\Core\Security::escape($_SESSION['school_address'] ?? 'P.O. Box 123, Kampala, Uganda'); ?></p>
        <h2 style="margin-top: 15px; background: #eee; display: inline-block; padding: 5px 20px;">STUDENT PROGRESS REPORT</h2>
    </div>

    <div class="student-info">
        <div>
            <strong>NAME:</strong> <?php echo \App\Core\Security::escape($student['first_name'] . ' ' . $student['last_name']); ?><br>
            <strong>ADM NO:</strong> <?php echo \App\Core\Security::escape($student['admission_number']); ?><br>
            <strong>GENDER:</strong> <?php echo strtoupper($student['gender']); ?>
        </div>
        <div style="text-align: right;">
            <strong>CLASS:</strong> <?php echo \App\Core\Security::escape($student['class_name'] . ' ' . $student['stream_name']); ?><br>
            <strong>TERM:</strong> Term 1 2024<br>
            <strong>POSITION:</strong> <?php echo $position; ?>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Subject</th>
                <th>Mid Term (40)</th>
                <th>End Term (60)</th>
                <th>Total (100)</th>
                <th>Grade</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($processedMarks as $name => $data): ?>
                <tr>
                    <td><strong><?php echo \App\Core\Security::escape($name); ?></strong></td>
                    <td><?php echo $data['mid_term'] ?? '-'; ?></td>
                    <td><?php echo $data['end_of_term'] ?? '-'; ?></td>
                    <td><strong><?php echo $data['total']; ?></strong></td>
                    <td><strong><?php echo $data['grade']; ?></strong></td>
                    <td style="font-size: 12px; font-style: italic;">
                        <?php
                        if ($data['total'] >= 80) echo "Excellent performance";
                        elseif ($data['total'] >= 70) echo "Very good";
                        elseif ($data['total'] >= 60) echo "Good effort";
                        elseif ($data['total'] >= 50) echo "Fair performance";
                        else echo "Needs to put in more effort";
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="summary-box">
        <div class="summary-item">
            <div class="summary-label">Total Marks</div>
            <div class="summary-value"><?php echo $totalScore; ?></div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Average Mark</div>
            <div class="summary-value"><?php echo number_format($average, 1); ?>%</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Position in Stream</div>
            <div class="summary-value"><?php echo $position; ?></div>
        </div>
    </div>

    <div class="comments-section">
        <p><strong>CLASS TEACHER'S COMMENT:</strong><br>
        <span style="font-style: italic;">A disciplined and hardworking student. Maintain the focus.</span></p>
    </div>

    <div class="comments-section">
        <p><strong>HEAD TEACHER'S COMMENT:</strong><br>
        <span style="font-style: italic;">Promising results. Keep up the good work.</span></p>
    </div>

    <div class="footer">
        <div style="display: flex; justify-content: space-between;">
            <div>
                <div class="signature-line">Class Teacher's Signature</div>
            </div>
            <div style="text-align: right;">
                <div class="signature-line">Head Teacher's Signature & Stamp</div>
            </div>
        </div>
        <p style="text-align: center; margin-top: 50px; font-size: 10px; color: #999;">
            Report generated on <?php echo date('d/m/Y H:i:s'); ?>. System by Jules.
        </p>
    </div>
</body>
</html>
