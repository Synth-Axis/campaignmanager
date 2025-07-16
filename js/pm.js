document.addEventListener("DOMContentLoaded", function () {
  function abrirModalNovo() {
    document.getElementById("modalTitulo").textContent = "Adicionar Acesso";
    document.getElementById("modalAcesso").classList.remove("hidden");
    document.getElementById("acesso_id").value = "";
    document.getElementById("nome_servico").value = "";
    document.getElementById("url_acesso").value = "";
    document.getElementById("username").value = "";
    document.getElementById("email").value = "";
    document.getElementById("senha").value = "";
    document.getElementById("notas").value = "";
  }

  function fecharModal() {
    document.getElementById("modalAcesso").classList.add("hidden");
  }

  function editarAcesso(id) {
    fetch("carregar_acesso.php?id=" + id)
      .then((res) => res.json())
      .then((dados) => {
        document.getElementById("modalTitulo").textContent = "Editar Acesso";
        document.getElementById("acesso_id").value = dados.id;
        document.getElementById("nome_servico").value = dados.nome_servico;
        document.getElementById("url_acesso").value = dados.url_acesso;
        document.getElementById("username").value = dados.username;
        document.getElementById("email").value = dados.email;
        document.getElementById("senha").value = dados.senha; // senha desencriptada vinda do PHP
        document.getElementById("notas").value = dados.notas;
        document.getElementById("modalAcesso").classList.remove("hidden");
      });
  }

  function copiarSenha(id) {
    fetch("desencriptar.php?id=" + id)
      .then((res) => res.text())
      .then((txt) => navigator.clipboard.writeText(txt));
  }
});
