<?php
require_once 'backend/sdbh.php';
$dbh = new sdbh();

?>
<html>
    <head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="assets/css/style.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"  crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="assets/js/scripts.js" type="text/javascript" > </script>
    </head>
    <body>
        <div class="container">
            <div class="row row-header">
                <div class="col-12">
                    <img src="assets/img/logo.png" alt="logo" style="max-height:50px"/>
                    <h1>Прокат</h1>
                </div>
            </div>

            <!-- TODO: реализовать форму расчета -->

                <div class="row row-body">
                    <div class="col-3">
                        <span style="text-align: center">Форма Расчета</span>
                        <i class="bi bi-activity"></i>
                    </div>
                    <div class="col-9">
                        <form id="form" method = "post">
                                <label class="form-label" for="product">Выберите продукт:</label>
                                <select class="form-select" name="product" id="product">
                                <?php
                                    $services = ($dbh->mselect_rows('a25_products', NULL, 0, 3, 'id'));
                                    foreach($services as $k => $s) { ?>
                                        <option value="<?php echo $s['ID']?>"><?php echo $s['NAME']." за ".$s['PRICE'];?></option>
                                    <?php }
                                ?>
                                </select>

                                <label for="customRange1" class="form-label">Количество дней:</label>
                                <input type="text" class="form-control" id="customRange1" min="1" max="30">

                                <label for="customRange1" class="form-label">Дополнительно:</label>

                                <?php
                                    $services = unserialize($dbh->mselect_rows('a25_settings', ['set_key' => 'services'], 0, 3, 'id')[0]['set_value']);
                                    $i = 1;
                                    foreach($services as $k => $s) { ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="<?php echo $s?>" id="flexCheckChecked<?php echo $i;?>" >
                                        <label class="form-check-label" for="flexCheckChecked<?php echo $i;?>">
                                            <?php echo $k." за ".$s;?>
                                        </label>
                                    </div>
                                    <?php 
                                        $i++;
                                    }
                                ?>
                                <button type="submit" class="btn btn-primary">Рассчитать</button>
                        </form>
                        <div class = "calulation-result"></div>
                    </div>
                </div>
        </div>
    </body>
</html>