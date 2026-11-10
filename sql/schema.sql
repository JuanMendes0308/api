-- Banco: lembretesdb
-- Se a tabela JÁ existe, rode apenas o bloco ALTER TABLE abaixo.

CREATE TABLE IF NOT EXISTS lembretes (
    LembreteId      INT AUTO_INCREMENT PRIMARY KEY,
    TituloLembrete  VARCHAR(100) NOT NULL,
    CorpoLembrete   TEXT NOT NULL,
    DataLembrete    DATE NULL,
    HorarioLembrete TIME NULL,
    DiarioLembrete  TINYINT(1) NOT NULL DEFAULT 0,
    StatusLembrete  TINYINT(1) NOT NULL DEFAULT 0
) CHARACTER SET utf8mb4;

-- Para tabela já existente (Data, Horário e Diário vindos do layout do app):
-- ALTER TABLE lembretes
--     ADD COLUMN DataLembrete    DATE NULL,
--     ADD COLUMN HorarioLembrete TIME NULL,
--     ADD COLUMN DiarioLembrete  TINYINT(1) NOT NULL DEFAULT 0;
