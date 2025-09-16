<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

$page_title = 'Demander un prêt';
require_once 'includes/header.php';

// Traitement du formulaire de demande de prêt
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = secure_input($_POST['amount']);
    $duration = secure_input($_POST['duration']);
    $purpose = secure_input($_POST['purpose']);
    $interest_rate = 5.5; // Taux d'intérêt fixe
    
    // Calcul des mensualités
    $monthly_rate = $interest_rate / 100 / 12;
    $monthly_payment = ($amount * $monthly_rate) / (1 - Math.pow(1 + $monthly_rate, -$duration));
    $total_amount = $monthly_payment * $duration;
    
    try {
        $stmt = $dbh->prepare("INSERT INTO loans (user_id, amount, duration, interest_rate, monthly_payment, total_amount, purpose) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $amount, $duration, $interest_rate, $monthly_payment, $total_amount, $purpose]);
        
        echo '<script>showAlert("success", "Demande envoyée", "Votre demande de prêt a été soumise avec succès."); setTimeout(() => { window.location.href = "myloans.php"; }, 1500);</script>';
    } catch (PDOException $e) {
        echo '<script>showAlert("error", "Erreur", "Une erreur est survenue lors de la soumission de votre demande.");</script>';
    }
}
?>

<div class="card">
    <div class="card-header">
        <h2>Demande de prêt</h2>
    </div>
    <div class="card-body">
        <form action="apply.php" method="POST">
            <div class="form-group">
                <label for="amount">Montant souhaité (€)</label>
                <input type="number" id="amount" name="amount" class="form-control" min="500" max="50000" required>
            </div>
            
            <div class="form-group">
                <label for="duration">Durée (mois)</label>
                <select id="duration" name="duration" class="form-control" required>
                    <option value="">Sélectionnez une durée</option>
                    <option value="6">6 mois</option>
                    <option value="12">12 mois</option>
                    <option value="24">24 mois</option>
                    <option value="36">36 mois</option>
                    <option value="48">48 mois</option>
                    <option value="60">60 mois</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="purpose">Motif du prêt</label>
                <textarea id="purpose" name="purpose" class="form-control" rows="4" required></textarea>
            </div>
            
            <button type="submit" class="btn">Soumettre la demande</button>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>