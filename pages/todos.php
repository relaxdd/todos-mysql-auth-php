<?php

use Awenn2015\TestDemoTodos\Models\TodoModel;

global $mysql_pdo;
global $jwt_payload;

$user_id = $jwt_payload['user_id'] ?? 0;
$todo_model = new TodoModel($mysql_pdo);
$todos = $todo_model->loadAll($user_id);

$form_errors = [
  'c3q1' => 'Missing title for a new todo record',
  'x1n4' => 'Failed to create a new todo record',
];

$success = $_GET['success'] ?? null;
$field = $_GET['field'] ?? null;
$error = $_GET['error'] ?? null;
$text_errors = $form_errors[$error] ?? 'Unknown field error';
?>
<!DOCTYPE html>
<html lang="<?= get_options('lang') ?>">
<head>
  <?php require_template('html-head') ?>

  <link rel='stylesheet' href='/assets/css/form.css'>
  <link rel='stylesheet' href='/assets/css/todos.css'>
</head>

<body>
<?php require_template('header') ?>

<main>
  <div class="container">
    <form method="POST" action="/api/v1/todos">
      <input type="hidden" name="return-type" value="html">

      <div class="form_add">
        <div class="form_add__shell">
          <input type="text" class="form-control" name="title" required="">
          <input type="submit" value="ADD TODO" class="btn-primary">
        </div>

        <?php if ($error && $field === 'title') : ?>
          <small class="form-error">
            <?= $text_errors ?>
            <input type="button" value="x" onclick="window.location.replace('/')">
          </small>
        <?php endif; ?>


        <?php if ($success) : ?>
          <small class="form-success">
            A new todo entry has been successfully added
            <input type="button" value="x" onclick="window.location.replace('/')">
          </small>
        <?php endif; ?>
      </div>
    </form>

    <ul class="todo_list">
      <?php foreach ($todos as $todo) : ?>
        <li>
          <label
            class="todo_list__item"
            data-completed="<?= +$todo['completed'] ?>"
            data-id="<?= $todo['id'] ?>"
          >
            <input
              type="checkbox"
              class="todo_list__item__checkbox"
              <?= $todo['completed'] ? 'checked=""' : '' ?>
            >

            <span class="todo_list__item__title"><?= $todo['title'] ?></span>
          </label>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</main>

<script>
  (function() {
    /**
     * @param {object} payload
     * @returns {Promise<void>}
     */
    async function updateTodoValue(payload) {
      const resp = await fetch('/api/v1/todos', {
        method: 'PATCH',
        body: JSON.stringify(payload),
        headers: { 'Content-Type': 'application/json' }
      })

      const text = await resp.text()

      const result = ((json) => {
        try {
          return {
            status: true,
            payload: JSON.parse(json)
          }
        } catch (e) {
          return {
            status: false,
            error: json
          }
        }
      })(text)

      if (!result.status) {
        throw new Error(result.error)
      }

      return result.payload
    }

    function onChangeCheckboxHandler(e) {
      const label = e.target.closest('label')
      if (!label) return

      const id = +(label.dataset?.['id'] || '0')
      const completed = +(label.dataset?.['completed'] || '0')

      if (!id) return

      updateTodoValue({ id, completed })
        .then(() => {
          window.location.reload()
        })
        .catch((err) => {
          alert(err?.message || 'The record value could not be updated')
        })
    }

    const checkboxes = document.querySelectorAll('.todo_list li label input[type="checkbox"]')

    for (const checkbox of checkboxes) {
      checkbox.addEventListener('change', onChangeCheckboxHandler)
    }
  })();
</script>
</body>
</html>