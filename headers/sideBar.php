<div th:fragment="sideBar" class="wrapper">
    <nav id="sidebar" class="d-flex flex-column bg-dark">
        <div class="sidebar-header d-flex align-items-center text-light">
            <i class="fa-solid fa-chart-line me-2"></i> Oficina
        </div>
        <ul class="nav flex-column mb-auto mt-3">
            <li class="nav-item">
                <a href="/oficina/clientes" class="nav-link text-light">
                    <i class="fa-solid fa-users"></i> Clientes
                </a>
            </li>
            <li class="nav-item">
                <a href="/oficina/veiculos" class="nav-link text-light">
                    <i class="fa-solid fa-box"></i> Veículos
                </a>
            </li>
            <li class="nav-item">
                <a href="/oficina/ordens" class="nav-link text-light">
                    <i class="fa-solid fa-cart-shopping"></i> Ordens
                </a>
            </li>
            <li class="nav-item">
                <a href="/oficina/pagamentos" class="nav-link text-light">
                    <i class="fa-solid fa-comments"></i> Pagamentos
                </a>
            </li>
            <li class="nav-item">
                <a href="/oficina/usuario/perfil" class="nav-link text-light">
                    <i class="fa-solid fa-user-gear"></i> Meu Perfil
                </a>
            </li>
            <li sec:authorize="hasRole('ADM')" class="nav-item">
                <a href="/adm/home" class="nav-link text-light">
                    <i class="fa-solid fa-user-tie"></i> Painel ADM
                </a>
            </li>
        </ul>

        <div class="border-top bg-danger">
            <a href="/oficina/auth/logout.php" class="nav-link logout text-light">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </nav>

    <script>
        document.getElementById('sidebarCollapse')?.addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('active');
        });
    </script>
</div>