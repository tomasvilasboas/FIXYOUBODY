<?php
// admin_chat.php
include "valida_session.php"; // Valida a sessão do usuário
$login = $_SESSION["login"] ?? null;

if (!$login) {
    header("Location: login.php");
    exit;
}

$ligax = mysqli_connect(
    'localhost',
    'root',
    'root',
    '4640454_fixyoubody'
);if (!$ligax) {
    echo '<p>Falha na Ligação.</p>';
    exit;
}

// Busca as informações do utilizador logado
$consulta = "SELECT * FROM utilizadores WHERE login='" . mysqli_real_escape_string($ligax, $login) . "'";
$result = mysqli_query($ligax, $consulta);
if (!$result) {
    echo "<p>Erro ao consultar utilizador: " . mysqli_error($ligax) . "</p>";
    exit;
}

$registo = mysqli_fetch_assoc($result);
if (!$registo || $registo['sit_ut'] != "ADM") {
    exit("Acesso negado: Apenas Administradores têm acesso.");
}

$admin_id = intval($registo['cod_ut']);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Chat</title>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Open+Sans&display=swap" rel="stylesheet">
  <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body, html {
        font-family: 'Open Sans', sans-serif;
        background-color: #f1f1f1;
        color: #333;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    header {
        position: fixed;
        top: 0;
        width: 100%;
        padding: 20px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(0, 0, 0, 0.6);
        z-index: 10;
        height: 80px;
    }

    header h1 {
        font-family: 'Bebas Neue', cursive;
        font-size: 32px;
        color: #b19a4f;
        letter-spacing: 2px;
    }

    nav ul {
        list-style: none;
        display: flex;
        gap: 20px;
    }

    nav ul li a {
        color: #fff;
        text-decoration: none;
        font-weight: bold;
        padding: 8px 16px;
        border: 2px solid transparent;
        border-radius: 20px;
        transition: 0.3s ease;
    }

    nav ul li a:hover {
        border-color: #b19a4f;
        background-color: #b19a4f;
    }

    /* ESTILOS ESPECÍFICOS DO CHAT */
    .chat-container {
        display: flex;
        margin-top: 120px;
        min-height: calc(100vh - 200px);
        padding: 20px;
    }

    #users {
        width: 30%;
        border-right: 1px solid #ccc;
        padding: 20px;
        background-color: #fff;
        height: 100%;
        overflow-y: auto;
        box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    #users h3 {
        font-size: 20px;
        color: #333;
        margin-bottom: 15px;
        text-align: center;
    }

    #user-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .user {
        padding: 12px;
        background-color: #f9f9f9;
        margin-bottom: 10px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
    }

    .user:hover {
        background-color: #b19a4f;
        color: white;
        transform: translateX(5px);
    }

    .user.active {
        background-color: #b19a4f;
        color: white;
        font-weight: bold;
    }

    #search-user {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
        outline: none;
        transition: border-color 0.3s ease;
    }

    #search-user:focus {
        border-color: #b19a4f;
    }

    #chat {
        width: 70%;
        padding: 20px;
        display: flex;
        flex-direction: column;
    }

    #chat h3 {
        color: #333;
        margin-bottom: 20px;
        text-align: center;
        font-family: 'Bebas Neue', cursive;
        font-size: 24px;
        color: #b19a4f;
    }

    #chat-box {
        flex-grow: 1;
        height: 60vh;
        overflow-y: auto;
        border: 1px solid #ccc;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .message {
        max-width: 70%;
        padding: 12px 15px;
        margin: 10px 0;
        border-radius: 10px;
        position: relative;
        clear: both;
        word-wrap: break-word;
    }

    .message.right {
        background-color: #b19a4f;
        color: white;
        float: right;
        text-align: right;
    }

    .message.left {
        background-color: #f0f0f0;
        color: #333;
        float: left;
        text-align: left;
    }

    .timestamp {
        font-size: 11px;
        color: #666;
        margin-top: 5px;
        display: block;
    }

    .message-footer {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 5px;
        margin-top: 5px;
    }

    .delete-btn {
        background: transparent;
        border: none;
        color: inherit;
        font-size: 12px;
        cursor: pointer;
        padding: 2px 6px;
        border-radius: 4px;
        transition: all 0.2s ease;
    }

    .delete-btn:hover {
        background-color: rgba(0, 0, 0, 0.1);
    }

    #send-message-container {
        display: flex;
        gap: 10px;
    }

    #message {
        flex-grow: 1;
        padding: 12px 15px;
        font-size: 16px;
        border: 2px solid #ccc;
        border-radius: 8px;
        box-sizing: border-box;
        background-color: #fff;
        transition: all 0.3s ease;
    }

    #message:focus {
        border-color: #b19a4f;
        box-shadow: 0 0 8px rgba(177, 154, 79, 0.3);
        outline: none;
    }

    #send-btn {
        padding: 12px 25px;
        background-color: #b19a4f;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    #send-btn:hover {
        background-color: #9a7f3a;
    }
        .no-style-link {
      color: inherit;
      text-decoration: none;
    }

    .no-style-link:hover {
      color: inherit;
      text-decoration: none;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .chat-container {
            flex-direction: column;
            margin-top: 100px;
        }

        #users, #chat {
            width: 100%;
        }

        #users {
            margin-bottom: 20px;
            height: auto;
            max-height: 300px;
        }

        #chat-box {
            height: 35vh;
        }

        nav ul {
            flex-direction: column;
            position: fixed;
            top: 80px;
            right: 0;
            background: rgba(0, 0, 0, 0.95);
            width: 220px;
            height: 100%;
            padding-top: 20px;
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
        }

        nav ul.show {
            transform: translateX(0);
        }

        .hamburger {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: 30px;
            height: 21px;
            background: transparent;
            border: none;
            cursor: pointer;
            z-index: 20;
            position: absolute;
            right: 20px;
            top: 25px;
            padding: 0;
        }

        .hamburger div {
            width: 100%;
            height: 3px;
            background-color: #b19a4f;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .hamburger.active div:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .hamburger.active div:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active div:nth-child(3) {
            transform: rotate(-45deg) translate(5px, -5px);
        }
    }
  </style>  
