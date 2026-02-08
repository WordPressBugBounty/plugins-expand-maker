<?php
use \yrm\TypesNavBar;

$currentExtensions = YrmConfig::extensions();
$extensionsResult  = ReadMoreAdminHelper::separateToActiveAndNotActive($currentExtensions);

$allowedTag = ReadMoreAdminHelper::getAllowedTags();

$upgradeButton = '';
if (YRM_PKG == YRM_FREE_PKG) {
	$upgradeButton = ReadMoreAdminHelper::upgradeButton();
}

$ideasButton = ReadMoreAdminHelper::newIdeasButton();

$adminBase = admin_url('admin.php?page=button');

$buildTypeUrl = static function(string $type) use ($adminBase): string {
	return add_query_arg(['yrm_type' => $type], $adminBase);
};

/**
 * Renders a type box (recommended: <a> for accessibility)
 */
$renderTypeBox = static function(array $args) {
	// args: href, shortKey, title, isPro(optional), video(optional), extraClass(optional)
	$href       = $args['href'] ?? '#';
	$shortKey   = $args['shortKey'] ?? '';
	$title      = $args['title'] ?? '';
	$isPro      = !empty($args['isPro']);
	$video      = $args['video'] ?? '';
	$extraClass = $args['extraClass'] ?? '';

	$wrapTag = ($isPro && !empty($args['targetBlank'])) ? 'a' : 'a';
	$target  = !empty($args['targetBlank']) ? ' target="_blank" rel="noopener noreferrer"' : '';

	?>
	<<?php echo $wrapTag; ?> class="product-banner <?php echo esc_attr($extraClass); ?>"
		href="<?php echo esc_url($href); ?>"<?php echo $target; ?>>
		<div class="yrm-types yrm-<?php echo esc_attr($shortKey); ?><?php echo $isPro ? ' type-banner-pro' : ''; ?>">
			<?php if ($isPro): ?>
				<p class="yrm-type-title-pro"><?php echo esc_html__('PRO Features', YRM_LANG); ?></p>
			<?php endif; ?>
		</div>

		<div class="yrm-type-view-footer">
			<span class="yrm-promotion-title"><?php echo esc_html($title); ?></span>

			<?php if (!empty($video)): ?>
				<span class="yrm-play-promotion-video" data-href="<?php echo esc_url($video); ?>"></span>
			<?php endif; ?>
		</div>
	</<?php echo $wrapTag; ?>>
	<?php
};
?>
<div class="ycf-bootstrap-wrapper">
	<h3>
		<?php echo esc_html__('Add New Read More Type', YRM_LANG); ?>
		<?php echo wp_kses($upgradeButton, $allowedTag); ?>
		<?php echo wp_kses($ideasButton, $allowedTag); ?>
	</h3>

	<?php echo TypesNavBar::render(); ?>

	<div class="boxes">
		<?php if (ReadMoreAdminHelper::allowToShowType('button')): ?>
			<?php $renderTypeBox([
				'href' => $buildTypeUrl('button'),
				'shortKey' => 'button',
				'title' => __('Button', YRM_LANG),
			]); ?>
		<?php endif; ?>

		<?php if (ReadMoreAdminHelper::allowToShowType('inline')): ?>
			<?php $renderTypeBox([
				'href' => $buildTypeUrl('inline'),
				'shortKey' => 'inline',
				'title' => __('Inline', YRM_LANG),
			]); ?>
		<?php endif; ?>

		<?php if (ReadMoreAdminHelper::allowToShowType('accordion')): ?>
			<?php $renderTypeBox([
				'href' => $buildTypeUrl('accordion'),
				'shortKey' => 'accordion',
				'title' => __('Accordion', YRM_LANG),
			]); ?>
		<?php endif; ?>

		<?php foreach (($extensionsResult['active'] ?? []) as $extension): ?>
			<?php
			// NOTE: boxTitle ideally should be a static i18n key; if it's already translated text, use esc_html only.
			$title = isset($extension['boxTitle']) ? $extension['boxTitle'] : '';
			$short = isset($extension['shortKey']) ? $extension['shortKey'] : '';
			?>
			<?php $renderTypeBox([
				'href' => $buildTypeUrl($short),
				'shortKey' => $short,
				'title' => $title,
			]); ?>
		<?php endforeach; ?>

		<?php if (ReadMoreAdminHelper::allowToShowType('link')): ?>
			<?php $renderTypeBox([
				'href' => $buildTypeUrl('link'),
				'shortKey' => 'link',
				'title' => __('Link button', YRM_LANG),
			]); ?>
		<?php endif; ?>

		<?php if (ReadMoreAdminHelper::allowToShowType('alink')): ?>
			<?php $renderTypeBox([
				'href' => $buildTypeUrl('alink'),
				'shortKey' => 'alink',
				'title' => __('Link', YRM_LANG),
			]); ?>
		<?php endif; ?>

		<?php if (YRM_PKG > YRM_SILVER_PKG && ReadMoreAdminHelper::allowToShowType('popup')): ?>
			<?php $renderTypeBox([
				'href' => $buildTypeUrl('popup'),
				'shortKey' => 'popup',
				'title' => __('Button & popup', YRM_LANG),
			]); ?>

			<?php $renderTypeBox([
				'href' => $buildTypeUrl('inlinePopup'),
				'shortKey' => 'inline-popup',
				'title' => __('Inline & popup', YRM_LANG),
			]); ?>

			<?php $renderTypeBox([
				'href' => $buildTypeUrl('accordionPopup'),
				'shortKey' => 'accordion-popup',
				'title' => __('Accordion & popup', YRM_LANG),
			]); ?>
		<?php endif; ?>

		<?php if (YRM_PKG == YRM_FREE_PKG && ReadMoreAdminHelper::allowToShowType('popup')): ?>
			<?php
			$proHref = defined('YRM_PRO_URL') ? YRM_PRO_URL : '#';
			$video   = defined('YRM_POPUP_VIDEO') ? YRM_POPUP_VIDEO : '';
			?>
			<?php $renderTypeBox([
				'href' => $proHref,
				'shortKey' => 'popup',
				'title' => __('Button & popup', YRM_LANG),
				'isPro' => true,
				'video' => $video,
				'targetBlank' => true,
			]); ?>

			<?php $renderTypeBox([
				'href' => $proHref,
				'shortKey' => 'inline-popup',
				'title' => __('Inline & popup', YRM_LANG),
				'isPro' => true,
				'video' => $video,
				'targetBlank' => true,
			]); ?>

			<?php $renderTypeBox([
				'href' => $proHref,
				'shortKey' => 'accordion-popup',
				'title' => __('Accordion & popup', YRM_LANG),
				'isPro' => true,
				'targetBlank' => true,
			]); ?>
		<?php endif; ?>
	</div>
</div>

<?php if (!empty($extensionsResult['passive'])): ?>
	<div class="yrm-add-new-extensions-wrapper">
		<span class="yrm-add-new-extensions"><?php echo esc_html__('Extensions', YRM_LANG); ?></span>
	</div>

	<?php foreach (($extensionsResult['comingSoon'] ?? []) as $extension): ?>
		<?php include(YRM_TEMPLATES_FIND . 'extensionBox.php'); ?>
	<?php endforeach; ?>

	<?php foreach ($extensionsResult['passive'] as $extension): ?>
		<?php include(YRM_TEMPLATES_FIND . 'extensionBox.php'); ?>
	<?php endforeach; ?>
<?php endif; ?>

<div class="yrm-add-new-extensions-wrapper">
	<span class="yrm-add-new-extensions"><?php echo esc_html__('More plugins', YRM_LANG); ?></span>
</div>
<div class="yrm-add-new-plugins">
	<?php require_once(dirname(__FILE__) . '/morePlugins.php'); ?>
</div>
