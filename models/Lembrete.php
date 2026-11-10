
<?php

class Lembrete
{
    private PDO $conn;
    private string $table = 'lembretes';

    // Campos existentes no banco MariaDB
    private string $campos = "
        LembreteId,
        TituloLembrete,
        DescricaoLembrete,
        DataLembrete,
        HorarioLembrete,
        DiarioLembrete,
        StatusLembrete
    ";

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    // Listar todos os lembretes
    public function listar(): array
    {
        $sql = "SELECT {$this->campos}
                FROM {$this->table}
                ORDER BY LembreteId DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Buscar lembrete pelo ID
    public function buscarPorId(int $id): ?array
    {
        $sql = "SELECT {$this->campos}
                FROM {$this->table}
                WHERE LembreteId = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $dados = $stmt->fetch();

        return $dados ?: null;
    }

    // Cadastrar lembrete
    public function criar(array $dados): int
    {
        $sql = "INSERT INTO {$this->table}
        (
            TituloLembrete,
            DescricaoLembrete,
            DataLembrete,
            HorarioLembrete,
            DiarioLembrete,
            StatusLembrete
        )
        VALUES
        (
            :titulo,
            :descricao,
            :data,
            :horario,
            :diario,
            :status
        )";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($this->parametros($dados));

        return (int) $this->conn->lastInsertId();
    }

    // Atualizar lembrete
    public function atualizar(int $id, array $dados): bool
    {
        $sql = "UPDATE {$this->table} SET
            TituloLembrete = :titulo,
            DescricaoLembrete = :descricao,
            DataLembrete = :data,
            HorarioLembrete = :horario,
            DiarioLembrete = :diario,
            StatusLembrete = :status
            WHERE LembreteId = :id";

        $stmt = $this->conn->prepare($sql);

        $parametros = $this->parametros($dados);
        $parametros[':id'] = $id;

        return $stmt->execute($parametros);
    }

    // Excluir lembrete
    public function excluir(int $id): bool
    {
        $sql = "DELETE FROM {$this->table}
                WHERE LembreteId = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    // Organizar os parâmetros recebidos pela API
    private function parametros(array $dados): array
    {
        return [
            ':titulo' => $dados['TituloLembrete'] ?? '',
            
            ':descricao' => $dados['DescricaoLembrete']
                ?? $dados['Nota']
                ?? '',

            ':data' => !empty($dados['DataLembrete'])
                ? $dados['DataLembrete']
                : null,

            ':horario' => !empty($dados['HorarioLembrete'])
                ? $dados['HorarioLembrete']
                : null,

            ':diario' => !empty($dados['DiarioLembrete']) ? 1 : 0,

            ':status' => !empty($dados['StatusLembrete']) ? 1 : 0
        ];
    }
}
