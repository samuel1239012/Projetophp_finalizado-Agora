<?php
require_once("../conexao.php");

$email = $_POST["email"];

$sql = "SELECT e_mail, senha, pk_id, habilita FROM usuarios WHERE e_mail='$email'";
$query = mysqli_query($conecta, $sql);

if(mysqli_num_rows($query) == 0){
    echo "não existe esse e-mail";
    // redirecionar para login
    exit;
}

date_default_timezone_set('America/Sao_Paulo');

echo "e-mail válido";
$row = mysqli_fetch_object($query);
$senha = $row->senha;
$pk_id = $row->pk_id;
$habilita = $row->habilita;
$data = date('d/m/Y H:i:s');

$codigo_temp = $email . $senha . $pk_id . $data;
$codigo_temp_hashed = hash('sha512', $codigo_temp);
$codigo = substr($codigo_temp_hashed, 0, 4) . substr($codigo_temp_hashed, -4);

$sql = "UPDATE usuarios SET recuperar_senha='$codigo' WHERE pk_id=$pk_id"; 
mysqli_query($conecta, $sql);

require_once("../../vendor/plugins/PHPMailer/src/PHPMailer.php");
require_once("../../vendor/plugins/PHPMailer/src/SMTP.php");
require_once("../../vendor/plugins/PHPMailer/src/Exception.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp-relay.brevo.com'; // Aqui você deve colocar o host do seu servidor SMTP
    $mail->SMTPAuth   = true;
    $mail->Username   = 'samueleuyt@gmail.com'; // Aqui você deve colocar o seu e-mail
    $mail->Password   = 'bRJjx9qShTtgNKMO'; // Aqui você deve colocar a senha do seu e-mail
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587; // Porta do servidor SMTP

    //Recipients
    $mail->setFrom('samueleuyt@gmail.co', 'S.J News');
    $mail->addAddress($email, $nome); // Aqui você adiciona o e-mail do destinatário

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Recuperar Conta';
    $mail->Body    = 'Seu codigo para recuperar a sua conta : ' . $codigo;

    $mail->send();
    header("location: recover-password.php");
} catch (Exception $e) {
    echo "Erro ao enviar e-mail: {$mail->ErrorInfo}";
}
?>
