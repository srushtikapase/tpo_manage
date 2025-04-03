<?php
include('../includes/db.php');

$search = isset($_POST['query']) ? $_POST['query'] : '';

$query = "SELECT * FROM jobs WHERE 
    comname LIKE '%$search%' OR 
    title LIKE '%$search%' OR 
    description LIKE '%$search%' OR 
    skills LIKE '%$search%' OR 
    domain LIKE '%$search%' OR 
    position LIKE '%$search%' OR 
    experience LIKE '%$search%' OR 
    salary LIKE '%$search%' OR 
    openings LIKE '%$search%' OR 
    eligibility LIKE '%$search%' OR 
    shift LIKE '%$search%' OR 
    schedule LIKE '%$search%'";

$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings</title>
    <style>
        
        th {
            background-color: #f2f2f2;
            padding: 10px;
        }

        :root {
            --primary-color: #4a4e69;
            --secondary-color: #9a8c98;
            --accent-color: #c9ada7;
            --background-color: #f2e9e4;
            --card-background: #ffffff;
            --text-color: #22223b;
            --text-light: #4a4e69;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            line-height: 1.6;
            background-color: var(--background-color);
            color: var(--text-color);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 4rem 1rem;
        }

        h1 {
            text-align: center;
            color: var(--primary-color);
            margin-bottom: 3rem;
            font-size: 2.5rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .job-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .job-card {
            background-color: var(--card-background);
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            
            position: relative;
        }

        .job-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .job-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: 0 auto 20px;
            overflow: hidden;
            border: 6px solid var(--card-background);
            transition: transform 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .job-card:hover .job-image {
            transform: scale(1.1) rotate(5deg);
        }

        .job-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .job-content {
            padding: 2rem;
            text-align: center;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .job-content::before {
            content: '';
            position: absolute;
            top: -60px;
            left: 0;
            right: 0;
            height: 60px;
            background-color: var(--accent-color);
            border-radius: 20px 20px 0 0;
        }

        .job-title {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .job-company {
            font-weight: 500;
            color: var(--secondary-color);
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .job-details {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            color: var(--text-light);
        }

        .job-details span {
            background-color: var(--accent-color);
            color: var(--card-background);
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-weight: 500;
        }

        .job-description {
            margin-bottom: 2rem;
            font-size: 0.95rem;
            color: var(--text-light);
            flex-grow: 1;
            line-height: 1.6;
        }

        .action-buttons {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .apply-btn {
            display: inline-block;
            background-color: var(--primary-color);
            color: var(--card-background);
            padding: 0.75rem 2rem;
            border-radius: 25px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
            flex: 1;
        }

        .apply-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .apply-btn:hover::before {
            left: 100%;
        }

        .apply-btn:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .container {
                padding: 3rem 1rem;
            }

            h1 {
                font-size: 2rem;
            }

            .job-grid {
                grid-template-columns: 1fr;
            }

            .job-card {
                max-width: 400px;
                margin: 0 auto;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="job-grid">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '
                <div class="job-card">
                    
                    <div class="job-content">
                        <h2 class="job-title">' . $row['title'] . '</h2>
                        <p class="job-company">' . $row['comname'] . '</p>
                        <div class="job-details">
                            <span>' . $row['domain'] . '</span>
                            <span>' . $row['position'] . '</span>
                        </div>
                        <p class="job-description">' . $row['description'] . '</p>
                      
                    </div>
                </div>';
            }
        } else {
            echo '<p class="no-jobs-message">No jobs found.</p>';
        }
        ?>
    </div>
</div>

</body>
</html>
