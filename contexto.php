<?php
include ("conexion.php");

$id_usuario=$_SESSION["id"];
$datos_usuario=$_SESSION["nombre"];
$email=$_SESSION["email"];


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="generator" content="CoolAdmin 3.5.0"/>
    <meta name="description" content="Sales pipeline dashboard with deals funnel, top reps, and source attribution."/>
    <title>Aplicaciones Ransa</title>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="Sales pipeline | CoolAdmin Bootstrap 5 Admin Dashboard"/>
    <meta property="og:description" content="Sales pipeline dashboard with deals funnel, top reps, and source attribution."/>
    <meta property="og:image" content="screenshots/cooladmin-bootstrap-dashboard-2.png"/>
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:title" content="Sales pipeline | CoolAdmin Bootstrap 5 Admin Dashboard"/>
    <meta name="twitter:description" content="Sales pipeline dashboard with deals funnel, top reps, and source attribution."/>
    <meta name="theme-color" content="#66752a"/>
    <link href="css/font-face.css" rel="stylesheet" media="all"/>
    <link rel="preconnect" href="https://rsms.me/"/>
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css"/>
    <link href="vendor/fontawesome-7.3.1/css/all.min.css" rel="stylesheet" media="all"/>
    <link href="vendor/bootstrap-5.3.8.min.css" rel="stylesheet" media="all"/>
    <link href="vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all"/>
    <link href="css/theme.css" rel="stylesheet" media="all"/>
    <link href="css/app.css" rel="stylesheet" media="all"/>
	
	
	
  </head>
  <body class="app"><a class="visually-hidden-focusable skip-link" href="#main-content">Skip to main content</a>
    <div class="page-wrapper">
      <header class="header-mobile d-block d-lg-none">
        <div class="header-mobile__bar">
          <div class="container-fluid">
            <div class="header-mobile-inner"><a class="logo" href="contexto.php" aria-label="Ransa inicio"><img src="images/brand/ransa-mark.png" alt="Ransa"></a>
              <button class="hamburger hamburger--slider" type="button" aria-label="Toggle navigation"><span class="hamburger-box"><span class="hamburger-inner"></span></span></button>
            </div>
          </div>
        </div>
        <nav class="navbar-mobile">
          <div class="container-fluid">
            <ul class="navbar-mobile__list list-unstyled">
			
              <li class="has-sub"><a class="js-arrow" href="#"><i class="fa-solid fa-tachometer-alt"></i>Administración</a>
                <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                  <li><a href="index.html">Roles</a></li>
                  <li><a href="index2.html">Usuarios</a></li>
                  <li><a href="index3.html">Roles usuarios</a></li>
                 </ul>
              </li>
			  
			  <li class="has-sub"><a class="js-arrow" href="#"><i class="fa-solid fa-tachometer-alt"></i>Dotex</a>
                <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                  <li><a href="index.html">Colaboradores</a></li>
                  <li><a href="index2.html">Implementos</a></li>
                  <li><a href="index3.html">Asignación de dotación</a></li>
                 </ul>
              </li>
             
            </ul>
          </div>
        </nav>
      </header>
	  
      <aside class="menu-sidebar" id="main-sidebar">
        <div class="logo"><a class="logo-link" href="contexto.php" aria-label="Ransa inicio"><img class="logo-mark" src="images/brand/ransa-mark.png" alt="" aria-hidden="true"><span class="logo-text">Ransa</span></a>
          <button class="sidebar-close js-sidebar-toggle" type="button" aria-label="Close navigation"><i class="fa-solid fa-xmark" aria-hidden="true"></i></button>
        </div>
        <div class="menu-sidebar__content js-scrollbar1">
          <nav class="navbar-sidebar">
            <ul class="list-unstyled navbar__list">
			<?php
				$q="select r.id id, r.nombre nombre from rol r, rol_usuarios u where u.cod_rol=r.id and u.cod_usuario=$id_usuario ORDER BY 2";
				$eq=$dbmy->Execute($q);
				$fq=$eq->RecordCount();
				for ($i=0;$i<$fq;$i++){

					$cod_rol=$eq->fields['id'];
					$nombre_rol=$eq->fields['nombre'];
					?>
					<li class="active has-sub"><a class="js-arrow open" href="#"><i class="fa-solid fa-tachometer-alt"></i><?php echo htmlentities($nombre_rol);?></a>
					<ul class="list-unstyled navbar__sub-list js-sub-list ransa-open-submenu">
							
					<?php
					$q1="select a.id,a.nombre,a.url from aplicaciones a,rol_aplicaciones r where r.cod_aplicacion=a.id and r.cod_rol=$cod_rol ORDER BY 2";
					$eq1=$dbmy->Execute($q1);
					$fq1=$eq1->RecordCount();
					
					for ($j=0;$j<$fq1;$j++){

						$id_apli=$eq1->fields['id'];
						$nombre_apli=$eq1->fields['nombre'];
						$url_apli=$eq1->fields['url'];
						?>
						<li><a href="#" class="js-app-link ransa-app-link" data-app-url="<?php echo htmlspecialchars($url_apli, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($nombre_apli, ENT_QUOTES, 'UTF-8'); ?></a></li>
						<?php
						$eq1->MoveNext();
					}
				     
					?>
					 </ul>
					</li>					
					<?php
					$eq->MoveNext();
				}
				?>
			  
            </ul>
          </nav>
        </div>
      </aside>
      <div class="page-container">
        <header class="header-desktop">
          <div class="section__content section__content--p30">
            <div class="container-fluid">
              <div class="header-wrap">
                
                <div>
				<button class="sidebar-toggle js-sidebar-toggle" type="button" aria-label="Toggle navigation" aria-expanded="false" aria-controls="main-sidebar"><i class="fa-solid fa-bars" aria-hidden="true"></i></button>
                <form class="form-header ransa-search-form" role="search"><i class="fa-solid fa-magnifying-glass form-header__icon" aria-hidden="true"></i>
                  <input class="au-input au-input--xl" type="search" name="search" placeholder="Buscar aplicaciones…" aria-label="Buscar aplicaciones"><kbd class="form-header__hint" aria-hidden="true">Ctrl+K</kbd>
                </form>
				</div>
				
                <div class="header-button">
                  <div class="noti-wrap">
                    <div class="noti__item js-item-menu" role="button" tabindex="0" aria-haspopup="true" aria-label="Messages"><i class="fa-solid fa-comment-dots"></i><span class="quantity">1</span>
                      <div class="mess-dropdown js-dropdown">
                        <div class="mess__title">
                          <p>You have 2 news message</p>
                        </div>
                        <div class="mess__item">
                          <div class="image img-cir img-40"><img src="images/icon/avatar-06.jpg" alt="Michelle Moreno"></div>
                          <div class="content">
                            <h6>Michelle Moreno</h6>
                            <p>Have sent a photo</p><span class="time">3 min ago</span>
                          </div>
                        </div>
                        <div class="mess__item">
                          <div class="image img-cir img-40"><img src="images/icon/avatar-04.jpg" alt="Diane Myers"></div>
                          <div class="content">
                            <h6>Diane Myers</h6>
                            <p>You are now connected on message</p><span class="time">Yesterday</span>
                          </div>
                        </div>
                        <div class="mess__footer"><a href="#">View all messages</a></div>
                      </div>
                    </div>
                    <div class="noti__item js-item-menu" role="button" tabindex="0" aria-haspopup="true" aria-label="Emails"><i class="fa-solid fa-envelope"></i><span class="quantity">1</span>
                      <div class="email-dropdown js-dropdown">
                        <div class="email__title">
                          <p>You have 3 New Emails</p>
                        </div>
                        <div class="email__item">
                          <div class="image img-cir img-40"><img src="images/icon/avatar-06.jpg" alt="Cynthia Harvey"></div>
                          <div class="content">
                            <p>Meeting about new dashboard...</p><span>Cynthia Harvey, 3 min ago</span>
                          </div>
                        </div>
                        <div class="email__item">
                          <div class="image img-cir img-40"><img src="images/icon/avatar-05.jpg" alt="Cynthia Harvey"></div>
                          <div class="content">
                            <p>Meeting about new dashboard...</p><span>Cynthia Harvey, Yesterday</span>
                          </div>
                        </div>
                        <div class="email__item">
                          <div class="image img-cir img-40"><img src="images/icon/avatar-04.jpg" alt="Cynthia Harvey"></div>
                          <div class="content">
                            <p>Meeting about new dashboard...</p><span>Cynthia Harvey, January 15, 2025</span>
                          </div>
                        </div>
                        <div class="email__footer"><a href="#">See all emails</a></div>
                      </div>
                    </div>
                    <div class="noti__item js-item-menu" role="button" tabindex="0" aria-haspopup="true" aria-label="Notifications"><i class="fa-solid fa-bell"></i><span class="quantity">3</span>
                      <div class="notifi-dropdown js-dropdown">
                        <div class="notifi__title">
                          <p>You have 3 Notifications</p>
                        </div>
                        <div class="notifi__item">
                          <div class="bg-c1 img-cir img-40"><i class="fa-solid fa-envelope-open"></i></div>
                          <div class="content">
                            <p>You got a email notification</p><span class="date">January 15, 2025 14:30</span>
                          </div>
                        </div>
                        <div class="notifi__item">
                          <div class="bg-c2 img-cir img-40"><i class="fa-solid fa-id-card"></i></div>
                          <div class="content">
                            <p>Your account has been blocked</p><span class="date">January 15, 2025 14:30</span>
                          </div>
                        </div>
                        <div class="notifi__item">
                          <div class="bg-c3 img-cir img-40"><i class="fa-solid fa-file-lines"></i></div>
                          <div class="content">
                            <p>You got a new file</p><span class="date">January 15, 2025 14:30</span>
                          </div>
                        </div>
                        <div class="notifi__footer"><a href="#">All notifications</a></div>
                      </div>
                    </div>
                  </div>
                  <div class="account-wrap">
                    <div class="account-item clearfix js-item-menu" role="button" tabindex="0" aria-haspopup="true" aria-label="Account menu">
                      <div class="image"><img src="images/icon/avatar-01.jpg" alt="<?php echo $datos_usuario;?>"></div>
                      <div class="content"><a class="js-acc-btn" href="#"><?php echo $datos_usuario;?></a></div>
                      <div class="account-dropdown js-dropdown">
                        <div class="info clearfix">
                          <div class="image"><a href="#"><img src="images/icon/avatar-01.jpg" alt="John Doe"></a></div>
                          <div class="content">
                            <h5 class="name"><a href="#"><?php echo $datos_usuario;?></a></h5><span class="email"><?php echo $email;?></span>
                          </div>
                        </div>
                        <div class="account-dropdown__body">
                          <div class="account-dropdown__item"><a href="#"><i class="fa-solid fa-user"></i>Account</a></div>
                          <div class="account-dropdown__item"><a href="#"><i class="fa-solid fa-sack-dollar"></i>Billing</a></div>
                        </div>
                        <div class="account-dropdown__footer"><a href="salida.php"><i class="fa-solid fa-power-off"></i>Salir</a></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </header>
		
		
        <main class="main-content" id="main-content">
          <div class="section__content section__content--p30">
            <div class="container-fluid">                        <!-- Page header -->
             
				<div class="row row-tight workspace-layout">
					<div class="col-lg-12">
						<section class="workspace-welcome" aria-label="Resumen de bienvenida">
							<div class="workspace-welcome__content">
								<span class="workspace-welcome__eyebrow">Inicio</span>
								<h1>Hola, <?php echo htmlspecialchars($datos_usuario, ENT_QUOTES, 'UTF-8'); ?></h1>
								<p>Seleccione una aplicación para gestionar sus procesos desde un solo lugar.</p>
							</div>
						</section>
						<section class="app-content-card" aria-labelledby="workspace-title">
							<div class="app-content-card__header">
								<div>
									<span class="app-content-card__eyebrow">Área de trabajo</span>
									<h1 id="workspace-title" class="app-content-card__title">Aplicaciones Ransa</h1>
								</div>
								<span class="app-content-card__status"><i class="fa-solid fa-circle" aria-hidden="true"></i> Sesión activa</span>
							</div>
							<div id="contenedor" name="contenedor" class="app-content-card__body" aria-live="polite">
								<div class="app-empty-state">
									<i class="fa-solid fa-layer-group" aria-hidden="true"></i>
									<p>Seleccione una aplicación del menú para comenzar.</p>
								</div>
							</div>
						</section>
					</div>
					
				</div>
		

				<!-- Footer -->
				<div class="row ransa-workspace-footer">
					<div class="col-md-12">
						<div class="copyright">
							<p>Copyright © 2026 Ransa <a href="https://www.ransa.biz" rel="nofollow" target="_blank">Ransa</a>.</p>
						</div>
					</div>
				</div>

            </div>
          </div>
        </main>
      </div>
    </div>
    <script src="js/vanilla-utils.js"></script>
    <script src="vendor/bootstrap-5.3.8.bundle.min.js"></script>
    <script src="vendor/chartjs/chart.umd.js-4.5.1.min.js"></script>
    <script src="js/bootstrap5-init.js"></script>
    <script src="js/main-vanilla.js"></script>
    <script src="js/modern-plugins.js"></script>

    <!-- Arquitectura de Capas JS (Conexión, Lógica y Front/UI) -->
    <script src="js/layers/connection.js"></script>
    <script src="js/layers/logic.js"></script>
    <script src="js/layers/ui.js"></script>
    <script src="js/layers/legacy-bridges.js"></script>
    <script src="js/layers/response-ui.js"></script>
  </body>
</html>