</head>
<body>

<header>
    <h1><a href="pagina_principal_adm.php" class="no-style-link">FIX YOU BODY</a></h1>
    
    <button class="hamburger" id="hamburger" aria-label="Menu">
        <div></div>
        <div></div>
        <div></div>
    </button>

    <nav>
        <ul id="menu-principal">
            <li><a href="admin_chat.php">Chats</a></li>
            <li><a href="inserir_utilizadores_frm.htm">Adicionar utilizadores</a></li>
            <li><a href="remover_utilizadores_listados.php">Listagem utilizadores</a></li>
            <li><a href="marcacoes_frm.php">Adicionar Marcações</a></li>
            <li><a href="remover_marcacoes_listadas.php">Listagem Marcações</a></li>
            <li><a href="marcacoes_calendario_adm.php">Calendário Marcações</a></li>
            <li><a href="avaliacoes.html">Avaliações</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<div class="chat-container">
  <div id="users">
    <h3>Conversas</h3>
    <input type="text" id="search-user" placeholder="Pesquisar utilizador..." />
    <div id="user-list"></div>
  </div>

  <div id="chat">
    <h3>Conversa com <span id="chat-user">...</span></h3>
    <div id="chat-box"></div>
    <div id="send-message-container">
      <input type="text" id="message" placeholder="Escreva sua mensagem..." autocomplete="off" />
      <button id="send-btn">Enviar</button>
    </div>
  </div>
</div>

