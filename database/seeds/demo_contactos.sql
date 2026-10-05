-- LynxApp: 1.000 contactos ficticios, 8 listas, 4 canais, 8 gestores e 6 segmentos.
-- Selecionar primeiro a base de dados da aplicacao no phpMyAdmin.
-- Compativel com MariaDB 10.4 / MySQL 5.7+; executar o ficheiro completo.
-- Nao elimina nem altera registos existentes. Pode ser executado novamente.
-- Os emails example.com sao ficticios; nao usar estes contactos para envios reais.
-- Cada contacto pertence a UMA lista e pode pertencer a VARIOS segmentos.
-- Inclui regras automaticas para considerar tambem os contactos criados depois.

SET NAMES utf8mb4;

-- Estas duas tabelas ainda nao existem no projeto.
-- O DDL e executado antes da transacao porque provoca commit implicito.
CREATE TABLE IF NOT EXISTS segmentos (
    segmento_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    segmento_nome VARCHAR(150) NOT NULL,
    descricao TEXT DEFAULT NULL,
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (segmento_id),
    UNIQUE KEY uq_segmentos_nome (segmento_nome)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS publico_segmentos (
    publico_id INT UNSIGNED NOT NULL,
    segmento_id INT UNSIGNED NOT NULL,
    associado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (publico_id, segmento_id),
    KEY idx_publico_segmentos_segmento (segmento_id),
    CONSTRAINT fk_publico_segmentos_contacto FOREIGN KEY (publico_id)
        REFERENCES publico (publico_id) ON DELETE CASCADE,
    CONSTRAINT fk_publico_segmentos_segmento FOREIGN KEY (segmento_id)
        REFERENCES segmentos (segmento_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabelas auxiliares apenas nesta ligacao; nao ficam guardadas na base de dados.
DROP TEMPORARY TABLE IF EXISTS tmp_lynx_demo_numeros;
DROP TEMPORARY TABLE IF EXISTS tmp_lynx_demo_listas;
DROP TEMPORARY TABLE IF EXISTS tmp_lynx_demo_gestores;
DROP TEMPORARY TABLE IF EXISTS tmp_lynx_demo_contactos;

CREATE TEMPORARY TABLE tmp_lynx_demo_numeros (n INT NOT NULL PRIMARY KEY);
INSERT INTO tmp_lynx_demo_numeros (n)
SELECT 1 + unidades.n + dezenas.n * 10 + centenas.n * 100
FROM (SELECT 0 n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3
      UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7
      UNION ALL SELECT 8 UNION ALL SELECT 9) unidades
CROSS JOIN (SELECT 0 n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3
      UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7
      UNION ALL SELECT 8 UNION ALL SELECT 9) dezenas
CROSS JOIN (SELECT 0 n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3
      UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7
      UNION ALL SELECT 8 UNION ALL SELECT 9) centenas;

CREATE TEMPORARY TABLE tmp_lynx_demo_listas (
    ordem INT NOT NULL PRIMARY KEY,
    nome VARCHAR(150) NOT NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO tmp_lynx_demo_listas (ordem, nome) VALUES
    (1, '[DEMO] Newsletter'),
    (2, '[DEMO] Clientes'),
    (3, '[DEMO] Leads'),
    (4, '[DEMO] Eventos'),
    (5, '[DEMO] Parceiros'),
    (6, '[DEMO] Formação'),
    (7, '[DEMO] Ofertas'),
    (8, '[DEMO] Reativação');

CREATE TEMPORARY TABLE tmp_lynx_demo_gestores (
    ordem INT NOT NULL PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    canal_nome VARCHAR(150) NOT NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO tmp_lynx_demo_gestores (ordem, nome, canal_nome) VALUES
    (1, '[DEMO] Ana Martins',    '[DEMO] Website'),
    (2, '[DEMO] Bruno Costa',    '[DEMO] Loja'),
    (3, '[DEMO] Carla Silva',    '[DEMO] Parceiros'),
    (4, '[DEMO] Diogo Santos',   '[DEMO] Eventos'),
    (5, '[DEMO] Eva Ferreira',   '[DEMO] Website'),
    (6, '[DEMO] Filipe Almeida', '[DEMO] Loja'),
    (7, '[DEMO] Inês Ribeiro',   '[DEMO] Parceiros'),
    (8, '[DEMO] João Pereira',   '[DEMO] Eventos');

CREATE TEMPORARY TABLE tmp_lynx_demo_contactos (
    n INT NOT NULL PRIMARY KEY,
    publico_id INT UNSIGNED NOT NULL UNIQUE
);

START TRANSACTION;

INSERT INTO canal (nome)
SELECT DISTINCT d.canal_nome
FROM tmp_lynx_demo_gestores d
WHERE NOT EXISTS (SELECT 1 FROM canal c WHERE c.nome = d.canal_nome);

INSERT INTO listas (lista_nome)
SELECT d.nome FROM tmp_lynx_demo_listas d
WHERE NOT EXISTS (SELECT 1 FROM listas l WHERE l.lista_nome = d.nome);

INSERT INTO gestor (gestor_nome, canal_id)
SELECT d.nome, c.canal_id
FROM tmp_lynx_demo_gestores d
JOIN canal c ON c.nome = d.canal_nome
WHERE NOT EXISTS (
    SELECT 1 FROM gestor g
    WHERE g.gestor_nome = d.nome AND g.canal_id = c.canal_id
);

INSERT INTO segmentos (segmento_nome, descricao)
SELECT d.nome, d.descricao FROM (
    SELECT '[DEMO] Novos contactos' nome,
           'Contactos de demonstracao registados nos ultimos 30 dias, no momento da execucao.' descricao
    UNION ALL SELECT '[DEMO] Clientes', 'Contactos da lista Clientes.'
    UNION ALL SELECT '[DEMO] Leads', 'Contactos da lista Leads.'
    UNION ALL SELECT '[DEMO] Newsletter', 'Contactos das listas Newsletter, Formacao e Ofertas.'
    UNION ALL SELECT '[DEMO] Canal digital', 'Contactos associados ao canal Website.'
    UNION ALL SELECT '[DEMO] Parceiros', 'Contactos da lista Parceiros ou do canal Parceiros.'
) d
WHERE NOT EXISTS (SELECT 1 FROM segmentos s WHERE s.segmento_nome = d.nome);

INSERT INTO publico (nome, email, gestor_id, canal_id, lista_id, data_registo)
SELECT
    CONCAT(
        ELT(MOD(n.n - 1, 12) + 1,
            'Ana', 'Bruno', 'Carla', 'Diogo', 'Eva', 'Filipe',
            'Inês', 'João', 'Leonor', 'Miguel', 'Sofia', 'Tiago'), ' ',
        ELT(MOD(FLOOR((n.n - 1) / 12), 12) + 1,
            'Silva', 'Santos', 'Ferreira', 'Pereira', 'Costa', 'Oliveira',
            'Martins', 'Rodrigues', 'Almeida', 'Ribeiro', 'Carvalho', 'Gomes'),
        ' (Demo ', LPAD(n.n, 4, '0'), ')'
    ),
    CONCAT('contacto.demo.', LPAD(n.n, 4, '0'), '@example.com'),
    g.gestor_id,
    c.canal_id,
    l.lista_id,
    DATE_SUB(DATE_SUB(NOW(), INTERVAL MOD(n.n - 1, 90) DAY),
             INTERVAL MOD(n.n * 13, 60) SECOND)
FROM tmp_lynx_demo_numeros n
JOIN tmp_lynx_demo_listas dl ON dl.ordem = MOD(n.n - 1, 8) + 1
JOIN listas l ON l.lista_nome = dl.nome
JOIN tmp_lynx_demo_gestores dg
    ON dg.ordem = MOD(FLOOR((n.n - 1) / 8), 8) + 1
JOIN canal c ON c.nome = dg.canal_nome
JOIN (
    SELECT gestor_nome, canal_id, MIN(gestor_id) gestor_id
    FROM gestor GROUP BY gestor_nome, canal_id
) g ON g.gestor_nome = dg.nome AND g.canal_id = c.canal_id
WHERE NOT EXISTS (
    SELECT 1 FROM publico p
    WHERE p.email = CONCAT('contacto.demo.', LPAD(n.n, 4, '0'), '@example.com')
);

-- Identifica apenas os 1.000 emails usados neste ficheiro, sem assumir IDs fixos.
INSERT INTO tmp_lynx_demo_contactos (n, publico_id)
SELECT n.n, p.publico_id
FROM tmp_lynx_demo_numeros n
JOIN publico p ON p.email = CONCAT('contacto.demo.', LPAD(n.n, 4, '0'), '@example.com');

-- Um contacto pode corresponder a varios segmentos.
-- Estas associacoes ficam guardadas; as regras automaticas sao configuradas no fim.
INSERT INTO publico_segmentos (publico_id, segmento_id)
SELECT p.publico_id, s.segmento_id
FROM tmp_lynx_demo_contactos d
JOIN publico p ON p.publico_id = d.publico_id
JOIN listas l ON l.lista_id = p.lista_id
JOIN canal c ON c.canal_id = p.canal_id
JOIN segmentos s ON
       (s.segmento_nome = '[DEMO] Novos contactos'
        AND p.data_registo >= DATE_SUB(NOW(), INTERVAL 30 DAY))
    OR (s.segmento_nome = '[DEMO] Clientes' AND l.lista_nome = '[DEMO] Clientes')
    OR (s.segmento_nome = '[DEMO] Leads' AND l.lista_nome = '[DEMO] Leads')
    OR (s.segmento_nome = '[DEMO] Newsletter'
        AND l.lista_nome IN ('[DEMO] Newsletter', '[DEMO] Formação', '[DEMO] Ofertas'))
    OR (s.segmento_nome = '[DEMO] Canal digital' AND c.nome = '[DEMO] Website')
    OR (s.segmento_nome = '[DEMO] Parceiros'
        AND (l.lista_nome = '[DEMO] Parceiros' OR c.nome = '[DEMO] Parceiros'))
WHERE NOT EXISTS (
    SELECT 1 FROM publico_segmentos ps
    WHERE ps.publico_id = p.publico_id AND ps.segmento_id = s.segmento_id
);

COMMIT;

-- Resumos devolvidos pelo phpMyAdmin no fim da importacao.
SELECT COUNT(*) AS contactos_demo
FROM tmp_lynx_demo_contactos;

SELECT l.lista_nome, COUNT(p.publico_id) AS contactos_demo
FROM tmp_lynx_demo_listas dl
JOIN listas l ON l.lista_nome = dl.nome
LEFT JOIN publico p ON p.lista_id = l.lista_id
    AND p.publico_id IN (SELECT publico_id FROM tmp_lynx_demo_contactos)
GROUP BY l.lista_id, l.lista_nome ORDER BY l.lista_nome;

SELECT s.segmento_nome, COUNT(ps.publico_id) AS contactos_demo
FROM segmentos s
LEFT JOIN publico_segmentos ps ON ps.segmento_id = s.segmento_id
    AND ps.publico_id IN (SELECT publico_id FROM tmp_lynx_demo_contactos)
WHERE s.segmento_nome IN (
    '[DEMO] Novos contactos', '[DEMO] Clientes', '[DEMO] Leads',
    '[DEMO] Newsletter', '[DEMO] Canal digital', '[DEMO] Parceiros'
)
GROUP BY s.segmento_id, s.segmento_nome ORDER BY s.segmento_nome;

DROP TEMPORARY TABLE tmp_lynx_demo_contactos;
DROP TEMPORARY TABLE tmp_lynx_demo_gestores;
DROP TEMPORARY TABLE tmp_lynx_demo_listas;
DROP TEMPORARY TABLE tmp_lynx_demo_numeros;


-- Ativa regras para os seis segmentos de exemplo existentes.
-- Reexecutavel: nao elimina contactos nem associacoes manuais.
-- As regras do mesmo segmento combinam-se com OU.
-- Segmentos sem regras continuam a usar publico_segmentos.
SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS segmento_regras (
    regra_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    segmento_id INT UNSIGNED NOT NULL,
    tipo ENUM('lista', 'canal', 'dias_registo') NOT NULL,
    valor INT UNSIGNED NOT NULL,
    PRIMARY KEY (regra_id),
    UNIQUE KEY uq_segmento_regra (segmento_id, tipo, valor),
    CONSTRAINT fk_segmento_regras_segmento FOREIGN KEY (segmento_id)
        REFERENCES segmentos (segmento_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

START TRANSACTION;

INSERT INTO segmento_regras (segmento_id, tipo, valor)
SELECT s.segmento_id, 'lista', l.lista_id
FROM segmentos s JOIN listas l ON
       (s.segmento_nome = '[DEMO] Clientes' AND l.lista_nome = '[DEMO] Clientes')
    OR (s.segmento_nome = '[DEMO] Leads' AND l.lista_nome = '[DEMO] Leads')
    OR (s.segmento_nome = '[DEMO] Newsletter'
        AND l.lista_nome IN ('[DEMO] Newsletter', '[DEMO] Formação', '[DEMO] Ofertas'))
    OR (s.segmento_nome = '[DEMO] Parceiros' AND l.lista_nome = '[DEMO] Parceiros')
WHERE NOT EXISTS (
    SELECT 1 FROM segmento_regras r
    WHERE r.segmento_id = s.segmento_id AND r.tipo = 'lista' AND r.valor = l.lista_id
);

INSERT INTO segmento_regras (segmento_id, tipo, valor)
SELECT s.segmento_id, 'canal', c.canal_id
FROM segmentos s JOIN canal c ON
       (s.segmento_nome = '[DEMO] Canal digital' AND c.nome = '[DEMO] Website')
    OR (s.segmento_nome = '[DEMO] Parceiros' AND c.nome = '[DEMO] Parceiros')
WHERE NOT EXISTS (
    SELECT 1 FROM segmento_regras r
    WHERE r.segmento_id = s.segmento_id AND r.tipo = 'canal' AND r.valor = c.canal_id
);

INSERT INTO segmento_regras (segmento_id, tipo, valor)
SELECT s.segmento_id, 'dias_registo', 30 FROM segmentos s
WHERE s.segmento_nome = '[DEMO] Novos contactos'
AND NOT EXISTS (
    SELECT 1 FROM segmento_regras r
    WHERE r.segmento_id = s.segmento_id AND r.tipo = 'dias_registo' AND r.valor = 30
);

UPDATE segmentos SET descricao = 'Contactos registados nos últimos 30 dias. Atualizado automaticamente.'
WHERE segmento_nome = '[DEMO] Novos contactos';

COMMIT;
