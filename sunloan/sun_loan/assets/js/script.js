$(document).ready(function() {
    // Calculatrice de prêt
    $('#loan-calculator-form').on('submit', function(e) {
        e.preventDefault();
        
        const amount = parseFloat($('#amount').val());
        const duration = parseInt($('#duration').val());
        const interestRate = 5.5; // Taux d'intérêt fixe de 5.5%
        
        if (amount && duration) {
            const monthlyRate = interestRate / 100 / 12;
            const monthlyPayment = (amount * monthlyRate) / (1 - Math.pow(1 + monthlyRate, -duration));
            const totalAmount = monthlyPayment * duration;
            
            $('#monthly-payment').text(monthlyPayment.toFixed(2) + ' €');
            $('#total-amount').text(totalAmount.toFixed(2) + ' €');
            $('#interest-rate').text(interestRate + ' %');
            $('#calculator-result').show();
        }
    });

    // Afficher/masquer le mot de passe
    $('.toggle-password').click(function() {
        const input = $(this).siblings('input');
        const icon = $(this).find('i');
        
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
});

// Fonction pour afficher les alertes SweetAlert
function showAlert(type, title, message) {
    Swal.fire({
        icon: type,
        title: title,
        text: message,
        confirmButtonColor: '#FF7F00'
    });
}