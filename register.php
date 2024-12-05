<?php
// Include the Supabase client
require_once 'vendor/autoload.php';

use Supabase\SupabaseClient;

$supabaseUrl = 'https://kjzsjwlcezujrxkbogbd.supabase.co';
$supabaseKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImtqenNqd2xjZXp1anJ4a2JvZ2JkIiwicm9sZSI6ImFub24iLCJpYXQiOjE3MzM0MDAwNjYsImV4cCI6MjA0ODk3NjA2Nn0.DT2xtDkwoGka0GO0ECTIZCJf1ez04rVu-Z_iQeuh7xw';

$supabase = new SupabaseClient($supabaseUrl, $supabaseKey);

$message = "";
$toastColor = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the email already exists
    $response = $supabase->from('users')->select('email')->eq('email', $email)->execute();
    
    if (count($response['data']) > 0) {
        $message = "Email ID already exists";
        $toastColor = "#007bff"; // Primary color
    } else {
        // Insert new user into the database
        $response = $supabase->from('users')->insert([
            'username' => $username,
            'email' => $email,
            'password' => $hashed_password
        ])->execute();

        if ($response['status'] == 201) {
            $message = "Account created successfully";
            $toastColor = "#28a745"; // Success color
        } else {
            $message = "Error: " . $response['error']['message'];
            $toastColor = "#dc3545"; // Error color
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasjon</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>

    <div class="container">

        <?php if ($message): ?>
            <div class="toast" style="background-color: <?php echo $toastColor; ?>;">
                <?php echo $message; ?>
                <button onclick="this.parentElement.style.display='none'">×</button>
            </div>
        <?php endif; ?>
        
        <form method="post">
            <h5>Opprett Konto</h5>

            <div class="input-gruppe">
                <label for="username">Brukernavn</label>
                <input type="text" name="username" id="username" required>
            </div>

            <div class="input-gruppe">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
            </div>

            <div class="input-gruppe">
                <label for="password">Passord</label>
                <input type="password" name="password" id="password" required>
            </div>

            <button type="submit" class="register-knapp">Lag Bruker</button>
            <p>Har du en bruker? <a class="tilbake" href="./login.php">Login</a> eller <a class="tilbake" href="http://localhost/loginRegisterationSystem/logg_inn-registrer/hovedside/">til hjemsiden</a></p>
        </form>
    </div>

  
</body>
</html>
