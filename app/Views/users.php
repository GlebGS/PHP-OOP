<?php $this->layout('../view_layout',[
        'id' => $_GET['id'],
        'role' => $getRole
]); ?>

<?php $id = $_GET['id']; ?>

<main id="js-page-content" role="main" class="page-content mt-3">

    <?php echo flash()->display(); ?>

    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-users'></i> Список пользователей
        </h1>
    </div>
    <div class="row">
        <div class="col-xl-12">

            <?php if ($getRole[1] === "ADMIN"): ?>
                <a class="btn btn-success" href=<?php echo "/pageCreate?id=$id"; ?>>Добавить</a>
            <?php else: ?>

            <?php endif; ?>


            <div class="border-faded bg-faded p-3 mb-g d-flex mt-3">
                <input type="text" id="js-filter-contacts" name="filter-contacts"
                       class="form-control shadow-inset-2 form-control-lg" placeholder="Найти пользователя">
                <div class="btn-group btn-group-lg btn-group-toggle hidden-lg-down ml-3" data-toggle="buttons">
                    <label class="btn btn-default active">
                        <input type="radio" name="contactview" id="grid" checked="" value="grid"><i
                                class="fas fa-table"></i>
                    </label>
                    <label class="btn btn-default">
                        <input type="radio" name="contactview" id="table" value="table"><i class="fas fa-th-list"></i>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="js-contacts">

      <?php foreach ($posts as $v): ?>

        <?php
            switch ($v['status_user']) {
              case 0:
                $v['status_user'] = "warning";
                break;
              case 1:
                $v['status_user'] = "success";
                break;
              case 2:
                $v['status_user'] = "danger";
                break;
            }
        ?>

          <div class="col-xl-4">
              <div id="c_<?php echo $v['id']; ?>" class="card border shadow-0 mb-g shadow-sm-hover" data-filter-tags="<?php echo $v['username']; ?>">
                  <div class="card-body border-faded border-top-0 border-left-0 border-right-0 rounded-top">
                      <div class="d-flex flex-row align-items-center">
                                <span class="status status-<?php echo $v['status_user']; ?> mr-3">
                                    <span class="rounded-circle profile-image d-block "
                                          style="background-image:url(<?php echo $v['img']; ?>); background-size: cover;"></span>
                                </span>
                          <div class="info-card-text flex-1">

                              <?php if ($getRole[1] === "ADMIN" OR $v['id'] === $id): ?>
                                  <a href="javascript:void(0);" class="fs-xl text-truncate text-truncate-lg text-info" data-toggle="dropdown" aria-expanded="false">
                                      <?php echo $v['username']; ?>
                                      <i class="fal fas fa-cog fa-fw d-inline-block ml-1 fs-md"></i>
                                      <i class="fal fa-angle-down d-inline-block ml-1 fs-md"></i>
                                  </a>
                              <?php else: ?>
                                  <p>
                                    <?php echo $v['username']; ?>
                                  </p>
                              <?php endif; ?>

                            <?php if ($getRole[1] === "ADMIN" OR $v['id'] === $id): ?>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="/edit?id=<?php echo $v['id']; ?>">
                                        <i class="fa fa-edit"></i>
                                        Редактировать</a>
                                    <a class="dropdown-item" href="/security?id=<?php echo $v['id']; ?>">
                                        <i class="fa fa-lock"></i>
                                        Безопасность</a>
                                    <a class="dropdown-item" href="/status?id=<?php echo $v['id']; ?>">
                                        <i class="fa fa-sun"></i>
                                        Установить статус</a>
                                    <a class="dropdown-item" href="/medias?id=<?php echo $v['id']; ?>">
                                        <i class="fa fa-camera"></i>
                                        Загрузить аватар
                                    </a>
                                    <a href="#" class="dropdown-item" onclick="return confirm('are you sure?');">
                                        <i class="fa fa-window-close"></i>
                                        Удалить
                                    </a>
                                </div>
                            <?php else: ?>

                            <?php endif; ?>

                              <span class="text-truncate text-truncate-xl">
                                <?php echo $v['position']; ?>
                              </span>
                          </div>
                          <button class="js-expand-btn btn btn-sm btn-default d-none" data-toggle="collapse"
                                  data-target="#c_<?php echo $v['id']; ?> > .card-body + .card-body" aria-expanded="false">
                              <span class="collapsed-hidden">+</span>
                              <span class="collapsed-reveal">-</span>
                          </button>
                      </div>
                  </div>

                    <div class="card-body p-0 collapse show">
                        <div class="p-3">
                            <a href="tel:<?php echo $v['phone']; ?>" class="mt-1 d-block fs-sm fw-400 text-dark">
                                <i class="fas fa-mobile-alt text-muted mr-2"></i>
                              <?php echo $v['phone']; ?>
                            </a>
                            <a href="mailto:<?php echo $v['email']; ?>"
                               class="mt-1 d-block fs-sm fw-400 text-dark">
                                <i class="fas fa-mouse-pointer text-muted mr-2"></i>
                              <?php echo $v['email']; ?>
                            </a>
                            <address class="fs-sm fw-400 mt-4 text-muted">
                                <i class="fas fa-map-pin mr-2"></i>
                              <?php echo $v['address']; ?>
                            </address>
                        </div>
                    </div>

              </div>
          </div>
      <?php endforeach; ?>


    </div>
</main>
     

