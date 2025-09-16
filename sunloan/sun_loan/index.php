<?php
// Remplacé _DIR_ par chemin relatif pour éviter l'erreur
require_once 'includes/config.php';
$page_title = 'Accueil';
require_once 'includes/header.php';
?>

<section class="hero">
    <div class="hero-content">
        <h1>Obtenez le prêt dont vous avez besoin en toute confiance</h1>
        <p>SUN LOAN vous propose des solutions de financement adaptées à vos besoins avec des taux compétitifs.</p>
        
        <?php if(!isset($_SESSION['user_id'])): ?>
            <!-- Boutons redisposés avec un meilleur alignement -->
            <div class="hero-buttons" style="display: flex; gap: 10px; justify-content: center; margin-top: 20px;">
                <a href="register.php" class="btn">S'inscrire</a>
                <a href="login.php" class="btn btn-outline">Se connecter</a>
            </div>
        <?php else: ?>
            <a href="apply.php" class="btn">Demander un prêt</a>
        <?php endif; ?>
    </div>
</section>

<section class="features">
    <div class="feature-card">
        <i class="fas fa-bolt"></i>
        <h3>Rapide</h3>
        <p>Réponse sous 24h pour votre demande de prêt.</p>
    </div>
    <div class="feature-card">
        <i class="fas fa-percent"></i>
        <h3>Taux avantageux</h3>
        <p>Des taux d'intérêt compétitifs adaptés à votre profil.</p>
    </div>
    <div class="feature-card">
        <i class="fas fa-shield-alt"></i>
        <h3>Sécurisé</h3>
        <p>Vos données sont protégées et sécurisées.</p>
    </div>
</section>

<section class="loan-calculator">
    <h2>Simulateur de prêt</h2>
    <form id="loan-calculator-form">
        <div class="form-group">
            <!-- Devise changée en FCFA -->
            <label for="amount">Montant du prêt (FCFA)</label>
            <input type="number" id="amount" class="form-control" min="500" max="5000000" required>
        </div>
        <div class="form-group">
            <label for="duration">Durée (mois)</label>
            <input type="number" id="duration" class="form-control" min="2" max="60" required>
        </div>
        <button type="submit" class="btn">Calculer</button>
    </form>
    
    <div id="calculator-result" class="calculator-result" style="display: none;">
        <h3>Résultat de la simulation</h3>
        <p><strong>Mensualité:</strong> <span id="monthly-payment"></span></p>
        <p><strong>Montant total à rembourser:</strong> <span id="total-amount"></span></p>
        <p><strong>Taux d'intérêt:</strong> <span id="interest-rate"></span></p>
        <p><strong>Pénalité en cas de retard (1% / jour):</strong> <span id="late-penalty"></span></p>
    </div>
</section>

<script>
// Gestion de la simulation côté client
document.getElementById('loan-calculator-form').addEventListener('submit', function(e) {
    e.preventDefault();

    let amount = parseFloat(document.getElementById('amount').value);
    let duration = parseInt(document.getElementById('duration').value);

    // Si montant > 500 000, durée forcée à 3 mois
    if (amount > 500000) {
        duration = 3;
        document.getElementById('duration').value = 3;
        alert("Pour un prêt supérieur à 500 000 FCFA, la durée est automatiquement fixée à 3 mois . pour un montant inferieur a 1000000 FCFA, la durée est de 1 mois. ");
    }

    // Exemple de calcul simple : (tu peux le remplacer par ta formule réelle)
    let interestRate = 0.1; // 10% d'intérêt (modifiable)
    let totalAmount = amount + (amount * interestRate);
    let monthlyPayment = totalAmount / duration;

    // Pénalité si retard (exemple pour 1 jour)
    let daysLate = 1
    let latePenalty = (amount * 0.1) * daysLate;

    document.getElementById('monthly-payment').textContent = monthlyPayment.toFixed(2) + " FCFA";
    document.getElementById('total-amount').textContent = totalAmount.toFixed(2) + " FCFA";
    document.getElementById('interest-rate').textContent = (interestRate * 100) + " %";
    document.getElementById('late-penalty').textContent = latePenalty.toFixed(2) + " FCFA / jour";

    document.getElementById('calculator-result').style.display = 'block';
});
</script>

<?php require_once 'includes/footer.php'; ?>