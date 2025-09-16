<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

$page_title = 'Mon profil';
require_once 'includes/header.php';

// Récupérer les informations de l'utilisateur
try {
    $stmt = $dbh->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
} catch (PDOException $e) {
    echo '<script>showAlert("error", "Erreur", "Une erreur est survenue lors de la récupération de votre profil.");</script>';
}

// Traitement de la mise à jour du profil
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = secure_input($_POST['first_name']);
    $last_name = secure_input($_POST['last_name']);
    $phone = secure_input($_POST['phone']);
    $address = secure_input($_POST['address']);
    $city = secure_input($_POST['city']);
    $zip_code = secure_input($_POST['zip_code']);
    $country = secure_input($_POST['country']);
    
    try {
        $stmt = $dbh->prepare("UPDATE users SET first_name = ?, last_name = ?, phone = ?, address = ?, city = ?, zip_code = ?, country = ? WHERE id = ?");
        $stmt->execute([$first_name, $last_name, $phone, $address, $city, $zip_code, $country, $_SESSION['user_id']]);
        
        // Mettre à jour les données de session
        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name'] = $last_name;
        
        echo '<script>showAlert("success", "Profil mis à jour", "Vos informations ont été mises à jour avec succès.");</script>';
    } catch (PDOException $e) {
        echo '<script>showAlert("error", "Erreur", "Une erreur est survenue lors de la mise à jour de votre profil.");</script>';
    }
}

// Traitement du changement de mot de passe
if(isset($_POST['change_password'])) {
    $current_password = secure_input($_POST['current_password']);
    $new_password = secure_input($_POST['new_password']);
    $confirm_password = secure_input($_POST['confirm_password']);
    
    // Vérifier si le mot de passe actuel est correct
    if(password_verify($current_password, $user['password'])) {
        if($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            try {
                $stmt = $dbh->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$hashed_password, $_SESSION['user_id']]);
                
                echo '<script>showAlert("success", "Mot de passe changé", "Votre mot de passe a été mis à jour avec succès.");</script>';
            } catch (PDOException $e) {
                echo '<script>showAlert("error", "Erreur", "Une erreur est survenue lors du changement de mot de passe.");</script>';
            }
        } else {
            echo '<script>showAlert("error", "Erreur", "Les nouveaux mots de passe ne correspondent pas.");</script>';
        }
    } else {
        echo '<script>showAlert("error", "Erreur", "Le mot de passe actuel est incorrect.");</script>';
    }
}
?>

<div class="card">
    <div class="card-header">
        <h2>Mon profil</h2>
    </div>
    <div class="card-body">
        <form action="profile.php" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="first_name">Prénom</label>
                        <input type="text" id="first_name" name="first_name" class="form-control" value="<?php echo $user['first_name']; ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="last_name">Nom</label>
                        <input type="text" id="last_name" name="last_name" class="form-control" value="<?php echo $user['last_name']; ?>" required>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" class="form-control" value="<?php echo $user['email']; ?>" disabled>
            </div>
            
            <div class="form-group">
                <label for="phone">Téléphone</label>
                <input type="tel" id="phone" name="phone" class="form-control" value="<?php echo $user['phone']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="address">Adresse</label>
                <input type="text" id="address" name="address" class="form-control" value="<?php echo $user['address']; ?>" required>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="city">Ville</label>
                        <input type="text" id="city" name="city" class="form-control" value="<?php echo $user['city']; ?>" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="zip_code">Code postal</label>
                        <input type="text" id="zip_code" name="zip_code" class="form-control" value="<?php echo $user['zip_code']; ?>" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="country">Pays</label>
                        <input type="text" id="country" name="country" class="form-control" value="<?php echo $user['country']; ?>" required>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn">Mettre à jour</button>
        </form>
        
        <hr>
        
        <h3>Changer le mot de passe</h3>
        <form action="profile.php" method="POST">
            <div class="form-group">
                <label for="current_password">Mot de passe actuel</label>
                <div class="input-group">
                    <input type="password" id="current_password" name="current_password" class="form-control" required>
                    <span class="input-group-text toggle-password">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
            </div>
            
            <div class="form-group">
                <label for="new_password">Nouveau mot de passe</label>
                <div class="input-group">
                    <input type="password" id="new_password" name="new_password" class="form-control" required>
                    <span class="input-group-text toggle-password">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirmer le nouveau mot de passe</label>
                <div class="input-group">
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                    <span class="input-group-text toggle-password">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
            </div>
            
            <button type="submit" name="change_password" class="btn">Changer le mot de passe</button>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>