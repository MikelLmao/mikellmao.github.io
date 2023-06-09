<?php
// Establish a connection to the Microsoft Access database
$dbPath = 'C:\Users\ACER\Documents\GitHub\mikellmao.github.io\users.accdb';
$db = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=$dbPath");

// Function to validate user credentials
function validateUser($username, $password) {
    global $db;
    $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        // Password is correct, grant access
        return true;
    }
    
    return false;
}

// Process the login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (validateUser($username, $password)) {
        // Set a session or token to maintain user's logged-in state
        session_start();
        $_SESSION['username'] = $username;
        // Redirect to the protected page
        header('Location: protected_page.php');
        exit();
    } else {
        // Invalid credentials, display an error message
        $errorMessage = 'Invalid username or password.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <?php if (isset($errorMessage)) : ?>
        <p style="color: red;"><?php echo $errorMessage; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="Log In">
    </form>
</body>
</html>