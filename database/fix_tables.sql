-- Correção das tabelas servicos e checklists
USE guincho_db;

-- Remover tabelas se existirem (apenas para correção)
DROP TABLE IF EXISTS checklists;
DROP TABLE IF EXISTS servicos;

-- Tabela de serviços corrigida
CREATE TABLE IF NOT EXISTS servicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    veiculo_id INT NOT NULL,
    usuario_id INT NULL,
    tipo_servico VARCHAR(100) NOT NULL,
    data_servico DATE NOT NULL,
    descricao TEXT,
    valor DECIMAL(10,2),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (veiculo_id) REFERENCES veiculos(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);

-- Tabela de checklists corrigida
CREATE TABLE IF NOT EXISTS checklists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    servico_id INT NOT NULL,
    itens TEXT NOT NULL,
    observacoes TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (servico_id) REFERENCES servicos(id) ON DELETE CASCADE
);
