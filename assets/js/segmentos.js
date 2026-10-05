document.addEventListener("DOMContentLoaded", () => {
  const painel = document.getElementById("contactos-segmento");
  if (!painel) return;

  const botoes = document.querySelectorAll(".ver-contactos-segmento");
  const titulo = document.getElementById("nome-segmento-selecionado");
  const pesquisa = document.getElementById("pesquisar-contactos-segmento");
  const estado = document.getElementById("estado-contactos-segmento");
  const tabela = document.getElementById("tabela-contactos-segmento");
  const paginacao = document.getElementById("paginacao-contactos-segmento");
  const paginaTexto = document.getElementById("segmento-pagina-atual");
  const anterior = document.getElementById("segmento-pagina-anterior");
  const seguinte = document.getElementById("segmento-pagina-seguinte");
  let selecionado = null;
  let pagina = 1;
  let pedido = null;
  let debounce = null;

  function mensagem(texto, erro = false) {
    estado.textContent = texto;
    estado.classList.toggle("text-negative", erro);
    estado.classList.toggle("text-neutral", !erro);
  }

  async function carregarContactos(novaPagina = 1) {
    clearTimeout(debounce);
    pedido?.abort();
    const atual = new AbortController();
    let erroMensagem = "Não foi possível carregar os contactos. Tente novamente.";
    pedido = atual;
    painel.setAttribute("aria-busy", "true");
    tabela.replaceChildren();
    paginacao.classList.add("hidden");
    mensagem("A carregar contactos…");

    try {
      const params = new URLSearchParams({
        segmento_id: selecionado.dataset.segmentoId,
        q: pesquisa.value.trim(),
        page: novaPagina,
      });
      const resposta = await fetch(`/api/contactos_segmento.php?${params}`, {
        signal: atual.signal,
      });
      const dados = await resposta.json();
      if (!resposta.ok) {
        erroMensagem = dados.erro || erroMensagem;
        throw new Error(erroMensagem);
      }
      if (atual !== pedido) return;

      pagina = dados.pagina;
      const fragmento = document.createDocumentFragment();
      for (const contacto of dados.registos) {
        const linha = document.createElement("tr");
        linha.className = "border-b border-line";
        for (const campo of ["nome", "email", "gestor_nome", "canal_nome", "lista_nome"]) {
          const celula = document.createElement("td");
          celula.className = "p-3";
          celula.textContent = contacto[campo] ?? "—";
          linha.appendChild(celula);
        }
        fragmento.appendChild(linha);
      }
      tabela.replaceChildren(fragmento);
      if (!dados.total) {
        mensagem(pesquisa.value.trim() ? "Nenhum contacto corresponde à pesquisa neste segmento." : "Este segmento ainda não tem contactos associados.");
      } else {
        const inicio = (pagina - 1) * dados.por_pagina + 1;
        const fim = inicio + dados.registos.length - 1;
        mensagem(`A mostrar ${inicio}–${fim} de ${dados.total} contactos.`);
        paginaTexto.textContent = `Página ${pagina} de ${dados.total_paginas}`;
        anterior.disabled = pagina <= 1;
        seguinte.disabled = pagina >= dados.total_paginas;
        paginacao.classList.remove("hidden");
      }
    } catch (erro) {
      if (erro.name !== "AbortError" && atual === pedido) {
        mensagem(erroMensagem, true);
      }
    } finally {
      if (atual === pedido) painel.setAttribute("aria-busy", "false");
    }
  }

  botoes.forEach((botao) => {
    botao.addEventListener("click", () => {
      selecionado = botao;
      botoes.forEach((item) => {
        item.setAttribute("aria-expanded", String(item === botao));
        item.closest("tr").classList.toggle("bg-highlight/20", item === botao);
      });
      titulo.textContent = botao.dataset.segmentoNome;
      pesquisa.value = "";
      painel.classList.remove("hidden");
      titulo.focus({ preventScroll: true });
      painel.scrollIntoView({ block: "start" });
      carregarContactos();
    });
  });

  pesquisa.addEventListener("input", () => {
    clearTimeout(debounce);
    pedido?.abort();
    debounce = setTimeout(() => carregarContactos(), 200);
  });
  anterior.addEventListener("click", () => carregarContactos(pagina - 1));
  seguinte.addEventListener("click", () => carregarContactos(pagina + 1));
  document.getElementById("fechar-contactos-segmento").addEventListener("click", () => {
    clearTimeout(debounce);
    pedido?.abort();
    pedido = null;
    painel.classList.add("hidden");
    painel.setAttribute("aria-busy", "false");
    botoes.forEach((botao) => {
      botao.setAttribute("aria-expanded", "false");
      botao.closest("tr").classList.remove("bg-highlight/20");
    });
    selecionado?.focus();
  });
});
