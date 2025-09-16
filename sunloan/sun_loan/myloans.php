<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

$page_title = 'Mes prêts';
require_once 'includes/header.php';

// Récupérer les prêts de l'utilisateur
try {
    $stmt = $dbh->prepare("SELECT * FROM loans WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$_SESSION['user_id']]);
    $loans = $stmt->fetchAll();
} catch (PDOException $e) {
    echo '<script>showAlert("error", "Erreur", "Une erreur est survenue lors de la récupération de vos prêts.");</script>';
}
?>

<div class="card">
    <div class="card-header">
        <h2>Mes prêts</h2>
        <a href="apply.php" class="btn">Demander un nouveau prêt</a>
    </div>
    <div class="card-body">
        <?php if(empty($loans)): ?>
            <p>Vous n'avez aucun prêt pour le moment.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Montant</th>
                        <th>Durée</th>
                        <th>Mensualité</th>
                        <th>Total à rembourser</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($loans as $loan): ?>
                        <tr>
                            <td>#<?php echo $loan['id']; ?></td>
                            <td><?php echo number_format($loan['amount'], 2); ?> €</td>
                            <td><?php echo $loan['duration']; ?> mois</td>
                            <td><?php echo number_format($loan['monthly_payment'], 2); ?> €</td>
                            <td><?php echo number_format($loan['total_amount'], 2); ?> €</td>
                            <td class="status-<?php echo $loan['status']; ?>"><?php echo ucfirst($loan['status']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($loan['created_at'])); ?></td>
                            <td>
                                <a href="loan_details.php?id=<?php echo $loan['id']; ?>" class="btn btn-sm">Détails</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>