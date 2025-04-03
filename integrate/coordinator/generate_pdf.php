<?php
// PDFShift API key
$apiKey = 'sk_6fb28fc9e0da3a7b875ad2716d4053344184f881'; // Replace with your PDFShift API key

// Create HTML content
$html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Job Listings</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <h1>Job Listings</h1>
    <table>
        <thead>
            <tr>
                <th>Company</th>
                <th>Job Title</th>
                <th>Description</th>
                <th>Skills</th>
                <th>Domain</th>
                <th>Position</th>
                <th>Experience</th>
                <th>Salary</th>
                <th>Openings</th>
                <th>Eligibility</th>
                <th>Shift</th>
                <th>Schedule</th>
            </tr>
        </thead>
        <tbody>';

include('../includes/db.php');
$result = $conn->query("SELECT * FROM jobs");
while ($row = $result->fetch_assoc()) {
    $html .= '<tr>
        <td>' . htmlspecialchars($row['comname']) . '</td>
        <td>' . htmlspecialchars($row['title']) . '</td>
        <td>' . htmlspecialchars($row['description']) . '</td>
        <td>' . htmlspecialchars($row['skills']) . '</td>
        <td>' . htmlspecialchars($row['domain']) . '</td>
        <td>' . htmlspecialchars($row['position']) . '</td>
        <td>' . htmlspecialchars($row['experience']) . '</td>
        <td>' . htmlspecialchars($row['salary']) . '</td>
        <td>' . htmlspecialchars($row['openings']) . '</td>
        <td>' . htmlspecialchars($row['eligibility']) . '</td>
        <td>' . htmlspecialchars($row['shift']) . '</td>
        <td>' . htmlspecialchars($row['schedule']) . '</td>
    </tr>';
}

$html .= '</tbody>
    </table>
</body>
</html>';

// Prepare data for PDFShift API
$data = [
    'source' => $html,
    'landscape' => false
];

// API endpoint
$url = 'https://api.pdfshift.io/v3/convert';

// API request options
$options = [
    'http' => [
        'header'  => "Content-Type: application/json\r\n" .
                     "Authorization: Bearer $apiKey\r\n",
        'method'  => 'POST',
        'content' => json_encode($data),
    ],
];

// Create a stream context
$context  = stream_context_create($options);

// Make the API request
$response = file_get_contents($url, false, $context);

if ($response === FALSE) {
    die('Error generating PDF');
}

// Serve the PDF file to the browser for download
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="Job_Listings.pdf"');
echo $response;
