<header id="header">
            <div id="site-logo">
                <a href="index.php">
                <img style="width: 200px;" src="images/logo.png" alt="Memteka"/></a>
            </div>
            <div class="navigation-items">
                <ul class="nav-header">
                    <li class="nav-bar-item">
                        <a href="#">
                            <i class="far fa-clock"></i>
                            Nauji
                        </a>
                    </li>
                    <li class="nav-bar-item">
                        <a href="#">
                            <i class="fab fa-hotjar"></i> 
                            Populiariausi
                        </a>
                    </li>
                    <li class="nav-bar-item">
                        <a href="#">
                            <i class="fas fa-arrow-up"></i>
                            Kylantys
                        </a>
                    </li>
					<li class="nav-bar-item">
                        <a href="upload.php">
                            <i class="fas fa-plus-square"></i>
                            Pridėti memą
                        </a>
                    </li>
                    <?php
						session_start();
						if(isset($_SESSION['vartotojo_vardas'])) {
					?>	
						<li class="nav-bar-item">
							<a href="upload.php">
								<i class="fas fa-plus-square"></i>
								Pridėti memą
							</a>
						</li>
						<li class="nav-bar-item">
							<a href="logout.php">
								<i class="fas fa-key"></i>
								Atsijungti
							</a>
						</li>
                    <?php } ?>
                </ul>
            </div>
            <div class="nav-social-icons">
                <i class="fab fa-facebook-square header-icon"></i>
            </div>
            <div id="mobile-nav">
                <div class="top-bar"></div>
                <div class="middle-bar"></div>
                <div class="bottom-bar"></div>
            </div>
            <div class="mobile-menu-background" >
                <div class="mobile-menu-content">
                <div class="mobile-social">
                    <i class="fab fa-facebook-square header-icon"></i>
                </div>
                    <ul>
                    <hr>
                    <li class="mobile-menu-item active">
                        <a href="#">
                            <i class="far fa-clock"></i>
                            Nauji
                        </a>
                    </li>
                    <li class="mobile-menu-item">
                        <a href="#">
                            <i class="fab fa-hotjar"></i> 
                            Populiariausi
                        </a>
                    </li>
                    <li class="mobile-menu-item">
                        <a href="#">
                            <i class="fas fa-arrow-up"></i>
                            Kylantys
                        </a>
                    </li>
					<li class="mobile-menu-item">
                        <a href="upload.php">
                            <i class="fas fa-plus-square"></i>
                            Pridėti memą
                        </a>
                    </li>
                    <?php
						if(isset($_SESSION['vartotojo_vardas'])) {
					?>	
						<li class="mobile-menu-item">
							<a href="upload.php">
								<i class="fas fa-plus-square"></i>
								Pridėti memą
							</a>
						</li>
						<li class="mobile-menu-item">
							<a href="logout.php">
								<i class="fas fa-key"></i>
								Atsijungti
							</a>
						</li>
                    <?php } ?>
                    <hr id="categories-hr">
                    </ul>
                </div>
            </div>
</header>
