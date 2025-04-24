### Esse arquivo é apenas para anotações relacionadas ao desenvolvimento.

- [x] Autenticação
- [x] Gerenciamento de Tarefas
    - [x] CRUD completo de tarefas (criar, listar, editar, excluir).
    - [x] Cada tarefa deve ter: título, descrição, status (pendente, em andamento, concluído),
        prioridade (baixa, média, alta), data de vencimento e usuário responsável.
    - [x] Apenas o usuário responsável pela tarefa ou um admin pode editar/excluir a tarefa.
    - [x] Atribuição de tags as tarefas
        - [x] O sistema permitirá que o usuário atribua tags com valores personalizados as tarefas
        - [x] Tags poderão ser reaproveitadas para atribuição a mais de uma tarefa
        - [x] Apenas usuários com permissão para editar a tarefa poderão adicionar/remover tags
        - [x] Uma tarefa poderá estar vinculada a múltiplas tags, e uma tag poderá estar
            vinculada a múltiplas tarefas

- [x] Atribuição de Tarefas
    - [x] Um usuário pode atribuir uma tarefa a outro.
    - [x] Ao atribuir uma tarefa, o sistema deve:
        - [x] Enviar um e-mail notificando o usuário responsável.
        - [x] Utilizar uma fila de jobs para envio assíncrono.

- [x] Notificações de Vencimento
    - [x] Se uma tarefa estiver com vencimento em 2 dias, o sistema deve:
        - [x] Enviar uma notificação por e-mail ao usuário responsável.
        - [x] Essa verificação deve rodar via comando agendado (schedule) + job em
            background.

- [x] Filtros e Ordenação
    - [x] A API deve permitir listar tarefas com filtros:
        - [x] Por status, prioridade, data de vencimento, usuário e tags.
    - [x] Permitir ordenação por prioridade e data de vencimento.

- [ ] Testes automatizados básicos.
- [x] Docker para facilitar setup local.
- [x] Utilização de Redis como serviço de fila.