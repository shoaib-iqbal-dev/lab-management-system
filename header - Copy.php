<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$username = $_SESSION['username'] ?? '';
?>

<header>
	<img src="loggo.png" alt="lab logo.png" style="width: 120px;margin-bottom: 0px;padding-bottom: 0px;">
   
   <h1>
        <a href="<?php echo isset($_SESSION['username']) ? 'dashboard.php' : 'login.php'; ?>" class="header-link">
            Cell Lab & Diagnostic Center
        </a>
    </h1>
	
    <?php if (!empty($username)) { ?>
        <h2 style="text-align: center; color:white;">Welcome, <?php echo ucfirst($username); ?>!</h2>
        <a href="logout.php" class="logout-btn" style="text-decoration: none; color: inherit;">Logout</a>
    <?php } ?>
</header>
