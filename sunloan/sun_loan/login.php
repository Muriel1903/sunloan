<?php
require_once 'includes/config.php';

if(isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$page_title = 'Connexion';
require_once 'includes/header.php';

// Traitement du formulaire de connexion
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = secure_input($_POST['email']);
    $password = secure_input($_POST['password']);
    
    try {
        $stmt = $dbh->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['is_admin'] = $user['is_admin'];
            
            echo '<script>showAlert("success", "Connexion réussie", "Vous êtes maintenant connecté."); setTimeout(() => { window.location.href = "index.php"; }, 1500);</script>';
        } else {
            echo '<script>showAlert("error", "Erreur", "Email ou mot de passe incorrect.");</script>';
        }
    } catch (PDOException $e) {
        echo '<script>showAlert("error", "Erreur", "Une erreur est survenue lors de la connexion.");</script>';
    }
}
?>

<div class="card">
    <div class="card-header">
        <h2>Connexion</h2>
    </div>
    <div class="card-body">
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control" required>
                    <span class="input-group-text toggle-password">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
            </div>
            <button type="submit" class="btn">Se connecter</button>
            <p class="mt-3">Pas encore de compte? <a href="register.php">S'inscrire</a></p>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>