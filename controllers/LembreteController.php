<?php

class LembreteController
{
    private Lembrete $model;

    public function __construct(Lembrete $model)
    {
        $this->model = $model;
    }

    public function listar(): void
    {
        echo json_encode($this->model->listar(), JSON_UNESCAPED_UNICODE);
    }

    public function buscar(int $id): void
    {
        $lembrete = $this->model->buscarPorId($id);

        if (!$lembrete) {
            $this->erro(404, 'Lembrete não encontrado');
            return;
        }

        echo json_encode($lembrete, JSON_UNESCAPED_UNICODE);
    }

    public function criar(): void
    {
        $dados = $this->lerCorpo();

        if ($erro = $this->validar($dados)) {
            $this->erro(400, $erro);
            return;
        }

        $id = $this->model->criar($dados);

        http_response_code(201);
        echo json_encode([
            'mensagem' => 'Lembrete criado com sucesso',
            'LembreteId' => $id
        ], JSON_UNESCAPED_UNICODE);
    }

    public function atualizar(int $id): void
    {
        $existente = $this->model->buscarPorId($id);

        if (!$existente) {
            $this->erro(404, 'Lembrete não encontrado');
            return;
        }

        // Campos não enviados mantêm o valor atual
        $dados = array_merge($existente, $this->lerCorpo());

        if ($erro = $this->validar($dados)) {
            $this->erro(400, $erro);
            return;
        }

        $this->model->atualizar($id, $dados);

        echo json_encode(['mensagem' => 'Lembrete atualizado com sucesso'], JSON_UNESCAPED_UNICODE);
    }

    public function excluir(int $id): void
    {
        if (!$this->model->buscarPorId($id)) {
            $this->erro(404, 'Lembrete não encontrado');
            return;
        }

        $this->model->excluir($id);

        echo json_encode(['mensagem' => 'Lembrete removido com sucesso'], JSON_UNESCAPED_UNICODE);
    }

    private function lerCorpo(): array
    {
        $dados = json_decode(file_get_contents('php://input'), true);
        return is_array($dados) ? $dados : [];
    }

    private function validar(array $dados): ?string
    {
        if (empty($dados['TituloLembrete'])) {
            return 'O título é obrigatório';
        }

        if (!isset($dados['DescricaoLembrete'])) {
            return 'A nota é obrigatória';
        }

        if (!empty($dados['DataLembrete']) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dados['DataLembrete'])) {
            return 'Data inválida (use AAAA-MM-DD)';
        }

        if (!empty($dados['HorarioLembrete']) && !preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $dados['HorarioLembrete'])) {
            return 'Horário inválido (use HH:MM)';
        }

        return null;
    }

    private function erro(int $codigo, string $mensagem): void
    {
        http_response_code($codigo);
        echo json_encode(['erro' => $mensagem], JSON_UNESCAPED_UNICODE);
    }
}
