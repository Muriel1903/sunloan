<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

// Vérifier si l'utilisateur est admin
if(!$_SESSION['is_admin']) {
    header('Location: ../index.php');
    exit;
}

$page_title = 'Gestion des utilisateurs';
require_once '../../includes/header.php';

// Traitement de la suppression d'un utilisateur
if(isset($_GET['delete']) && isset($_GET['id'])) {
    $user_id = secure_input($_GET['id']);
    
    try {
        // Ne pas permettre la suppression de l'admin principal
        if($user_id == 1) {
            echo '<script>showAlert("error", "Erreur", "Vous ne pouvez pas supprimer l\'administrateur principal.");</script>';
        } else {
            $stmt = $dbh->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            
            echo '<script>showAlert("success", "Succès", "L\'utilisateur a été supprimé avec succès."); setTimeout(() => { window.location.href = "users.php"; }, 1500);</script>';
        }
    } catch (PDOException $e) {
        echo '<script>showAlert("error", "Erreur", "Une erreur est survenue lors de la suppression de l\'utilisateur.");</script>';
    }
}

// Récupérer tous les utilisateurs
try {
    $stmt = $dbh->query("SELECT * FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    echo '<script>showAlert("error", "Erreur", "Une erreur est survenue lors de la récupération des utilisateurs.");</script>';
}
?>

<div class="dashboard">
    <div class="sidebar">
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
            <li><a href="loans.php"><i class="fas fa-hand-holding-usd"></i> Gestion des prêts</a></li>
            <li><a href="users.php" class="active"><i class="fas fa-users"></i> Gestion des utilisateurs</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <h1>Gestion des utilisateurs</h1>
        
        <?php if(empty($users)): ?>
            <p>Aucun utilisateur trouvé.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Inscription</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $user): ?>
                        <tr>
                            <td>#<?php echo $user['id']; ?></td>
                            <td><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['phone']; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                            <td><?php echo $user['is_admin'] ? 'Admin' : 'Client'; ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="user_details.php?id=<?php echo $user['id']; ?>" class="btn btn-sm">Détails</a>
                                    <?php if($user['id'] != 1 && $user['id'] != $_SESSION['user_id']): ?>
                                        <a href="users.php?delete=true&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?')">Supprimer</a>
                                    <?php endif; ?>
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