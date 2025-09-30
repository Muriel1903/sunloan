<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

// Vérifier si l'utilisateur est admin

$page_title = 'Tableau de bord admin';
require_once '../../includes/header.php';

// Statistiques
try {
    // Nombre total d'utilisateurs
    $stmt = $dbh->query("SELECT COUNT(*) as total_users FROM users");
    $total_users = $stmt->fetch()['total_users'];
    
    // Nombre total de prêts
    $stmt = $dbh->query("SELECT COUNT(*) as total_loans FROM loans");
    $total_loans = $stmt->fetch()['total_loans'];
    
    // Montant total des prêts
    $stmt = $dbh->query("SELECT SUM(amount) as total_amount FROM loans");
    $total_amount = $stmt->fetch()['total_amount'];
    
    // Prêts en attente
    $stmt = $dbh->query("SELECT COUNT(*) as pending_loans FROM loans WHERE status = 'pending'");
    $pending_loans = $stmt->fetch()['pending_loans'];
} catch (PDOException $e) {
    echo '<script>showAlert("error", "Erreur", "Une erreur est survenue lors de la récupération des statistiques.");</script>';
}
?>

<div class="dashboard">
    <div class="sidebar">
        <ul>
            <li><a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
            <li><a href="loans.php"><i class="fas fa-hand-holding-usd"></i> Gestion des prêts</a></li>
            <li><a href="users.php"><i class="fas fa-users"></i> Gestion des utilisateurs</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <h1>Tableau de bord</h1>
        
        <div class="stats">
            <div class="stat-card">
                <h3>Utilisateurs</h3>
                <p><?php echo $total_users; ?></p>
            </div>
            <div class="stat-card">
                <h3>Prêts</h3>
                <p><?php echo $total_loans; ?></p>
            </div>
            <div class="stat-card">
                <h3>Montant total</h3>
                <p><?php echo number_format($total_amount, 2); ?> €</p>
            </div>
            <div class="stat-card">
                <h3>Prêts en attente</h3>
                <p><?php echo $pending_loans; ?></p>
            </div>
        </div>
        
        <div class="recent-loans">
            <h2>Dernières demandes de prêt</h2>
            <?php
            try {
                $stmt = $dbh->query("SELECT l.*, u.first_name, u.last_name FROM loans l JOIN users u ON l.user_id = u.id ORDER BY l.created_at DESC LIMIT 5");
                $recent_loans = $stmt->fetchAll();
                
                if(empty($recent_loans)) {
                    echo '<p>Aucun prêt récent.</p>';
                } else {
                    echo '<table>';
                    echo '<thead><tr><th>ID</th><th>Client</th><th>Montant</th><th>Durée</th><th>Statut</th><th>Date</th><th>Actions</th></tr></thead>';
                    echo '<tbody>';
                    
                    foreach($recent_loans as $loan) {
                        echo '<tr>';
                        echo '<td>#' . $loan['id'] . '</td>';
                        echo '<td>' . $loan['first_name'] . ' ' . $loan['last_name'] . '</td>';
                        echo '<td>' . number_format($loan['amount'], 2) . ' €</td>';
                        echo '<td>' . $loan['duration'] . ' mois</td>';
                        echo '<td class="status-' . $loan['status'] . '">' . ucfirst($loan['status']) . '</td>';
                        echo '<td>' . date('d/m/Y', strtotime($loan['created_at'])) . '</td>';
                        echo '<td><a href="loan_details.php?id=' . $loan['id'] . '" class="btn btn-sm">Détails</a></td>';
                        echo '</tr>';
                    }
                    
                    echo '</tbody></table>';
                }
            } catch (PDOException $e) {
                echo '<script>showAlert("error", "Erreur", "Une erreur est survenue lors de la récupération des prêts récents.");</script>';
            }
            ?>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>