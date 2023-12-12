<?php
$frases_copyright = array(
  'Ningún unicornio resultó herido durante el desarrollo de esta aplicación',
  'El icono es un caballo porque el del unicornio era de pago',
  'Si estas leyendo esto me debes una cerveza'
);
 ?>
<script>
         $(document).ready(function(){
//$('.loader').hide();


         });
</script>
<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Copyright &copy; Uni Corn productions 2021. <?= $frases_copyright[array_rand($frases_copyright)]  ?>.</span>
        </div>
    </div>
</footer>
