<?php
/**
 * Tabs Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 * @param   array $context The context provided to the block by the post or it's parent block.
 */

// Support custom "anchor" values.
$anchor = '';
if ( ! empty( $block['anchor'] ) ) {
    $anchor = 'id="' . esc_attr( $block['anchor'] ) . '" ';
}

// Create class attribute allowing for custom "className" and "align" values.
$base_class = $class_name = 'wp-block-linchpin-tabs';
if ( ! empty( $block['className'] ) ) {
    $class_name .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
    $class_name .= ' align' . $block['align'];
}

$tab_posts = get_field( 'tab_posts' );

if ( empty( $tab_posts ) ) {
	return;
}

$tab_args = [
	'post_type' => 'tab',
	'posts_per_page' => count( $tab_posts ),
	'post__in' => $tab_posts,
];

$tabs = new \WP_Query( $tab_args );
?>

<?php if ( $tabs->have_posts() ) : ?>
	<div class="<?php echo esc_attr( $class_name ); ?>">
		<div class="<?php echo esc_attr( $base_class ); ?>__navigation">
			<?php while ( $tabs->have_posts() ) : $tabs->the_post(); ?>
				<?php
				// Navigation item classes.
				$nav_item_classes = [
					$base_class . '__navigation-item',
				];

				// Include active class if it is the first in the query.
				if ( 0 === $tabs->current_post ) {
					$nav_item_classes[] = 'active';
				}
				?>

				<div class="<?php echo esc_attr( implode( ' ', $nav_item_classes ) ); ?>">
					<p><?php the_title(); ?></p>
				</div>
			<?php endwhile; ?>
		</div>

		<div class="<?php echo esc_attr( $base_class ); ?>__content-container">
			<?php while ( $tabs->have_posts() ) : $tabs->the_post(); ?>
				<?php
				// Content item classes.
				$content_item_classes = [
					$base_class . '__item',
				];

				// Include active class if it is the first in the query.
				if ( 0 === $tabs->current_post ) {
					$content_item_classes[] = 'active';
				}
				?>

				<div class="<?php echo esc_attr( implode( ' ', $content_item_classes ) ); ?>">
					<?php the_title( '<h2>', '</h2>' ); ?>
					<?php the_content(); ?>
				</div>
			<?php endwhile; ?>
		</div>
	</div>
<?php endif; ?>

<script>
	let tabs = document.querySelectorAll('.wp-block-linchpin-tabs__navigation-item');
	let tabs_content = document.querySelectorAll('.wp-block-linchpin-tabs__item');

	// Loop through each tab and add an event listener, using ES6 arrow functions.
	tabs.forEach(tab => {
		tab.addEventListener('click', () => {
			// Remove the active class from all tabs.
			tabs.forEach(tab => {
				tab.classList.remove('active');
			});

			// Add the active class to the clicked tab.
			tab.classList.add('active');

			// Get the index of the clicked tab.
			let index = Array.from(tabs).indexOf(tab);

			// Remove the active class from all tab content.
			tabs_content.forEach(tab_content => {
				tab_content.classList.remove('active');
			});

			// Add the active class to the corresponding tab content.
			tabs_content[index].classList.add('active');
		});
	});
</script>