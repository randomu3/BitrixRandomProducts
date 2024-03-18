<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<div class="random-products">
    <?php foreach ($arResult['ITEMS'] as $item): ?>
        <div class="product">
            <h3><?= $item['NAME'] ?></h3>
            <a href="<?= $item['DETAIL_PAGE_URL'] ?>">
                <img src="<?= CFile::GetPath($item['PREVIEW_PICTURE']) ?>" alt="<?= $item['NAME'] ?>" />
            </a>
        </div>
    <?php endforeach; ?>
</div>