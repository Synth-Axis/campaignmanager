import assert from 'node:assert/strict';
import { mkdir, writeFile } from 'node:fs/promises';

await mkdir(new URL('../.cache/', import.meta.url), { recursive: true });
await writeFile(new URL('../.cache/fixture-accesses.json', import.meta.url), '{}');

const base = process.env.TEST_BASE_URL || 'http://127.0.0.1:8087';
const auth = { 'X-Test-Auth': '1' };
let checks = 0;
async function request(path, options = {}) {
  return fetch(base + path, { redirect: 'manual', headers: auth, ...options });
}
for (const path of ['/login', '/register', '/recuperar_password', '/redefinir_password', '/home', '/home-center', '/app-center', '/pm-home', '/password-manager', '/publico', '/campanhas', '/campanhas.php', '/campanhas.php?acao=criar']) {
  const response = await request(path);
  const html = await response.text();
  assert.equal(response.status, 200, path);
  assert.ok(html.includes('<title>Lynx App Center</title>'), path);
  assert.ok(!/Fatal error|Warning:|Parse error/.test(html), path);
  assert.ok(html.includes('/assets/js/theme.js'), path);
  const localAssets = [...html.matchAll(/(?:src|href)="(\/(?:assets\/js|dist)\/[^"?]+)(?:\?[^"\s]*)?"/g)].map(m => m[1]);
  for (const asset of localAssets) assert.equal((await request(asset)).status, 200, asset);
  checks++;
}
for (const path of ['/api/pesquisar_contactos.php?q=Demo&page=1', '/api/crescimento_contactos.php', '/api/contactos_segmento.php?segmento_id=1&page=1', '/api/get_contacto.php?id=1024']) {
  const response = await request(path);
  assert.equal(response.status, 200, path);
  assert.ok(response.headers.get('content-type').includes('application/json'), path);
  await response.json();
  checks++;
}
const noAuth = await request('/api/pesquisar_contactos.php', { headers: {} });
assert.equal(noAuth.status, 401);
assert.ok((await noAuth.json()).erro);
assert.equal((await request('/home-center', { headers: {} })).headers.get('location'), '/login');
assert.equal((await request('/password-manager/guardar')).status, 405);
assert.equal((await request('/page-does-not-exist')).status, 404);
checks += 4;

async function save(path, fields) {
  const response = await request(path, { method: 'POST', body: new URLSearchParams(fields) });
  assert.equal(response.status, 200);
  assert.equal((await response.json()).sucesso, true);
}
await save('/password-manager/guardar', { nome_servico: 'Serviço fictício', url_acesso: 'https://example.com', username: 'demo', senha: 'Senha fictícia € 2026', notas: 'Só para teste' });
const first = await (await request('/password-manager/carregar?id=1')).json();
assert.equal(first.senha, 'Senha fictícia € 2026');
assert.ok(!('senha_criptografada' in first));
assert.ok((await (await request('/password-manager/cards')).text()).includes('Serviço fictício'));
await save('/controllers/guardar_acesso.php', { id: '1', nome_servico: 'Serviço atualizado', url_acesso: 'https://example.com', username: 'demo.editado', senha: 'Senha editada 2026', notas: 'Atualizado' });
const updated = await (await request('/controllers/carregar_acesso.php?id=1')).json();
assert.equal(updated.nome_servico, 'Serviço atualizado');
assert.equal(updated.username, 'demo.editado');
assert.equal(updated.senha, 'Senha editada 2026');
assert.equal(await (await request('/controllers/desencriptar.php?id=1')).text(), 'Senha editada 2026');
assert.ok((await (await request('/controllers/pm-home-fragment.php')).text()).includes('Serviço atualizado'));
assert.equal((await request('/password-manager/carregar?id=9999999')).status, 404);
assert.equal((await request('/password-manager/carregar?id=invalid')).status, 400);
checks += 10;

// Read-only export selects one existing contact; no contact or list is changed.
const exportResponse = await request('/api/exportar_contactos.php', { method: 'POST', body: new URLSearchParams([['formato', 'csv'], ['campos[]', 'publico_id'], ['contactosSelecionados[]', '1024']]) });
assert.equal(exportResponse.status, 200);
assert.ok((await exportResponse.text()).includes('1024'));
checks++;
console.log(`HTTP checks passed: ${checks}; Password Manager used fictional data only.`);
