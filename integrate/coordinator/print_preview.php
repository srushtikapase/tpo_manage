<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Preview</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 20px;
        }
        iframe {
            width: 100%;
            height: 80vh;
            border: none;
        }
    </style>
</head>
<body>
    <h1>Print Preview</h1>
    <iframe src="generate_pdf.php" id="pdf_frame"></iframe>
    <br>
    <button onclick="window.print()">Print</button>
</body>
</html>
