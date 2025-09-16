<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUN LOAN - <?php echo $page_title ?? 'Application de Prêt en Ligne'; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <a href="index.php"><img src="assets/images/logo.png" alt="SUN LOAN"></a>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php"><i class="fas fa-home"></i> Accueil</a></li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li><a href="apply.php"><i class="fas fa-hand-holding-usd"></i> Demander un prêt</a></li>
                        <li><a href="myloans.php"><i class="fas fa-file-invoice-dollar"></i> Mes prêts</a></li>
                        <li><a href="profile.php"><i class="fas fa-user"></i> Mon profil</a></li>
                        <?php if($_SESSION['is_admin']): ?>
                            <li><a href="admin/dashboard.php"><i class="fas fa-tachometer-alt"></i> Admin</a></li>
                        <?php endif; ?>
                        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
                    <?php else: ?>
                        <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Connexion</a></li>
                        <li><a href="register.php"><i class="fas fa-user-plus"></i> Inscription</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container"></main>