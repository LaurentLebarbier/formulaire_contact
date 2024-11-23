<?php
// Configuration de la base de données
$host = 'localhost'; // ou 127.0.0.1
$dbname = 'contact_form';
$username = 'root'; // remplacez par votre utilisateur MySQL
$password = 'h9xt2ya1'; // remplacez par votre mot de passe MySQL

// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Insertion dans la base de données
    $stmt = $pdo->prepare("INSERT INTO contacts (name, email, message) VALUES (:name, :email, :message)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':message', $message);

    if ($stmt->execute()) {
        // Envoi de l'email
        $to = 'votre_email@example.com'; // Remplacez par votre adresse e-mail
        $subject = 'Nouveau message de contact';
        $email_message = "Nom: $name\nEmail: $email\nMessage:\n$message";
        $headers = "From: $email";

        if (mail($to, $subject, $email_message, $headers)) {
            echo "Message envoyé avec succès et enregistré dans la base de données.";
        } else {
            echo "Le message a été enregistré, mais l'envoi de l'e-mail a échoué.";
        }
    } else {
        echo "Erreur lors de l'enregistrement dans la base de données.";
    }
}
?>