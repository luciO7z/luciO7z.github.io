<?php
// Imposta il tipo di risposta
header('Content-Type: application/json');

// Configurazione
$recipient_email = "tuoindirizzo@example.com"; // Sostituisci con la tua email
$recipient_name  = "Tuo Nome"; // Opzionale

// Funzione per pulire i dati
function clean_input($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

// Controllo metodo richiesta
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
    exit;
}

// Recupero e pulizia dati
$name    = clean_input($_POST["name"] ?? '');
$email   = clean_input($_POST["email"] ?? '');
$subject = clean_input($_POST["subject"] ?? '');
$message = clean_input($_POST["message"] ?? '');

// Validazione campi
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo json_encode(["status" => "error", "message" => "Please fill in all fields"]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "error", "message" => "Invalid email address"]);
    exit;
}

// Creazione contenuto email
$email_subject = "[Contact Form] $subject";
$email_body  = "You have received a new message from your website contact form:\n\n";
$email_body .= "Name: $name\n";
$email_body .= "Email: $email\n";
$email_body .= "Subject: $subject\n";
$email_body .= "Message:\n$message\n";

// Header email
$headers = "From: $name <$email>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Invio email
if (mail($recipient_email, $email_subject, $email_body, $headers)) {
    echo json_encode(["status" => "success", "message" => "Your message has been sent. Thank you!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Unable to send email. Please try again later."]);
}
?>
