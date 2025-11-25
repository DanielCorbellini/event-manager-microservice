# 📘 events-manager-service — Documentação da API

## 📝 Resumo

-   Serviço responsável por **gerenciar eventos, inscrições e check-ins**.
-   **Base paths principais:**
    -   `/eventos`
    -   `/inscricoes`
    -   `/checkins`
-   **Autenticação:** Todas as rotas exigem: JWT
    Header: `Authorization: Bearer <token>`
    Middleware: `app/Http/Middleware/JwtMiddleware.php`

---

# 🎉 Endpoints — Eventos

## 🔍 Listar eventos

**GET** `/eventos`

### ✔️ 200 OK — Exemplo de resposta

```json
{
    "success": true,
    "eventos": [
        {
            "id_evento": 5,
            "titulo": "Nome do Evento",
            "data_inicio": "2025-10-01T09:00:00",
            "data_fim": "2025-10-01T18:00:00",
            "local": "Local do Evento"
        }
    ]
}
```

-   ❌ 404 — Nenhum evento encontrado

## 📄 Obter detalhes de um evento

**GET** `/eventos/{id}`

### ✔️ 200 OK

```json
{
    "success": true,
    "eventos": [
        {
            "id_evento": 5,
            "titulo": "Nome do Evento",
            "data_inicio": "2025-10-01T09:00:00",
            "data_fim": "2025-10-01T18:00:00",
            "local": "Local do Evento"
        }
    ]
}
```

-   ❌ 404 — Evento não encontrado

-   ❌ 500 — Erro interno

## 📄 Obter inscrições ativas de um evento

**GET** `/eventos/{id}/inscricoes`

### ✔️ 200 OK

```json
{
    "success": true,
    "inscricoes": [
        {
            "id_inscricao": 1,
            "id_usuario": 1,
            "id_evento": 1,
            "data_inscricao": "2025-11-18 02:19:18",
            "data_cancelamento": "2025-11-18 03:30:03",
            "status": true,
            "user": {
                "id_usuario": 1,
                "is_admin": true,
                "nome": "Administrador",
                "email": "admin@gmail.com",
                "cpf": "00000000000",
                "telefone": "000000000",
                "created_at": "2025-11-18 02:14:03.652543"
            },
            "event": {
                "id_evento": 1,
                "titulo": "teste",
                "data_inicio": "2025-11-03",
                "data_fim": "2025-11-03",
                "local": "prédio 11 - sala 3"
            },
            "checkin": null
        }
    ]
}
```

-   ❌ 404 — Nenhuma inscrição encontrada

---

## ➕ Criar evento

**POST** `/eventos`
Body: campos do evento (`titulo`, `data_inicio`, `data_fim`, `local`)

Exemplo de body

```json
{
    "titulo": "Internet das coisas",
    "data_inicio": "2025-11-30 23:00:00",
    "data_fim": "2025-11-30 23:59:59",
    "local": "Biblioteca - Auditório"
}
```

### Respostas

-   ✔️ **201** — Criado
-   ❌ **422** — Validação
-   ❌ **500** — Erro interno

---

## 🛠️ Atualizar evento

**PUT** `/eventos/{id}`
Possíveis campos no Body: (`titulo`, `data_inicio`, `data_fim`, `local`)

Exemplo de body

```json
{
    "data_fim": "2025-11-30 23:59:59"
}
```

### Respostas

-   ✔️ 200 — Atualizado
-   ❌ 404 — Não encontrado
-   ❌ 422 — Validação falhou
-   ❌ 500 — Erro interno

---

## 🗑️ Remover evento

**DELETE** `/eventos/{id}`

### Respostas

-   ✔️ 200 — Removido
-   ❌ 404 — Não encontrado
-   ❌ 500 — Erro interno

---

# 🧾 Endpoints — Inscrições (Subscriptions)

## 👤 Listar inscrições por usuário

**GET** `/inscricoes`
Query: `id_usuario`

### ✔️ 200 OK — Exemplo

