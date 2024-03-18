<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<div class="random-products">
    <?php if (!empty($arResult['ERROR'])): ?>
        <div class="error"><?= htmlspecialcharsbx($arResult['ERROR']) ?></div>
    <?php else: ?>
        <?php foreach ($arResult['ITEMS'] as $item): ?>
            <div class="product">
                <h3><?= htmlspecialcharsbx($item['NAME']) ?></h3>
                <a href="<?= htmlspecialcharsbx($item['DETAIL_PAGE_URL']) ?>">
                    <img src="<?= htmlspecialcharsbx(CFile::GetPath($item['PREVIEW_PICTURE'])) ?>"
                        alt="<?= htmlspecialcharsbx($item['NAME']) ?>" />
                </a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>