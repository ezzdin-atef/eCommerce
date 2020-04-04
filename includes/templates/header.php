<!DOCTYPE html>
    <html>
    <head>
        <meta charset='utf-8'>
        <title><?php getTitle(); ?></title>
        <link rel="stylesheet" href="themes/Default/css/bootstrap.min.css">
        <link rel="stylesheet" href="themes/Default/css/all.css">
        <link rel="stylesheet" href="themes/Default/css/style.css">
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
          <div class="container">
            <a class="navbar-brand" href="index.php">Homepage</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse " id="navbarSupportedContent">
              <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                  <a class="nav-link" href="index.php"><?php echo lang('HOME_PAGE') ?></a>
                </li>
                <?php

                  foreach (getCats() as $cat) {
                    echo '<li class="nav-item">
                          <a class="nav-link" href="categories.php?catid='.$cat['ID'].'&catname='.str_replace(' ', '-', $cat['Name']).'">';
                      echo $cat['Name'];
                    echo '</a></li>';
                  }
                  if (isset($_SESSION['Username'])) {
                    echo '<li class="nav-item">';
                      echo '<a class="nav-link" href="signin.php">SIGN IN/ SIGN UP</a>';
                    echo '</li>';
                  }



                ?>
              </ul>
            </div>
          </div>
        </nav>
    	