<?php
require_once 'includes/config.php';

if(isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$page_title = 'Inscription';
require_once 'includes/header.php';

// Traitement du formulaire d'inscription
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = secure_input($_POST['first_name']);
    $last_name = secure_input($_POST['last_name']);
    $email = secure_input($_POST['email']);
    $password = password_hash(secure_input($_POST['password']), PASSWORD_DEFAULT);
    $phone = secure_input($_POST['phone']);
    $address = secure_input($_POST['address']);
    $city = secure_input($_POST['city']);
    $zip_code = secure_input($_POST['zip_code']);
    $country = secure_input($_POST['country']);
    $birth_date = secure_input($_POST['birth_date']);
    $id_number = secure_input($_POST['id_number']);

    // Vérification de l'âge minimum
    $birth_timestamp = strtotime($birth_date);
    $age = date('Y') - date('Y', $birth_timestamp);
    if (date('md', $birth_timestamp) > date('md')) {
        $age--; // Ajuster si l'anniversaire n'est pas encore passé
    }

    if ($age < 25) {
        echo '<script>showAlert("error", "Accès refusé", "Vous devez avoir au moins 25 ans pour vous inscrire.");</script>';
    } else {
        try {
            // Vérifier si l'email existe déjà
            $stmt = $dbh->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if($stmt->rowCount() > 0) {
                echo '<script>showAlert("error", "Erreur", "Cet email est déjà utilisé.");</script>';
            } else {
                $stmt = $dbh->prepare("INSERT INTO users (first_name, last_name, email, password, phone, address, city, zip_code, country, birth_date, id_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$first_name, $last_name, $email, $password, $phone, $address, $city, $zip_code, $country, $birth_date, $id_number]);
                
                echo '<script>showAlert("success", "Inscription réussie", "Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter."); setTimeout(() => { window.location.href = "login.php"; }, 1500);</script>';
            }
        } catch (PDOException $e) {
            echo '<script>showAlert("error", "Erreur", "Une erreur est survenue lors de l\'inscription.");</script>';
        }
    }
}
?>

<div class="card">
    <div class="card-header">
        <h2>Inscription</h2>
    </div>
    <div class="card-body">
        <form action="register.php" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="first_name">Prénom</label>
                        <input type="text" id="first_name" name="first_name" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="last_name">Nom</label>
                        <input type="text" id="last_name" name="last_name" class="form-control" required>
                    </div>
                </div>
            </div>
            
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
            
            <div class="form-group">
                <label for="phone">Téléphone</label>
                <input type="tel" id="phone" name="phone" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="address">Adresse</label>
                <input type="text" id="address" name="address" class="form-control" required>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="city">Ville</label>
                        <input type="text" id="city" name="city" class="form-control" required>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="country">Pays</label>
                        <input type="text" id="country" name="country" class="form-control" required>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="birth_date">Date de naissance</label>
                <input type="date" id="birth_date" name="birth_date" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="id_number">Numéro de pièce d'identité</label>
                <input type="text" id="id_number" name="id_number" class="form-control" required>
            </div>

            <!-- Champ pour scanner ou uploader la CNI -->
            <div class="form-group">
                <label for="cni_scan">Scanner ou téléverser votre CNI</label>
                <input type="file" id="cni_scan" name="cni_scan" class="form-control" accept="image/*" capture="environment">
                <small class="form-text text-muted">Prenez une photo claire de votre CNI (recto/verso).</small>
            </div>
            
            <button type="submit" class="btn">S'inscrire</button>
            <p class="mt-3">Déjà un compte? <a href="login.php">Se connecter</a></p>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>