```json
{
    "success": true,
    "inscricoes": [
        {
            "id_inscricao": 3,
            "id_usuario": 3,
            "id_evento": 1,
            "data_inscricao": "2025-11-18 03:28:21",
            "data_cancelamento": "2025-11-18 03:30:34",
            "status": false,
            "user": {
                "id_usuario": 3,
                "is_admin": false,
                "nome": "Lucca Heineck",
                "email": "lucca@gmail.com",
                "cpf": null,
                "telefone": null,
                "created_at": "2025-11-18 03:28:15.337466"
            },
            "event": {
                "id_evento": 1,
                "titulo": "teste",
                "data_inicio": "2025-11-03",
                "data_fim": "2025-11-03",
                "local": "prédio 11 - sala 3"
            },
            "checkin": null
        }
    ]
}
```

-   ❌ 404 — Nenhuma inscrição encontrada

---

## ➕ Criar inscrição

**POST** `/inscricoes`

### Body

```json
{
    "id_usuario": 10,
    "id_evento": 5
}
```

### Respostas

✔️ **201 Created**

```json
{
    "success": true,
    "message": "Inscrição criada com sucesso!",
    "data": {
        "id_usuario": 3,
        "id_evento": 2,
        "status": true,
        "data_inscricao": "2025-11-25T23:12:27.017703Z",
        "id_inscricao": 14,
        "user": {
            "id_usuario": 3,
            "is_admin": false,
            "nome": "Lucca Heineck",
            "email": "lucca@gmail.com",
            "cpf": null,
            "telefone": null,
            "created_at": "2025-11-18 03:28:15.337466"
        },
        "event": {
            "id_evento": 2,
            "titulo": "Congresso de IA e Machine Learning",
            "data_inicio": "2025-11-03",
            "data_fim": "2025-11-30",
            "local": "prédio 11 - sala 3"
        },
        "checkin": null
    }
}
```

-   ❌ **400** — Inscrição duplicada (`DuplicateSubscriptionException`)
-   ❌ **422** — Validação
-   ❌ **500** — Erro interno

---

## ❌ Cancelar / Remover inscrição (Não deleta do banco, apenas altera o campo status para false)

**PUT** `/inscricoes/{id}`

### Respostas

### ✔️ 200/204 — cancelada

```json
{
    "success": true,
    "message": "Inscrição cancelada com sucesso.",
    "data": {
        "id_inscricao": 14,
        "id_usuario": 3,
        "id_evento": 2,
        "data_inscricao": "2025-11-25 23:12:27",
        "data_cancelamento": "2025-11-25T23:13:40.326486Z",
        "status": false,
        "user": {
            "id_usuario": 3,
            "is_admin": false,
            "nome": "Lucca Heineck",
            "email": "lucca@gmail.com",
            "cpf": null,
            "telefone": null,
            "created_at": "2025-11-18 03:28:15.337466"
        },
        "event": {
            "id_evento": 2,
            "titulo": "Congresso de IA e Machine Learning",
            "data_inicio": "2025-11-03",
            "data_fim": "2025-11-30",
            "local": "prédio 11 - sala 3"
        },
        "checkin": null
    }
}
```

-   ❌ 404 — Inscrição não encontrada
-   ❌ 400 — Regra de negócio
-   ❌ 500 — Erro interno

---

# 🎫 Endpoints — Checkins

## 🔘 Registrar checkin

**POST** `/inscricoes/{id}/checkin`

### Respostas

### ✔️ 200/201 — checkin registrado

```json
{
    "success": true,
    "message": "Check-in realizado com sucesso!",
    "data": {
        "id_inscricao": 13,
        "data_checkin": "2025-11-25T23:15:28.601509Z",
        "id_checkin": 11,
        "subscription": {
            "id_inscricao": 13,
            "id_usuario": 3,
            "id_evento": 3,
            "data_inscricao": "2025-11-25 00:34:03",
            "data_cancelamento": null,
            "status": true,
            "user": {
                "id_usuario": 3,
                "is_admin": false,
                "nome": "Lucca Heineck",
                "email": "lucca@gmail.com",
                "cpf": null,
                "telefone": null,
                "created_at": "2025-11-18 03:28:15.337466"
            }
        }
    }
}
```

-   ❌ **400** — Erro ao registrar check-in
