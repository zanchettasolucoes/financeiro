function login() {
    // Simulação de validação de login
    var loginValido = true; // Altere para 'false' para simular falha
  
    if (loginValido) {
      alert("Login OK! Redirecionando...");
      // Redireciona o usuário após o login bem-sucedido
      window.location.href = "pagina-inicial.html";
    } else {
      alert("Login falhou! Tente novamente.");
    }
  }
  
  // Simulando o envio do formulário de login
  document.getElementById("loginForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Previne o comportamento padrão do formulário
    login();
  });
  