<script>
  // Variáveis globais
  const admin_id = <?= json_encode($admin_id) ?>;
  let current_user_id = null;
  let current_user_login = "root";
  let refreshInterval;

  // Menu hamburguer
  const hamburger = document.getElementById('hamburger');
  const menuPrincipal = document.getElementById('menu-principal');
  
  hamburger.addEventListener('click', () => {
    hamburger.classList.toggle('active');
    menuPrincipal.classList.toggle('show');
  });

  // Carregar lista de utilizadores
  async function loadUsers() {
    try {
      const response = await fetch('get_users.php');
      if (!response.ok) throw new Error('Erro ao carregar utilizadores');
      
      const users = await response.json();
      displayUsers(users);
      
      // Se não há usuário selecionado, selecione o primeiro da lista
      if (!current_user_id && users.length > 0) {
        const firstUser = users.find(u => u.cod_ut !== admin_id);
        if (firstUser) {
          selectUser(firstUser.cod_ut, firstUser.login);
        }
      }
    } catch (error) {
      console.error('Erro:', error);
      document.getElementById('user-list').innerHTML = '<p>Erro ao carregar conversas.</p>';
    }
  }

  // Exibir lista de utilizadores
  function displayUsers(users) {
    const userList = document.getElementById('user-list');
    userList.innerHTML = 'root';
    
    users.forEach(user => {
      if (user.cod_ut === admin_id) return; // Pula o admin
      
      const userElement = document.createElement('div');
      userElement.className = 'user';
      if (user.cod_ut === current_user_id) {
        userElement.classList.add('active');
      }
      
      userElement.innerHTML = `
        <span>${user.login}</span>
      `;
      
      userElement.dataset.id = user.cod_ut;
      userElement.dataset.login = user.login;
      
      userElement.addEventListener('click', () => {
        selectUser(user.cod_ut, user.login);
      });
      
      userList.appendChild(userElement);
    });
  }

  // Selecionar um usuário para conversar
  function selectUser(userId, userLogin) {
    // Remove a classe 'active' de todos os usuários
    document.querySelectorAll('.user').forEach(u => {
      u.classList.remove('active');
    });
    
    // Adiciona a classe 'active' ao usuário selecionado
    const selectedUser = document.querySelector(`.user[data-id="${userId}"]`);
    if (selectedUser) {
      selectedUser.classList.add('active');
    }
    
    current_user_id = userId;
    current_user_login = userLogin;
    document.getElementById('chat-user').textContent = userLogin;
    
    // Carrega as mensagens
    loadMessages();
    
    // Rola para o usuário selecionado
    if (selectedUser) {
      selectedUser.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
  }

  // Carregar mensagens da conversa
  async function loadMessages() {
    if (!current_user_id) return;
    
    const chatBox = document.getElementById('chat-box');
    chatBox.innerHTML = '<p>Carregando mensagens...</p>';
    
    try {
      const response = await fetch(`fetch_messages_adm.php?user_id=${current_user_id}`);
      if (!response.ok) throw new Error('Erro ao carregar mensagens');
      
      const messagesHtml = await response.text();
      chatBox.innerHTML = messagesHtml;
      chatBox.scrollTop = chatBox.scrollHeight;
    } catch (error) {
      console.error('Erro:', error);
      chatBox.innerHTML = '<p>Erro ao carregar mensagens.</p>';
    }
  }

  // Enviar mensagem
  async function sendMessage() {
    const messageInput = document.getElementById('message');
    const message = messageInput.value.trim();
    
    if (!message || !current_user_id) {
      messageInput.focus();
      return;
    }
    
    try {
      const formData = new FormData();
      formData.append('receiver_id', current_user_id);
      formData.append('message', message);
      
      const response = await fetch('send_message_adm.php', {
        method: 'POST',
        body: formData
      });
      
      const result = await response.json();
      
      if (result.status === 'success') {
        messageInput.value = 'root';
        loadMessages();
      } else {
        alert('Erro ao enviar mensagem: ' + (result.message || 'Erro desconhecido'));
      }
    } catch (error) {
      console.error('Erro:', error);
      alert('Erro ao enviar mensagem. Por favor, tente novamente.');
    }
  }

  // Apagar mensagem
  async function deleteMessage(messageId) {
    if (!confirm('Tem certeza que deseja apagar esta mensagem?')) return;
    
    try {
      const formData = new FormData();
      formData.append('id', messageId);
      
      const response = await fetch('delete_message.php', {
        method: 'POST',
        body: formData
      });
      
      const result = await response.text();
      
      if (result.trim() === 'ok') {
        loadMessages();
      } else {
        alert('Erro ao apagar mensagem: ' + result);
      }
    } catch (error) {
      console.error('Erro:', error);
      alert('Erro ao apagar mensagem. Por favor, tente novamente.');
    }
  }

  // Pesquisar usuários
  document.getElementById('search-user').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const users = document.querySelectorAll('.user');
    
    users.forEach(user => {
      const userName = user.dataset.login.toLowerCase();
      user.style.display = userName.includes(searchTerm) ? 'flex' : 'none';
    });
  });

  // Enviar mensagem ao pressionar Enter
  document.getElementById('message').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      sendMessage();
    }
  });

  // Botão enviar
  document.getElementById('send-btn').addEventListener('click', sendMessage);

  // Atualizar mensagens periodicamente
  function startRefresh() {
    refreshInterval = setInterval(() => {
      if (current_user_id) {
        loadMessages();
      }
    }, 5000); // Atualiza a cada 5 segundos
  }

  // Inicialização
  window.onload = function() {
    loadUsers();
    startRefresh();
    
    // Torna a função deleteMessage acessível globalmente
    window.deleteMessage = deleteMessage;
  };

  // Parar de atualizar quando a página perde foco
  window.onblur = function() {
    clearInterval(refreshInterval);
  };

  // Retomar atualização quando a página ganha foco
  window.onfocus = function() {
    startRefresh();
    if (current_user_id) {
      loadMessages();
    }
  };
</script>

</body>
</html>