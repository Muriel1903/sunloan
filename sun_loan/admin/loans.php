<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

// Vérifier si l'utilisateur est admin
if(!$_SESSION['is_admin']) {
    header('Location: ../index.php');
    exit;
}

$page_title = 'Gestion des prêts';
require_once '../../includes/header.php';

// Traitement du changement de statut
if(isset($_GET['action']) && isset($_GET['id'])) {
    $loan_id = secure_input($_GET['id']);
    $action = secure_input($_GET['action']);
    
    if(in_array($action, ['approve', 'reject', 'mark_paid'])) {
        $status = str_replace(['approve', 'reject', 'mark_paid'], ['approved', 'rejected', 'paid'], $action);
        
        try {
            $stmt = $dbh->prepare("UPDATE loans SET status = ? WHERE id = ?");
            $stmt->execute([$status, $loan_id]);
            
            echo '<script>showAlert("success", "Succès", "Le statut du prêt a été mis à jour."); setTimeout(() => { window.location.href = "loans.php"; }, 1500);</script>';
        } catch (PDOException $e) {
            echo '<script>showAlert("error", "Erreur", "Une erreur est survenue lors de la mise à jour du statut.");</script>';
        }
    }
}

// Récupérer tous les prêts
try {
    $stmt = $dbh->query("SELECT l.*, u.first_name, u.last_name FROM loans l JOIN users u ON l.user_id = u.id ORDER BY l.created_at DESC");
    $loans = $stmt->fetchAll();
} catch (PDOException $e) {
    echo '<script>showAlert("error", "Erreur", "Une erreur est survenue lors de la récupération des prêts.");</script>';
}
?>

<div class="dashboard">
    <div class="sidebar">
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
            <li><a href="loans.php" class="active"><i class="fas fa-hand-holding-usd"></i> Gestion des prêts</a></li>
            <li><a href="users.php"><i class="fas fa-users"></i> Gestion des utilisateurs</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <h1>Gestion des prêts</h1>
        
        <div class="filters">
            <a href="loans.php" class="btn btn-outline">Tous</a>
            <a href="loans.php?status=pending" class="btn btn-outline">En attente</a>
            <a href="loans.php?status=approved" class="btn btn-outline">Approuvés</a>
            <a href="loans.php?status=rejected" class="btn btn-outline">Rejetés</a>
            <a href="loans.php?status=paid" class="btn btn-outline">Payés</a>
        </div>
        
        <?php if(empty($loans)): ?>
            <p>Aucun prêt trouvé.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Montant</th>
                        <th>Durée</th>
                        <th>Mensualité</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($loans as $loan): ?>
                        <tr>
                            <td>#<?php echo $loan['id']; ?></td>
                            <td><?php echo $loan['first_name'] . ' ' . $loan['last_name']; ?></td>
                            <td><?php echo number_format($loan['amount'], 2); ?> €</td>
                            <td><?php echo $loan['duration']; ?> mois</td>
                            <td><?php echo number_format($loan['monthly_payment'], 2); ?> €</td>
                            <td class="status-<?php echo $loan['status']; ?>"><?php echo ucfirst($loan['status']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($loan['created_at'])); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <?php if($loan['status'] == 'pending'): ?>
                                        <a href="loans.php?action=approve&id=<?php echo $loan['id']; ?>" class="btn btn-sm btn-success">Approuver</a>
                                        <a href="loans.php?action=reject&id=<?php echo $loan['id']; ?>" class="btn btn-sm btn-danger">Rejeter</a>
                                    <?php elseif($loan['status'] == 'approved'): ?>
                                        <a href="loans.php?action=mark_paid&id=<?php echo $loan['id']; ?>" class="btn btn-sm btn-info">Marquer comme payé</a>
                                    <?php endif; ?>
                                    <a href="loan_details.php?id=<?php echo $loan['id']; ?>" class="btn btn-sm">Détails</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>