<?php
require_once 'config/db.php';
include 'includes/header.php';

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email = '$email'");
    if ($result && $result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No account found with this email.";
    }
}
?>

<div class="container" style="max-width: 450px; margin-top: 50px;">
    <div class="form-card">
        <h2><?php echo $lang_data['login']; ?> to BloodBank</h2>
        <?php if (!empty($error)): ?>
        <p style="color: #d9534f; margin-bottom: 15px; font-weight: 500;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn" style="width: 100%;"><?php echo $lang_data['login']; ?></button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>