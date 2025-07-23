document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("modalAcesso");
  const form = modal.querySelector("form");

  document.getElementById("btnNovoAcesso").addEventListener("click", () => {
    document.getElementById("modalTitulo").textContent = "Adicionar Acesso";
    modal.classList.remove("hidden");

    form.reset();
    document.getElementById("acesso_id").value = "";
  });

  document
    .getElementById("btnFecharModal")
    .addEventListener("click", function () {
      modal.classList.add("hidden");
    });

  document
    .getElementById("pesquisaAcesso")
    .addEventListener("input", function () {
      const termo = this.value.toLowerCase();
      const cards = document.querySelectorAll("#listaAcessos > div");

      cards.forEach((card) => {
        const titulo = card.querySelector("h3").textContent.toLowerCase();
        card.style.display = titulo.includes(termo) ? "block" : "none";
      });
    });

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(form);

    fetch("controllers/guardar_acesso.php", {
      method: "POST",
      body: formData,
    })
      .then(async (res) => {
        const texto = await res.text();

        try {
          const resposta = JSON.parse(texto);

          if (resposta.sucesso) {
            modal.classList.add("hidden");

            fetch("controllers/pm-home-fragment.php")
              .then((res) => res.text())
              .then((html) => {
                document.getElementById("listaAcessos").innerHTML = html;
                atualizarListeners();
              });
          } else {
            alert("Erro ao guardar o acesso.");
          }
        } catch (e) {
          console.error("Resposta não é JSON válido:", texto);
          alert("Erro na comunicação com o servidor.");
        }
      })
      .catch((err) => {
        console.error("Erro na comunicação com o servidor:", err);
        alert("Erro na comunicação com o servidor.");
      });
  });

  function atualizarListeners() {
    document.querySelectorAll(".btnEditarAcesso").forEach((btn) => {
      btn.addEventListener("click", () => {
        const id = btn.getAttribute("data-id");
        fetch("controllers/carregar_acesso.php?id=" + id)
          .then((res) => res.json())
          .then((dados) => {
            document.getElementById("modalTitulo").textContent =
              "Editar Acesso";
            document.getElementById("acesso_id").value = dados.id;
            document.getElementById("nome_servico").value = dados.nome_servico;
            document.getElementById("url_acesso").value = dados.url_acesso;
            document.getElementById("username").value = dados.username;
            document.getElementById("senha").value = dados.senha;
            document.getElementById("notas").value = dados.notas;
            modal.classList.remove("hidden");
          });
      });
    });

    document.querySelectorAll(".btnCopiarSenha").forEach((btn) => {
      btn.addEventListener("click", () => {
        const id = btn.getAttribute("data-id");
        fetch("controllers/desencriptar.php?id=" + id)
          .then((res) => res.text())
          .then((txt) =>
            navigator.clipboard.writeText(txt).then(() => {
              const alerta = document.getElementById("alertaSenha");
              alerta.classList.remove("hidden");
              alerta.classList.add("opacity-100");

              setTimeout(() => {
                alerta.classList.remove("opacity-100");
                setTimeout(() => alerta.classList.add("hidden"), 500);
              }, 1000);
            })
          );
      });
    });
  }

  atualizarListeners();
});
