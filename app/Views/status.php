<?php $this->layout('../view_layout',[
  'id' => $_GET['id']
]); ?>

<?php $id = $_GET['id']; ?>

    <main id="js-page-content" role="main" class="page-content mt-3">
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-sun'></i> Установить статус
            </h1>

        </div>

      <?php foreach ($posts as $v): ?>

      <?php $user_id = $v['id'] ?>

        <?php
        switch ($v['status_user']) {
          case 0:
            $v['status_user'] = "Не беспокоить";
            break;
          case 1:
            $v['status_user'] = "Онлайн";
            break;
          case 2:
            $v['status_user'] = "Отошёл";
            break;
        }
        ?>

        <form method="post" action=<?php echo "/status_func?id=$user_id"; ?>>
            <div class="row">
                <div class="col-xl-6">
                    <div id="panel-1" class="panel">
                        <div class="panel-container">
                            <div class="panel-hdr">
                                <h2>Установка текущего статуса</h2>
                            </div>
                            <div class="panel-content">
                                <div class="row">
                                    <div class="col-md-4">
                                        <!-- status -->
                                        <div class="form-group">
                                            <label class="form-label" for="example-select">Выберите статус</label>
                                            <select class="form-control" id="example-select" name="option">

                                                <option value="1" <?php if ($v['status_user'] == "Онлайн") echo 'selected'; ?> >Онлайн</option>
                                                <option value="2" <?php if ($v['status_user'] == "Отошёл") echo 'selected'; ?> >Отошел</option>
                                                <option value="0" <?php if ($v['status_user'] == "Не беспокоить") echo 'selected'; ?> >Не беспокоить</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                                        <button class="btn btn-warning">Set Status</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </form>

      <?php endforeach; ?>

    </main>

    <script src="js/vendors.bundle.js"></script>
    <script src="js/app.bundle.js"></script>
    <script>

        $(document).ready(function()
        {

            $('input[type=radio][name=contactview]').change(function()
                {
                    if (this.value == 'grid')
                    {
                        $('#js-contacts .card').removeClassPrefix('mb-').addClass('mb-g');
                        $('#js-contacts .col-xl-12').removeClassPrefix('col-xl-').addClass('col-xl-4');
                        $('#js-contacts .js-expand-btn').addClass('d-none');
                        $('#js-contacts .card-body + .card-body').addClass('show');

                    }
                    else if (this.value == 'table')
                    {
                        $('#js-contacts .card').removeClassPrefix('mb-').addClass('mb-1');
                        $('#js-contacts .col-xl-4').removeClassPrefix('col-xl-').addClass('col-xl-12');
                        $('#js-contacts .js-expand-btn').removeClass('d-none');
                        $('#js-contacts .card-body + .card-body').removeClass('show');
                    }

                });

                //initialize filter
                initApp.listFilter($('#js-contacts'), $('#js-filter-contacts'));
        });

    </script>
</body>
</html>