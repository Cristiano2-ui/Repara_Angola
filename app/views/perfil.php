<?php

$dados = $_SESSION;

// var_dump($dados);

?>


<?php include 'layouts/header.php' ?>

<main class="relative flex flex-col items-center w-full gap-4 min-h-screen bg-teal-800 overflow-hidden  px-2 md:px-0 pb-10">

    <img src="public/image/Reciclagem-mecanica.jpg" alt="bg" class="fixed object-cover h-[50%] opacity-5 z-0">




    <!-- end -->
    <section class="container flex flex-col h-80 bg-gray-800/80 rounded-2xl overflow-hidden z-10 mt-20">

        <div class="relative flex flex-col items-center justify-center gap-2 p-4 ">

            <div class="absolute top-0 h-24 w-full bg-linear-120  from-teal-500" id="bg-template">

            </div>


            <div class="flex items-center justify-center size-24 border text-white rounded-full p-2  bg-slate-400 z-10">
                <i class="ri-user-line text-6xl text-white"></i>
            </div>

            <div class="flex flex-col items-center w-full text-white z-10">
                <h1><?= $dados['user_nome'] ?></h1>
                <h2 class="text-white/50"><?= $dados['user_email'] ?></h2>
            </div>

        </div>


        <div class="flex justify-center gap-4 mt-auto ml-auto p-4  rounded-b-2xl">
            <a href="editar" class="p-2 h-10 text-teal-500 flex items-center justify-center">
                <i class="ri-edit-line mr-2"></i> editar
            </a>
        </div>
    </section>
    <!-- end -->

    <?php include 'layouts/navbarProfile.php' ?>


</main>

<?php include 'layouts/footer.php' ?>