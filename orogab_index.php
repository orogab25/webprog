<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
        <title>Hungerian</title>
        
		<link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css" media="screen" type="text/css">
        <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Playball' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/style-portfolio.css">
        <link rel="stylesheet" href="css/picto-foundry-food.css" />
        <link rel="stylesheet" href="css/jquery-ui.css">
        <link href="css/font-awesome.min.css" rel="stylesheet">
        <link rel="icon" href="favicon-1.ico" type="image/x-icon">
    </head>

	<?php
	session_start();
	require_once("orogab_functions.php");
	$connect=dbconnect();
	
	//Feltételek
	if(!isset($_SESSION['visit']))
	{		
		$_SESSION['visit']=true;
		visit($connect);
	}
	
	if (isset($_POST["addReservationOk"]))
	{
		addReservation($connect);
		header("Location: orogab_index.php#reservation");
	}
	
	if (isset($_POST["addSubscribeOk"]))
	{
		addSubscribe($connect);
		header("Location: orogab_index.php#subscribe");
	}
	
	if(isset($_GET["activate"]) && isset($_GET["email"]))
	{
		subscribeConfirm($connect);
	}
	
	if(isset($_GET["delete"]) && isset($_GET["email"]))
	{
		subscribeDelete($connect);
	}
	?>
	
    <body>
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="row">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="orogab_index.php">Hungerian</a>
                    </div>

                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav main-nav  clear navbar-right ">
                            <li><a class="navactive color_animation" href="#top">KEZDŐLAP</a></li>
                            <li><a class="color_animation" href="#about">BEMUTATKOZÁS</a></li>
							<li><a class="color_animation" href="#contact">KAPCSOLAT</a></li>
                            <li><a class="color_animation" href="#weekly">HETI MENÜ</a></li>
                            <li><a class="color_animation" href="#daily">NAPI AJÁNLAT</a></li>
                            <li><a class="color_animation" href="#reservation">ASZTALFOGLALÁS</a></li>
							<li><a class="color_animation" href="#subscribe">FELIRATKOZÁS</a></li>
							<li><a class="color_animation" href="#dataProtection">ADATVÉDELEM</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
         
        <div id="top" class="starter_container bg">
            <div class="follow_container">
                <div class="col-md-6 col-md-offset-3">
                    <h2 class="top-title">Hungerian</h2>
                    <h2 class="white second-title">" Igazi magyar ételek! "</h2>
                    <hr>
                </div>
            </div>
        </div>
		
        <!-- ============ Bemutatkozás ============= -->
        <section id="about" class="description_content">
            <div class="container">
                <div class="col-md-6">
                    <h1>Rólunk</h1>
                    <div class="fa fa-cutlery fa-2x"></div>
                    <p class="desc-text">Az éttermünk az egyszerűség híve. Kiváló ételek, sör és kiszolgálás. Kis csapat vagyunk Pécsről akik lehetővé teszik az gyors és egyszerű éttermi étkezést. Gyere és csatlakozz hozzánk hogy megízleld te is.</p>
                </div>
                <div class="col-md-6">
                    <div class="img-section">
                       <img src="images/kabob.jpg" width="250" height="220">
                       <img src="images/limes.jpg" width="250" height="220">
                       <div class="img-section-space"></div>
                       <img src="images/radish.jpg"  width="250" height="220">
                       <img src="images/corn.jpg"  width="250" height="220">
                   </div>
                </div>
            </div>
        </section>
		
		<!-- ============ Elérhetőség  ============= -->
        <section id="contact" class="social_connect">
            <div class="map">
			<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2767.800313694004!2d18.21273231512292!3d46.07502270049887!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4742b196f860c39b%3A0x17bb2bae526f9ad9!2zUMOpY3MsIFBldMWRZmkgU8OhbmRvciB1dGNhIDEwLCA3NjI0!5e0!3m2!1shu!2shu!4v1543766495866" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
            </div>
			<div class="container">
                <span class="social_heading">Telefon</span>
                <span class="social_info"><a class="color_animation" href="tel:883-335-6524">(941) 883-335-6524</a></span>
			</div>
		</section>
		
		<!-- ============ Heti menü  ============= -->
        <section id ="weekly" class="description_content">
             <div class="bread background_content">
                <h1>Heti menü</h1>
             </div>
            <div class="text-content container"> 
                <div class="container">
                    <div class="row">
                        <div id="w">
                            <ul id="filter-list" class="clearfix">
                                <li class="filter" data-filter="all">Összes</li>
                                <li class="filter" data-filter="monday">Hétfő</li>
                                <li class="filter" data-filter="tuesday">Kedd</li>
                                <li class="filter" data-filter="wednesday">Szerda</li>
                                <li class="filter" data-filter="thursday">Csütörtök</li>
								<li class="filter" data-filter="friday">Péntek</li>
								<li class="filter" data-filter="saturday">Szombat</li>
								<li class="filter" data-filter="sunday">Vasárnap</li>
                            </ul>  
                            <ul id="portfolio">
							<?php
							selectWeeklyMenu($connect);
							?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>  
        </section>
        
        <!-- ============ Napi ajánlat  ============= -->
        <section id="daily" class="description_content">
            <div  class="featured background_content">
                <h1>Napi ajánlat</h1>
            </div>
            <div class="text-content container">
				<?php
				selectDailyMenu($connect);
				?>
            </div>
        </section>
		
        <!-- ============ Asztalfoglalás  ============= -->
        <section  id="reservation"  class="description_content">
            <div class="beer background_content">
                <h1>Asztalfoglalás</h1>
            </div>
            <div class="container"> 
                <div class="inner contact">
                    <div class="contact-form">
                        <form id="contact-us" method="post" action="">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-8 col-md-6 col-xs-12">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-xs-6">
                                                <input type="text" name="name" id="name" required="required" class="form" placeholder="Név" />
												<input type="email" name="email" id="email" required="required" class="form" placeholder="E-mail" /> 
                                                <input type="datetime-local" name="datepicker" id="datepicker" required="required" class="form" placeholder="Foglalás időpontja" />
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-xs-6">
                                                <input type="text" name="phone" id="phone" class="form" placeholder="Telefon" />
                                                <input type="number" name="gnumber" id="gnumber" required="required" class="form" placeholder="Fő" />
                                            </div>

                                            <div class="col-xs-6 ">
                                                <button type="submit" id="addReservationOk" name="addReservationOk" class="text-center form-btn form-btn">Foglalás</button> 
                                            </div>
                                            
                                        </div>
                                    </div>
									
									<div class="col-lg-4 col-md-6 col-xs-12">
                                        <div class="right-text">
                                            <h2>Nyitvatartás</h2><hr>
                                            <p>Hétfő - Péntek: 07:30 - 21:30</p>
                                            <p>Szombat: 07:30 - 18:30</p>
                                            <p>Vasárnap: 08:30 - 11:30</p>
                                        </div>
                                    </div>
									
                                </div>
                            </div>
                            <div class="clear"></div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

		<!-- ============ Feliratkozás  ============= -->
		<section id="subscribe">
		<div class="beer background_content">
            <h1>Feliratkozás</h1>
        </div>
		<div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="inner contact">
                            <div class="contact-form">
                                <form id="contact-us" method="post" action="">
                                    <div class="col-md-6 ">
                                        <input type="text" name="name" id="name" required="required" class="form" placeholder="Név" />
                                        <input type="email" name="email" id="email" required="required" class="form" placeholder="E-mail" />
                                    </div>
                                    <div class="relative fullwidth col-xs-12">
                                        <button type="submit" id="submit" name="addSubscribeOk" class="form-btn">Feliratkozás</button> 
                                    </div>
                                    <div class="clear"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		
		<!-- ============ Adatvédelmi tájékoztató ============= -->
        <section id="dataProtection" class="description_content">
            <div class="pricing background_content">
                <h1>Adatvédelmi tájékoztató</h1>
            </div>
			<div class="container" align="left">			
			<br><h2 class="center">Étterem információ</h2><br>
			<p>
			A Hungerian étterem (továbbiakban: étterem) online asztalfoglalással és hírlevél feliratkozással kapcsolatban kezeli a regisztrált felhasználók személyes adatait,
			kizárólag a foglalások és feliratkozások teljesítésének céljából.<br>
			Csak olyan személyes adatot kezel, amely az étterem céljának megvalósulásához elengedhetetlen, és a cél elérésére alkalmas.<br>
			A személyes adatokat csak a cél megvalósulásához szükséges mértékben és ideig kezeli.<br>
			<br>
			</p>
			<p>
			Az étterem minden elvárható módon védi a felhasználóknak az oldalon kezelt személyes adatait.<br>
			<br>
			</p>
			<p>
			Az étterem címe: 7624 Pécs, Petőfi Sándor utca 10.<br>
			Az étterem elérhetősége: (941)883-335-6524, hungerian@gmail.com<br>
			<br>
			</p>
			<p>
			Az adatkezelés jogalapja: 2011. évi CXII. törvény az információs önrendelkezési jogról és az információszabadságról 5. § (1) bekezdés a) Az érintett hozzájárulása.<br>
			<br>
			</p>
			<p>
			Hozzájárulás az adatkezeléshez: A felasználó asztalfoglalásnál és hírlevél feliratkozásnál kifejezetten hozzájárulását adja az általa önkéntesen megadott személyes adatainak kezeléséhez.<br>
			<br>
			</p>
			<p>
			Az érintettek köre:<br>
			-asztalfogalalással rendelkező felhasználók<br>
			-hírlevélre feliratkozott felhasználók<br>
			<br>
			</p>
			<p>
			A kezelt adatok köre: az asztalfoglalással rendelkező és feliratkozott felhasználók által a regisztráció során megadott adatok:<br> 
			-asztalfoglalás: Név, E-mail, Telefon<br>
			-feliratkozás: Név, E-mail<br>
			<br>
			</p>
			<p>
			Az adatgyűjtés célja: az asztalfoglalások és feliratkozási feladatok teljesítése.<br>
			<br>
			</p>
			<p>
			Az adatkezelés időtartama: az elektronikusan tárolt személyes adatok az asztalfoglalás elvégzését követően 30 nap után törlésre kerülnek.<br>
			A feliratkozással kapcsolatos adatok a leiratkozást követően megsemmisülnek.<br>
			<br>
			</p>
			<p>
			A vásárló kérelmezheti az étteremnél<br>
			- tájékoztatását személyes adatai kezeléséről,<br>
			- személyes adatainak helyesbítését,<br>
			- személyes adatainak törlését vagy zárolását.<br>
			<br>
			</p>
			<p>
			Ha korábban asztalfoglalást küldött el vagy feliratkozott, akkor a foglaláshoz illetve feliratkozáshoz az elküldés pillanatában érvényes adatok lettek csatolva.<br>
			Személyes adatairól tájékoztatást, ezek módosítását vagy törlését az étteremnél személyesen, telefonon vagy e-mailben kezdeményezheti.<br>
			<br>
			</p>
			<p>
			Az étterem a kérelem benyújtásától számított legrövidebb idő alatt, legfeljebb azonban 7 napon belül, közérthető formában, a regisztrált felhasználó erre irányuló kérelmére írásban megadja a tájékoztatást.<br>
			Ha a regisztrált felhasználó kérelmét jogosnak találja, akkor haladéktalanul intézkedik a személyes adatainak helyesbítése vagy törlése érdekében.<br>
			<br>
			</p>
			<p>
			Ha a regisztrált felhasználó nem elégedett az adatkezelő válaszával,
			akkor személyes adatai védelméhez való jogát polgári bíróság előtt érvényesítheti,
			továbbá a Nemzeti Adatvédelmi és Információszabadság Hatósághoz <a href="https://www.naih.hu/index.html">( https://www.naih.hu/index.html )</a> fordulhat.<br>
			<br>
			</p>
			<p>
			A 2011. évi CXII. törvény az információs önrendelkezési jogról és az információszabadságról itt olvasható:
			<a href="https://www.njt.hu/cgi_bin/njt_doc.cgi?docid=139257.243466">Nemzeti Jogszabálytár</a><br>
			<br>
			</p>
            </div>
        </section>
		
		<!-- ============ Lábjegyzet  ============= -->
        <footer class="sub_footer">
            <div class="container">
                <div class="col-md-4"><p class="sub-footer-text text-center">&copy; Restaurant 2014, Theme by <a href="https://themewagon.com/">ThemeWagon</a></p></div>
                <div class="col-md-4"><p class="sub-footer-text text-center">Back to <a href="#top">TOP</a></p>
                </div>
                <div class="col-md-4"><p class="sub-footer-text text-center">Built With Care By <a href="#" target="_blank">Us</a></p></div>
            </div>
        </footer>

        <script type="text/javascript" src="js/jquery-1.10.2.min.js"> </script>
        <script type="text/javascript" src="js/bootstrap.min.js" ></script>
        <script type="text/javascript" src="js/jquery-1.10.2.js"></script>     
        <script type="text/javascript" src="js/jquery.mixitup.min.js" ></script>
        <script type="text/javascript" src="js/main.js" ></script>
		
    </body>
</html>