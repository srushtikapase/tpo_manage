<?php
session_start();
include('../includes/db.php');
include('../includes/functions.php');

// Check if the admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Step 1: Insert records into performance_tracking if they don't exist
$insert_sql = "INSERT INTO performance_tracking (application_id, aptitude, technical_interview, offer_letter, placed, rejected)
               SELECT a.id, 'pending', 'pending', 'pending', 'pending', 'no'
               FROM applications a
               LEFT JOIN performance_tracking pt ON a.id = pt.application_id
               WHERE a.status = 'accepted' AND pt.application_id IS NULL";

if (!$conn->query($insert_sql)) {
    die("Error inserting data: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performance Tracking</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        .progress-container {
            width: 90%;
            max-width: 1200px;
            background-color: white;
            border-radius: 20px;
            padding: 30px;
            margin: 20px auto;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        .progress-bar {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            position: relative;
            margin-bottom: 50px;
        }
        .milestone {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            width: 18%;
            z-index: 2;
            position: relative;
        }
        .milestone-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #e0e0e0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
            transition: all 0.3s ease;
            position: relative;
            z-index: 3;
        }
        .milestone.active .milestone-icon {
            background-color: #4CAF50;
            color: white;
            box-shadow: 0 0 0 5px rgba(76, 175, 80, 0.2);
        }
        .milestone.rejected .milestone-icon {
            background-color: #ff0000;
            color: white;
            box-shadow: 0 0 0 5px rgba(255, 0, 0, 0.2);
        }
        .milestone-title {
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        .milestone-desc {
            font-size: 14px;
            color: #666;
            max-width: 120px;
        }
        .progress-line {
            position: absolute;
            top: 30px;
            left: 30px;
            right: 30px;
            height: 4px;
            background-color: #e0e0e0;
            z-index: 1;
        }
        .progress-line-fill {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            background-color: #4CAF50;
            transition: width 0.5s ease-in-out;
        }
        .tooltip {
            visibility: hidden;
            width: 200px;
            background-color: black;
            color: white;
            text-align: center;
            padding: 5px 0;
            border-radius: 5px;
            position: absolute;
            z-index: 4;
            bottom: 100%; /* Position above the milestone */
            left: 50%;
            margin-left: -100px;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .milestone:hover .tooltip {
            visibility: visible;
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="progress-container">
        <h2>Performance Tracking</h2>
        <input type="text" id="search-input" placeholder="Search..." style="width: 100%; padding: 10px; margin-bottom: 20px;">

        <table border="1" style="width: 100%; margin-bottom: 20px;" id="performance-table">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Branch</th>
                    <th>Job Title</th>
                    <th>Job Description</th>
                    <th>Job Domain</th>
                    <th>Progress</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data will be inserted here by JavaScript -->
            </tbody>
        </table>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');
        const performanceTable = document.getElementById('performance-table').getElementsByTagName('tbody')[0];

        function fetchData(query) {
            fetch('search_performance.php?query=' + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => {
                    performanceTable.innerHTML = '';

                    data.forEach(row => {
                        const tr = document.createElement('tr');

                        tr.innerHTML = `
                            <td>${row.student_name}</td>
                            <td>${row.student_email}</td>
                            <td>${row.student_phone}</td>
                            <td>${row.student_branch}</td>
                            <td>${row.job_title}</td>
                            <td>${row.job_description}</td>
                            <td>${row.job_domain}</td>
                            <td>
                                <div class="progress-bar">
                                    <div class="progress-line">
                                        <div class="progress-line-fill" style="width: ${row.progressPercentage}%"></div>
                                    </div>
                                    <div class="milestone ${row.aptitude === 'completed' ? 'active' : (row.rejected === 'yes' ? 'rejected' : '')}">
                                        <div class="milestone-icon">1</div>
                                        <div class="milestone-title">Aptitude</div>
                                        <div class="milestone-desc">Aptitude Test</div>
                                    </div>
                                    <div class="milestone ${row.technical_interview === 'completed' ? 'active' : (row.rejected === 'yes' ? 'rejected' : '')}">
                                        <div class="milestone-icon">2</div>
                                        <div class="milestone-title">Technical Interview</div>
                                        <div class="milestone-desc">Technical Interview</div>
                                    </div>
                                    <div class="milestone ${row.offer_letter === 'completed' ? 'active' : (row.rejected === 'yes' ? 'rejected' : '')}">
                                        <div class="milestone-icon">3</div>
                                        <div class="milestone-title">Offer Letter</div>
                                        <div class="milestone-desc">Offer Letter Sent</div>
                                    </div>
                                    <div class="milestone ${row.placed === 'completed' ? 'active' : (row.rejected === 'yes' ? 'rejected' : '')}">
                                        <div class="milestone-icon">4</div>
                                        <div class="milestone-title">Placed</div>
                                        <div class="milestone-desc">Placed in Company</div>
                                    </div>
                                    <div class="milestone ${row.rejected === 'yes' ? 'rejected' : ''}">
                                        <div class="milestone-icon">5</div>
                                        <div class="milestone-title">Rejected</div>
                                        <div class="milestone-desc">Rejected from Process</div>
                                        <div class="tooltip">${row.rejection_reason}</div>
                                    </div>
                                </div>
                            </td>
                            <td><a href="edit_performance.php?id=${row.application_id}">Edit</a></td>
                        `;

                        performanceTable.appendChild(tr);
                    });
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        searchInput.addEventListener('input', function() {
            fetchData(searchInput.value);
        });

        // Initial load
        fetchData('');
    });
    </script>
</body>
</html>
