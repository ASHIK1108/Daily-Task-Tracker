<!-- Background Particles -->
<div class="bg-particles">
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
</div>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark custom-navbar">
    <div class="container">
        <a class="navbar-brand" href="#"><i class="fas fa-clock me-2"></i>Elite Tracker Pro</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="#" id="todayLink">
                        <i class="fas fa-calendar-day me-1"></i>Today
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" id="calendarLink">
                        <i class="fas fa-calendar me-1"></i>Calendar
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link" href="#" id="reportsLink">
                        <i class="fas fa-chart-line me-1"></i>Reports
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <?= htmlspecialchars($user['nm']) ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="modules/logout">
                        Logout
                    </a>
                </li>
                <li><span id="userId" data-id="<?= htmlspecialchars($user['id']) ?>"></span></span>

            </ul>
        </div>

    </div>
</nav>