<?php
$nome = "";
$email = "";
$telefone = "";
$mensagem = "";
$tipoMensagem = "";

// Para testes locais ou se o getenv não funcionar, você pode forçar a URL aqui (NÃO RECOMENDADO EM PRODUÇÃO)
$db_url = getenv("DATABASE_URL");

if (!$db_url) {
    die("Erro: variável DATABASE_URL não encontrada.");
}

$conn = pg_connect($db_url);

if (!$conn) {
    die("Erro ao conectar no banco de dados.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $telefone = trim($_POST["telefone"] ?? "");

    if ($nome === "" || $email === "" || $telefone === "") {
        $mensagem = "Preencha todos os campos.";
        $tipoMensagem = "erro";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = "Digite um e-mail válido.";
        $tipoMensagem = "erro";
    } else {
        $query = "INSERT INTO usuarios (nome, email, telefone) VALUES ($1, $2, $3)";
        $result = pg_query_params($conn, $query, [$nome, $email, $telefone]);

        if ($result) {
            $mensagem = "Usuário cadastrado com sucesso no banco de dados.";
            $tipoMensagem = "sucesso";

            $nome = "";
            $email = "";
            $telefone = "";
        } else {
            $mensagem = "Erro ao salvar no banco de dados.";
            $tipoMensagem = "erro";
        }
    }
}

$queryLista = "SELECT id, nome, email, telefone FROM usuarios ORDER BY id DESC";
$resultLista = pg_query($conn, $queryLista);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistema Web II - Cadastro de Usuário</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      max-width: 900px;
      margin: 30px auto;
      padding: 20px;
      background: #f4f6f8;
      color: #222;
    }

    .container {
      background: #fff;
      padding: 30px; /* Aumentado um pouco o padding para respirar mais */
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    h1 {
      margin-top: 0;
      color: #1a1a1a;
      margin-bottom: 10px;
    }

    p {
      color: #555;
      margin-bottom: 25px;
    }

    /* ==== ALTERAÇÕES PRINCIPAIS AQUI ==== */
    /* Cria um grid de 2 colunas para o formulário */
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr; /* Duas colunas de tamanho igual */
      gap: 15px 20px; /* Espaço entre linhas (15px) e colunas (20px) */
      align-items: end; /* Alinha os botões com a base dos inputs */
      margin-bottom: 20px;
    }

    .form-group {
      display: flex;
      flex-direction: column;
    }

    label {
      font-weight: bold;
      margin-bottom: 5px; /* Substitui o <br> do HTML */
      color: #111;
    }

    input[type="text"],
    input[type="email"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      box-sizing: border-box;
      /* Removido max-width e margins que estavam quebrando o layout anterior */
    }

    /* O botão ocupa a última célula da segunda linha da grade */
    .btn-container {
        display: flex;
        align-items: flex-end; /* Garante que o botão fique na base da célula */
    }

    button {
      background: #0d6efd;
      color: white;
      border: none;
      padding: 10px 18px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 15px;
      font-weight: bold;
      height: 40px; /* Força uma altura para alinhar com os inputs */
    }
    /* ==================================== */

    button:hover {
      background: #0b5ed7;
    }

    .mensagem {
      margin-top: 20px;
      padding: 12px;
      border-radius: 6px;
      font-weight: bold;
    }

    .sucesso {
      background: #d1e7dd;
      color: #0f5132;
      border: 1px solid #badbcc;
    }

    .erro {
      background: #FF0000;
      color: #470000;
      border: 1px solid #1A0000;
    }

    h2 {
        margin-top: 40px;
        color: #1a1a1a;
        margin-bottom: 15px;
    }

    table {
      width: 100%;
