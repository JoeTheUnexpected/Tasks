<?php $this->title = 'Список задач';?>

<h1><?= $this->title ?></h1>

<div class="position-relative">
    <table class="table table-hover" id="tasks_table">
        <thead id="sort">
        <tr>
            <th scope="col" data-sort-by="id">#</th>
            <th scope="col" data-sort-by="user_name">Имя пользователя</th>
            <th scope="col" data-sort-by="user_email">Email</th>
            <th scope="col" data-sort-by="text">Текст</th>
            <th scope="col" data-sort-by="completed">Завершена</th>
        </tr>
        </thead>
        <tbody id="show_task">

        <?php foreach ($tasks as $task): ?>
            <tr>
                <td><?= $task->id ?></td>
                <td><?= $task->user_name ?></td>
                <td><?= $task->user_email ?></td>
                <td><?= $task->text ?></td>
                <td <?= $task->completed ? 'class="completed position-relative"' : '' ?>></td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>

    <div class="d-flex justify-content-center" id="pagination">
        <?php \App\Core\Component::include('pagination', ['linksCount' => $linksCount]) ?>
    </div>

    <div class="d-flex justify-content-center align-items-center position-absolute top-0 w-100 h-100 bg-secondary opacity-0 invisible" id="overlay">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

<script>
  let sortBy
  let sortOrder = 'asc'

  document.getElementById('pagination').addEventListener('click', (e) => {
    e.preventDefault()
    if (e.target.innerText !== (new URL(window.location.href)).searchParams.get('page')) {
      fetchData('page', e.target.innerText)
      Array.from(document.getElementById('pagination').getElementsByTagName('li')).forEach(item => item.classList.remove('active'))
      e.target.closest('li').classList.add('active')
    }
  })

  document.getElementById('sort').addEventListener('click', (e) => {
    let dataSortBy = e.target.closest('[data-sort-by]').dataset.sortBy
    if (dataSortBy) {
      if (sortBy === dataSortBy) {
        sortOrder = sortOrder === 'asc' ? 'desc' : 'asc'
      } else {
        sortOrder = 'asc'
      }
      sortBy = dataSortBy

      fetchData(['sort_by', 'sort_order'], [sortBy, sortOrder])
    }
  })

  document.getElementById('show_task').addEventListener('click', (e) => {
    let taskId = e.target.closest('tr').firstElementChild.textContent
    window.location.href = `/tasks/${taskId}`
  })

  function fetchData(param, paramVal) {
    let url = window.location.href
    if (Array.isArray(param)) {
      param.forEach((v, k) => url = updateURLParameter(url, v, paramVal[k]))
    } else {
      url = updateURLParameter(url, param , paramVal)
    }

    let overlay = document.getElementById('overlay')
    overlay.classList.remove('invisible', 'opacity-0')
    overlay.classList.add('opacity-50')
    fetch(url, {
      method: 'get',
      headers: {
        'Accept': 'application/json'
      }
    })
      .then((response) => {
        let tableBody = ''
        response.json()
          .then(data => data.forEach(item => tableBody += `<tr><td>${item.id}</td><td>${item.user_name}</td><td>${item.user_email}</td><td>${item.text}</td><td ${item.completed ? 'class="completed position-relative"' : ''}></td></tr>`))
          .then(() => document.getElementById('tasks_table').getElementsByTagName('tbody')[0].innerHTML = tableBody)
          .then(() => {
            overlay.classList.remove('opacity-50')
            overlay.classList.add('invisible', 'opacity-0')
            history.pushState('', '', url)
          })
      })
  }

  function updateURLParameter (url, param, paramVal) {
    let newAdditionalURL = '';
    let tempArray = url.split('?');
    let baseURL = tempArray[0];
    let additionalURL = tempArray[1];
    let temp = '';
    if (additionalURL) {
      tempArray = additionalURL.split('&');
      for (let i = 0; i < tempArray.length; i++) {
        if (tempArray[i].split('=')[0] != param) {
          newAdditionalURL += temp + tempArray[i];
          temp = '&';
        }
      }
    }

    let updated = temp + param + '=' + paramVal;
    return baseURL + '?' + newAdditionalURL + updated;
  }
</script>

<style>
    th:hover {
        background-color: var(--bs-table-hover-bg);
    }
    tr:hover {
        cursor: pointer;
    }
    .completed:after {
        content: '\2705';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    #overlay {
        transition: visibility 0.25s, opacity 0.25s linear;
        z-index: 100;
    }
</style>
