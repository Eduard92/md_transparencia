<ul class="modal_select">
    <?php foreach($fracciones as $fraccion):?>
        <li><a confirm-action href="<?=base_url('admin/transparencia/obligaciones/create/'.$fraccion->id)?>"><?=$fraccion->numeral?>.- <?=$fraccion->nombre?></a></li>
    <?php endforeach;?>
</ul>