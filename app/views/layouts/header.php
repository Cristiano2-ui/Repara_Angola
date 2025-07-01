<?php if ($_SESSION['user_id'] != null): ?>

    <header class="h-16 bg-slate-800 text-white flex items-center justify-center">

        <div class="container flex items-center  justify-between px-2">

            <span><img src="public/Recicla.png" alt="" class="size-12"></span>

            <nav class="flex items-center gap-10 group-hover:text-amber-300">
                <a class="hover:text-teal-500" href="home">Início</a>
                <a class="hover:text-teal-500" href="sobre">Sobre</a>


                <!-- Ícone de perfil com link -->
                <a class="hover:text-teal-500" href="perfil" class="flex items-center gap-2">
                    <i class="ri-user-fill"></i>
                    <span>Perfil</span>
                </a>
            </nav>
        </div>
    </header>

<?php else: ?>

    <header class="h-16 bg-slate-800 text-white flex items-center justify-center">

        <div class="container flex items-center  justify-between px-2">

            <span><img src="public/Recicla.png" alt="" class="size-12"></span>

            <nav class="flex items-center gap-10">
                <a class="hover:text-teal-500" href="home">inicio</a>
                <a class="hover:text-teal-500" href="sobre">Sobre</a>
                <a href="login" class="flex items-center justify-center rounded-lg bg-teal-600 h-10 p-2 hover:bg-teal-800">login <i class="ri-arrow-right-line ml-1"></i> </a>
            </nav>

        </div>
    </header>

<?php endif ?>