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
